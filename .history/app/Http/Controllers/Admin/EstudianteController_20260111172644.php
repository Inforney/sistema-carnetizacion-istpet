<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Carnet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EstudianteController extends Controller
{
    /**
     * Listar todos los estudiantes
     */
    public function index(Request $request)
    {
        $query = Usuario::where('tipo_usuario', 'estudiante')->with('carnet');

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
            'password' => 'required|string|min:8',
            'generar_carnet' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            $usuario = Usuario::create([
                'nombres' => $validated['nombres'],
                'apellidos' => $validated['apellidos'],
                'cedula' => $validated['cedula'],
                'tipo_documento' => $validated['tipo_documento'],
                'correo_institucional' => $validated['correo_institucional'],
                'celular' => $validated['celular'],
                'ciclo_nivel' => $validated['ciclo_nivel'],
                'nacionalidad' => $validated['nacionalidad'],
                'tipo_usuario' => 'estudiante',
                'estado' => 'activo',
                'password' => Hash::make($validated['password']),
                'fecha_registro' => Carbon::now(),
            ]);

            // Generar carnet si se solicitó
            if ($request->has('generar_carnet') && $request->generar_carnet) {
                $codigoQr = 'ISTPET-2026-' . strtoupper($usuario->cedula);

                Carnet::create([
                    'usuario_id' => $usuario->id,
                    'codigo_qr' => $codigoQr,
                    'fecha_emision' => Carbon::now(),
                    'fecha_vencimiento' => Carbon::now()->addYears(4),
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
            return back()->withInput()->with('error', 'Error al crear estudiante.');
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

        return view('admin.estudiantes.show', compact('estudiante', 'stats'));
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
        ]);

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
     * Resetear contraseña de estudiante
     */
    public function resetPassword($id)
    {
        $estudiante = Usuario::findOrFail($id);

        // Generar contraseña temporal
        $passwordTemp = 'ISTPET' . substr($estudiante->cedula, -4);

        $estudiante->update([
            'password' => Hash::make($passwordTemp),
            'password_temporal' => true,  // ← AGREGAR ESTA LÍNEA
        ]);

        return redirect()->back()->with(
            'success',
            'Contraseña reseteada. Nueva contraseña temporal: ' . $passwordTemp
        );
    }

    /**
     * Búsqueda AJAX para autocompletado
     */
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
}
