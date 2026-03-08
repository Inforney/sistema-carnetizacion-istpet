<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Carnet;
use App\Models\Profesor;
use App\Models\SolicitudPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EstudianteController extends Controller
{
    /**
     * Listar todos los estudiantes
     */
    public function index(Request $request)
    {
        // Incluir graduados: su historial se preserva y son visibles en la lista
        $query = Usuario::whereIn('tipo_usuario', ['estudiante', 'graduado'])->with('carnet');

        // Búsqueda
        if ($request->filled('buscar')) {
            $query->buscar($request->buscar);
        }

        // Filtro por ciclo
        if ($request->filled('ciclo')) {
            $query->porCiclo($request->ciclo);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por rol
        if ($request->filled('rol')) {
            $query->where('tipo_usuario', $request->rol);
        }

        $estudiantes = $query->orderBy('apellidos')->paginate(20);

        return view('admin.estudiantes.index', compact('estudiantes'));
    }

    /**
     * Mostrar formulario de crear estudiante
     */
    public function create()
    {
        return view('admin.estudiantes.create');
    }

    /**
     * Guardar nuevo estudiante
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'tipo_documento' => 'required|in:cedula,pasaporte,ruc',
            'cedula' => 'required|string|max:20|unique:usuarios,cedula',
            'correo_institucional' => 'required|email|unique:usuarios,correo_institucional',
            'celular' => 'required|string|max:15',
            'ciclo_nivel' => 'required|in:PRIMER NIVEL,SEGUNDO NIVEL,TERCER NIVEL,CUARTO NIVEL',
            'nacionalidad' => 'required|string|max:50',
            'carrera' => 'required|string|max:100', // NUEVO
            'password' => 'required|string|min:8',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // NUEVO
            'generar_carnet' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            // Manejar foto si se subió
            $fotoUrl = null;
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $nombreFoto = 'foto_' . $validated['cedula'] . '_' . time() . '.' . $foto->getClientOriginalExtension();

                // Guardar en storage/app/public/fotos_perfil/
                $path = $foto->storeAs('fotos_perfil', $nombreFoto, 'public');
                $fotoUrl = 'storage/' . $path;
            }

            $usuario = Usuario::create([
                'nombres' => $validated['nombres'],
                'apellidos' => $validated['apellidos'],
                'cedula' => $validated['cedula'],
                'tipo_documento' => $validated['tipo_documento'],
                'correo_institucional' => $validated['correo_institucional'],
                'celular' => $validated['celular'],
                'ciclo_nivel' => $validated['ciclo_nivel'],
                'nacionalidad' => $validated['nacionalidad'],
                'carrera' => $validated['carrera'], // NUEVO
                'foto_url' => $fotoUrl, // NUEVO
                'tipo_usuario' => 'estudiante',
                'estado' => 'activo',
                'password' => Hash::make($validated['password']),
                'fecha_registro' => Carbon::now(),
            ]);

            // Generar carnet si se solicitó
            if ($request->has('generar_carnet') && $request->generar_carnet) {
                $codigoQr = 'ISTPET-' . date('Y') . '-' . strtoupper($usuario->cedula);

                Carnet::create([
                    'usuario_id' => $usuario->id,
                    'codigo_qr' => $codigoQr,
                    'fecha_emision' => Carbon::now(),
                    'fecha_vencimiento' => Carbon::now()->addMonths(6),
                    'estado' => 'activo',
                ]);
            }

            DB::commit();

            return redirect()->route('admin.estudiantes.index')->with(
                'success',
                'Estudiante creado exitosamente.'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al crear estudiante: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de estudiante
     */
    public function show($id)
    {
        $estudiante = Usuario::with(['carnet', 'accesos'])->findOrFail($id);

        // Estadísticas del estudiante
        $stats = [
            'total_accesos' => $estudiante->accesos()->count(),
            'accesos_mes' => $estudiante->accesos()->whereMonth('fecha_entrada', Carbon::now()->month)->count(),
            'ultimo_acceso' => $estudiante->accesos()->latest('fecha_entrada')->first(),
        ];

        // Solicitudes de cambio de contraseña de este estudiante
        $solicitudes = SolicitudPassword::where('usuario_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Contraseña temporal conocida (solo si password_temporal = true)
        $passwordConocida = $estudiante->password_temporal
            ? 'ISTPET' . substr($estudiante->cedula, -4)
            : null;

        return view('admin.estudiantes.show', compact('estudiante', 'stats', 'solicitudes', 'passwordConocida'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $estudiante = Usuario::findOrFail($id);
        return view('admin.estudiantes.edit', compact('estudiante'));
    }

    /**
     * Actualizar estudiante
     */
    public function update(Request $request, $id)
    {
        $estudiante = Usuario::findOrFail($id);

        $validated = $request->validate([
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'correo_institucional' => 'required|email|unique:usuarios,correo_institucional,' . $id,
            'celular' => 'required|string|max:15',
            'ciclo_nivel' => 'required|in:PRIMER NIVEL,SEGUNDO NIVEL,TERCER NIVEL,CUARTO NIVEL',
            'nacionalidad' => 'required|string|max:50',
            'carrera' => 'required|string|max:100', // NUEVO
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // NUEVO: max 2MB
            'eliminar_foto' => 'nullable|boolean', // NUEVO
        ]);

        // Manejar foto nueva
        if ($request->hasFile('foto')) {
            // Eliminar foto anterior si existe
            if ($estudiante->foto_url) {
                $rutaAnterior = str_replace('storage/', '', $estudiante->foto_url);
                Storage::disk('public')->delete($rutaAnterior);
            }

            // Guardar nueva foto
            $foto = $request->file('foto');
            $nombreFoto = 'foto_' . $estudiante->cedula . '_' . time() . '.' . $foto->getClientOriginalExtension();
            $path = $foto->storeAs('fotos_perfil', $nombreFoto, 'public');
            $validated['foto_url'] = 'storage/' . $path;
        }

        // Eliminar foto si se marcó la opción
        if ($request->has('eliminar_foto') && $request->eliminar_foto) {
            if ($estudiante->foto_url) {
                $rutaAnterior = str_replace('storage/', '', $estudiante->foto_url);
                Storage::disk('public')->delete($rutaAnterior);
            }
            $validated['foto_url'] = null;
        }

        $estudiante->update($validated);

        return redirect()->route('admin.estudiantes.show', $id)->with(
            'success',
            'Información actualizada exitosamente.'
        );
    }

    /**
     * Bloquear/Desbloquear estudiante
     */
    public function toggleEstado($id)
    {
        $estudiante = Usuario::findOrFail($id);

        // No permitir cambiar estado de un graduado (ya es profesor)
        if ($estudiante->tipo_usuario === 'graduado') {
            return redirect()->back()->with('error', 'No se puede cambiar el estado de un graduado. Gestiona su cuenta desde el módulo de Profesores.');
        }

        $nuevoEstado = $estudiante->estado === 'activo' ? 'bloqueado' : 'activo';
        $estudiante->update(['estado' => $nuevoEstado]);

        // También bloquear/desbloquear el carnet
        if ($estudiante->carnet) {
            $estudiante->carnet->update(['estado' => $nuevoEstado]);
        }

        $mensaje = $nuevoEstado === 'bloqueado'
            ? 'Estudiante bloqueado. No podrá acceder al sistema.'
            : 'Estudiante desbloqueado. Ya puede acceder al sistema.';

        return redirect()->back()->with('success', $mensaje);
    }

    /**
     * Eliminar estudiante
     */
    public function destroy($id)
    {
        $estudiante = Usuario::findOrFail($id);

        // Eliminar foto si existe
        if ($estudiante->foto_url) {
            $rutaFoto = str_replace('storage/', '', $estudiante->foto_url);
            Storage::disk('public')->delete($rutaFoto);
        }

        // Eliminar carnet asociado (si existe)
        if ($estudiante->carnet) {
            $estudiante->carnet->delete();
        }

        $estudiante->delete();

        return redirect()->route('admin.estudiantes.index')->with(
            'success',
            'Estudiante eliminado exitosamente.'
        );
    }

    /**
     * Resetear contraseña de estudiante y generar enlace WhatsApp
     */
    public function resetPassword($id)
    {
        $estudiante = Usuario::findOrFail($id);

        $passwordTemp = 'ISTPET' . substr($estudiante->cedula, -4);

        $estudiante->update([
            'password' => Hash::make($passwordTemp),
            'password_temporal' => true,
        ]);

        // Generar enlace WhatsApp con el mensaje pre-escrito
        $telefono = $estudiante->celular;
        $telefonoIntl = '593' . ltrim($telefono, '0'); // Ecuador: 593 + número sin 0
        $mensaje = "Hola {$estudiante->nombreCompleto}, el administrador del Sistema ISTPET ha restablecido tu contraseña.\n\nNueva contraseña temporal: *{$passwordTemp}*\n\nIngresa al sistema y cámbiala de inmediato.\n" . config('app.url');
        $waLink = 'https://wa.me/' . $telefonoIntl . '?text=' . urlencode($mensaje);

        return redirect()->back()
            ->with('success', "Contraseña reseteada para {$estudiante->nombreCompleto}. Contraseña temporal: {$passwordTemp}")
            ->with('whatsapp', [
                'nombre' => $estudiante->nombreCompleto,
                'telefono' => $telefono,
                'password' => $passwordTemp,
                'link' => $waLink,
            ]);
    }

    /**
     * Búsqueda AJAX para autocompletado
     */
    public function buscarAjax(Request $request)
    {
        $termino = $request->get('term', $request->get('q', ''));

        if (strlen($termino) < 2) {
            return response()->json([]);
        }

        $estudiantes = Usuario::where('tipo_usuario', 'estudiante')
            ->where('estado', 'activo')
            ->where(function ($query) use ($termino) {
                $query->where('cedula', 'like', "%{$termino}%")
                    ->orWhere('apellidos', 'like', "%{$termino}%")
                    ->orWhere('nombres', 'like', "%{$termino}%");
            })
            ->limit(10)
            ->get();

        return response()->json($estudiantes->map(function ($est) {
            return [
                'id' => $est->id,
                'cedula' => $est->cedula,
                'nombreCompleto' => $est->nombres . ' ' . $est->apellidos,
                'nombres' => $est->nombres,
                'apellidos' => $est->apellidos,
            ];
        }));
    }

    /**
     * Promover estudiante a profesor (transición de rol)
     */
    public function promoverAProfesor(Request $request, $id)
    {
        $estudiante = Usuario::findOrFail($id);

        $request->validate([
            'correo'        => 'required|email|max:100|unique:profesores,correo',
            'celular'       => 'required|string|size:10',
            'especialidad'  => 'nullable|string|max:150',
            'departamento'  => 'nullable|string|max:100',
            'fecha_ingreso' => 'nullable|date',
            'horario'       => 'nullable|string|max:200',
        ], [
            'correo.unique' => 'Este correo ya está registrado como correo de otro profesor.',
            'celular.size'  => 'El celular debe tener exactamente 10 dígitos.',
        ]);

        // Verificar que no exista ya como profesor (seguridad extra)
        if (Profesor::where('cedula', $estudiante->cedula)->exists()) {
            return back()->with('error', 'Ya existe un profesor con la cédula ' . $estudiante->cedula . '.');
        }

        // Auto-generar contraseña temporal: ISTPET + últimos 4 dígitos de cédula
        $passwordTemp = 'ISTPET' . substr($estudiante->cedula, -4);

        DB::beginTransaction();
        try {
            // 1. Marcar al estudiante como graduado e inactivo
            $estudiante->update([
                'tipo_usuario' => 'graduado',
                'estado'       => 'inactivo',
            ]);

            // 2. Crear la cuenta de profesor con todos los datos
            $profesor = Profesor::create([
                'nombres'           => $estudiante->nombres,
                'apellidos'         => $estudiante->apellidos,
                'cedula'            => $estudiante->cedula,
                'correo'            => $request->correo,
                'celular'           => $request->celular,
                'especialidad'      => $request->especialidad,
                'departamento'      => $request->departamento,
                'fecha_ingreso'     => $request->fecha_ingreso,
                'horario'           => $request->horario,
                'foto_url'          => $estudiante->foto_url,
                'estado'            => 'activo',
                'password'          => Hash::make($passwordTemp),
                'password_temporal' => true,
            ]);

            DB::commit();

            // Generar enlace WhatsApp
            $telefono = $request->celular;
            $telefonoIntl = '593' . ltrim($telefono, '0');
            $nombre = $estudiante->nombres . ' ' . $estudiante->apellidos;
            $mensaje = "Hola {$nombre}, el administrador del Sistema ISTPET ha creado tu cuenta de docente.\n\nContraseña temporal: *{$passwordTemp}*\n\nIngresa al sistema con tu cédula y esta contraseña. Deberás cambiarla al iniciar sesión.\n" . config('app.url');
            $waLink = 'https://wa.me/' . $telefonoIntl . '?text=' . urlencode($mensaje);

            return redirect()
                ->route('admin.profesores.show', $profesor->id)
                ->with('success', "{$nombre} ha sido promovido a profesor. Contraseña temporal: {$passwordTemp}")
                ->with('whatsapp', [
                    'nombre'   => $nombre,
                    'telefono' => $telefono,
                    'password' => $passwordTemp,
                    'link'     => $waLink,
                ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al promover: ' . $e->getMessage());
        }
    }
}
