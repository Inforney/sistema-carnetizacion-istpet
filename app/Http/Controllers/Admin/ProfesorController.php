<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profesor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfesorController extends Controller
{
    /**
     * Mostrar lista de profesores
     */
    public function index()
    {
        $profesores = Profesor::orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => Profesor::count(),
            'activos' => Profesor::where('estado', 'activo')->count(),
            'inactivos' => Profesor::where('estado', 'inactivo')->count(),
        ];

        return view('admin.profesores.index', compact('profesores', 'stats'));
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

        // Procesar foto si existe
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $nombreFoto = 'profesor_' . $validated['cedula'] . '.' . $foto->getClientOriginalExtension();
            $rutaFoto = $foto->storeAs('public/fotos/profesores', $nombreFoto);
            $validated['foto_url'] = 'storage/fotos/profesores/' . $nombreFoto;
        }

        // Encriptar password
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

        // Contar accesos validados por este profesor
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

        // Procesar foto si existe
        if ($request->hasFile('foto')) {
            // Eliminar foto anterior si existe
            if ($profesor->foto_url && file_exists(public_path($profesor->foto_url))) {
                unlink(public_path($profesor->foto_url));
            }

            $foto = $request->file('foto');
            $nombreFoto = 'profesor_' . $profesor->cedula . '.' . $foto->getClientOriginalExtension();
            $rutaFoto = $foto->storeAs('public/fotos/profesores', $nombreFoto);
            $validated['foto_url'] = 'storage/fotos/profesores/' . $nombreFoto;
        }

        // Solo actualizar password si se proporcionó uno nuevo
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

        // Cambiar estado a inactivo en lugar de eliminar
        $profesor->update(['estado' => 'inactivo']);

        return redirect()->route('admin.profesores.index')
            ->with('success', 'Profesor desactivado exitosamente.');
    }

    /**
     * Resetear contraseña
     */
    public function resetPassword($id)
    {
        $profesor = Profesor::findOrFail($id);

        // Generar contraseña temporal (cédula)
        $nuevaPassword = $profesor->cedula;
        $profesor->update(['password' => Hash::make($nuevaPassword)]);

        return back()->with('success', 'Contraseña reseteada exitosamente. Nueva contraseña: ' . $nuevaPassword);
    }
}
