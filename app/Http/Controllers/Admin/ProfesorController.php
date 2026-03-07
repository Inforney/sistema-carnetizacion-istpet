<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profesor;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfesorController extends Controller
{
    /**
     * Mostrar lista de profesores con filtros de búsqueda
     */
    public function index(Request $request)
    {
        $query = Profesor::orderBy('created_at', 'desc');

        // Búsqueda por nombre o cédula
        if ($request->filled('buscar')) {
            $termino = $request->buscar;
            $query->where(function ($q) use ($termino) {
                $q->where('nombres', 'like', "%{$termino}%")
                    ->orWhere('apellidos', 'like', "%{$termino}%")
                    ->orWhere('cedula', 'like', "%{$termino}%")
                    ->orWhere('correo', 'like', "%{$termino}%");
            });
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por departamento
        if ($request->filled('departamento')) {
            $query->where('departamento', $request->departamento);
        }

        $profesores = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Profesor::count(),
            'activos' => Profesor::where('estado', 'activo')->count(),
            'inactivos' => Profesor::where('estado', 'inactivo')->count(),
        ];

        // Obtener lista de departamentos únicos para el filtro
        $departamentos = Profesor::whereNotNull('departamento')
            ->distinct()
            ->orderBy('departamento')
            ->pluck('departamento');

        return view('admin.profesores.index', compact('profesores', 'stats', 'departamentos'));
    }

    /**
     * Mostrar formulario para crear profesor
     */
    public function create()
    {
        return view('admin.profesores.create');
    }

    /**
     * Guardar nuevo profesor
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'cedula' => 'required|string|size:10|unique:profesores,cedula',
            'correo' => 'required|email|max:100|unique:profesores,correo',
            'celular' => 'required|string|size:10',
            'especialidad' => 'nullable|string|max:150',
            'fecha_ingreso' => 'nullable|date',
            'horario' => 'nullable|string',
            'departamento' => 'nullable|string|max:100',
            'password' => 'required|string|min:8|confirmed',
            'estado' => 'required|in:activo,inactivo',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        // Nunca permitir que la misma cédula exista como estudiante Y como profesor
        $estudianteExistente = Usuario::where('cedula', $validated['cedula'])->first();
        if ($estudianteExistente) {
            return back()->withInput()->withErrors([
                'cedula' => 'Esta cédula ya pertenece al estudiante "' . $estudianteExistente->nombres . ' ' . $estudianteExistente->apellidos . '". Para convertirlo en profesor, usa el botón "Promover a Profesor" en su perfil de estudiante.',
            ]);
        }

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $nombreFoto = 'profesor_' . $validated['cedula'] . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('fotos/profesores', $nombreFoto, 'public');
            $validated['foto_url'] = 'storage/fotos/profesores/' . $nombreFoto;
        }

        $validated['password'] = Hash::make($validated['password']);

        Profesor::create($validated);

        return redirect()->route('admin.profesores.index')
            ->with('success', 'Profesor registrado exitosamente.');
    }

    /**
     * Mostrar detalle de profesor
     */
    public function show($id)
    {
        $profesor = Profesor::findOrFail($id);
        $accesosValidados = $profesor->accesos()->count();

        return view('admin.profesores.show', compact('profesor', 'accesosValidados'));
    }

    /**
     * Mostrar formulario para editar profesor
     */
    public function edit($id)
    {
        $profesor = Profesor::findOrFail($id);
        return view('admin.profesores.edit', compact('profesor'));
    }

    /**
     * Actualizar profesor
     */
    public function update(Request $request, $id)
    {
        $profesor = Profesor::findOrFail($id);

        $validated = $request->validate([
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'correo' => [
                'required',
                'email',
                'max:100',
                Rule::unique('profesores')->ignore($profesor->id),
            ],
            'celular' => 'required|string|size:10',
            'especialidad' => 'nullable|string|max:150',
            'fecha_ingreso' => 'nullable|date',
            'horario' => 'nullable|string',
            'departamento' => 'nullable|string|max:100',
            'password' => 'nullable|string|min:8|confirmed',
            'estado' => 'required|in:activo,inactivo',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($profesor->foto_url) {
                $rutaAnterior = str_replace('storage/', '', $profesor->foto_url);
                Storage::disk('public')->delete($rutaAnterior);
            }

            $foto = $request->file('foto');
            $nombreFoto = 'profesor_' . $profesor->cedula . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('fotos/profesores', $nombreFoto, 'public');
            $validated['foto_url'] = 'storage/fotos/profesores/' . $nombreFoto;
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $profesor->update($validated);

        return redirect()->route('admin.profesores.index')
            ->with('success', 'Profesor actualizado exitosamente.');
    }

    /**
     * Activar/Desactivar profesor
     */
    public function toggleEstado($id)
    {
        $profesor = Profesor::findOrFail($id);

        $nuevoEstado = $profesor->estado === 'activo' ? 'inactivo' : 'activo';
        $profesor->update(['estado' => $nuevoEstado]);

        $mensaje = $nuevoEstado === 'inactivo'
            ? 'Profesor desactivado exitosamente.'
            : 'Profesor activado exitosamente.';

        return back()->with('success', $mensaje);
    }

    /**
     * Eliminar profesor (soft delete - solo cambia estado)
     */
    public function destroy($id)
    {
        $profesor = Profesor::findOrFail($id);
        $profesor->update(['estado' => 'inactivo']);

        return redirect()->route('admin.profesores.index')
            ->with('success', 'Profesor desactivado exitosamente.');
    }

    /**
     * Resetear contraseña del profesor y generar enlace WhatsApp
     */
    public function resetPassword($id)
    {
        $profesor = Profesor::findOrFail($id);

        // Formato: ISTPET + últimos 4 dígitos de cédula
        $nuevaPassword = 'ISTPET' . substr($profesor->cedula, -4);
        $profesor->update([
            'password'          => Hash::make($nuevaPassword),
            'password_temporal' => true,
        ]);

        // Generar enlace WhatsApp con el mensaje pre-escrito
        $telefono = $profesor->celular;
        $telefonoIntl = '593' . ltrim($telefono, '0'); // Ecuador: 593 + número sin 0
        $mensaje = "Hola {$profesor->nombreCompleto}, el administrador del Sistema ISTPET ha restablecido tu contraseña.\n\nNueva contraseña temporal: *{$nuevaPassword}*\n\nIngresa al sistema con tu cédula y esta contraseña.\n" . config('app.url');
        $waLink = 'https://wa.me/' . $telefonoIntl . '?text=' . urlencode($mensaje);

        return back()
            ->with('success', "Contraseña reseteada para {$profesor->nombreCompleto}. Contraseña temporal: {$nuevaPassword}")
            ->with('whatsapp', [
                'nombre' => $profesor->nombreCompleto,
                'telefono' => $telefono,
                'password' => $nuevaPassword,
                'link' => $waLink,
            ]);
    }
}
