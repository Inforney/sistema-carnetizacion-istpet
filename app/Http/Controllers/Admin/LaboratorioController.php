<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laboratorio;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LaboratorioController extends Controller
{
    /**
     * Listar laboratorios
     */
    public function index()
    {
        $laboratorios = Laboratorio::withCount([
            'accesos' => function ($query) {
                $query->whereNull('hora_salida')
                    ->where('marcado_ausente', false)
                    ->whereDate('fecha_entrada', today());
            }
        ])->orderBy('nombre')->paginate(10);

        return view('admin.laboratorios.index', compact('laboratorios'));
    }

    /**
     * Mostrar formulario para crear laboratorio
     */
    public function create()
    {
        return view('admin.laboratorios.create');
    }

    /**
     * Guardar nuevo laboratorio
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:laboratorios,nombre',
            'tipo' => 'required|in:laboratorio,aula_interactiva',
            'ubicacion' => 'required|string|max:200',
            'capacidad' => 'required|integer|min:1|max:100',
            'descripcion' => 'nullable|string|max:500',
            'estado' => 'required|in:activo,inactivo,mantenimiento',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.unique' => 'Ya existe con este nombre',
            'tipo.required' => 'El tipo es obligatorio',
            'ubicacion.required' => 'La ubicación es obligatoria',
            'capacidad.required' => 'La capacidad es obligatoria',
            'capacidad.min' => 'La capacidad debe ser al menos 1',
            'capacidad.max' => 'La capacidad no puede ser mayor a 100',
        ]);

        // Generar código QR único para el laboratorio
        $codigoQR = 'LAB-' . strtoupper(\Illuminate\Support\Str::slug($validated['nombre'])) . '-' . \Illuminate\Support\Str::random(8);

        // Crear laboratorio con todos los datos
        Laboratorio::create([
            'nombre' => $validated['nombre'],
            'tipo' => $validated['tipo'],
            'ubicacion' => $validated['ubicacion'],
            'capacidad' => $validated['capacidad'],
            'descripcion' => $validated['descripcion'] ?? null,
            'estado' => $validated['estado'],
            'codigo_qr_lab' => $codigoQR,
        ]);

        return redirect()
            ->route('admin.laboratorios.index')
            ->with('success', '✅ ' . ($validated['tipo'] === 'laboratorio' ? 'Laboratorio' : 'Aula Interactiva') . ' creado exitosamente');
    }

    /**
     * Mostrar formulario para editar laboratorio
     */
    public function edit($id)
    {
        $laboratorio = Laboratorio::findOrFail($id);
        return view('admin.laboratorios.edit', compact('laboratorio'));
    }

    /**
     * Actualizar laboratorio
     */
    public function update(Request $request, $id)
    {
        $laboratorio = Laboratorio::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:laboratorios,nombre,' . $id,
            'tipo' => 'required|in:laboratorio,aula_interactiva',
            'ubicacion' => 'required|string|max:200',
            'capacidad' => 'required|integer|min:1|max:100',
            'descripcion' => 'nullable|string|max:500',
            'estado' => 'required|in:activo,inactivo,mantenimiento',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.unique' => 'Ya existe con este nombre',
            'tipo.required' => 'El tipo es obligatorio',
            'ubicacion.required' => 'La ubicación es obligatoria',
            'capacidad.required' => 'La capacidad es obligatoria',
            'capacidad.min' => 'La capacidad debe ser al menos 1',
            'capacidad.max' => 'La capacidad no puede ser mayor a 100',
        ]);

        $laboratorio->update($validated);

        return redirect()
            ->route('admin.laboratorios.index')
            ->with('success', '✅ ' . ($validated['tipo'] === 'laboratorio' ? 'Laboratorio' : 'Aula Interactiva') . ' actualizado exitosamente');
    }

    /**
     * Eliminar laboratorio
     */
    public function destroy($id)
    {
        $laboratorio = Laboratorio::findOrFail($id);

        // Verificar si tiene accesos registrados
        if ($laboratorio->accesos()->count() > 0) {
            return redirect()
                ->route('admin.laboratorios.index')
                ->with('error', '❌ No se puede eliminar el laboratorio porque tiene accesos registrados');
        }

        $laboratorio->delete();

        return redirect()
            ->route('admin.laboratorios.index')
            ->with('success', '✅ Laboratorio eliminado exitosamente');
    }

    /**
     * Generar PDF con QR individual
     */
    public function generarQR($id)
    {
        $laboratorio = Laboratorio::findOrFail($id);

        $pdf = Pdf::loadView('admin.laboratorios.qr-individual', compact('laboratorio'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('QR_' . str_replace(' ', '_', $laboratorio->nombre) . '.pdf');
    }

    /**
     * Descargar todos los QR en un solo PDF
     */
    public function descargarTodosQR()
    {
        $laboratorios = Laboratorio::where('estado', 'activo')->get();

        $pdf = Pdf::loadView('admin.laboratorios.qr-todos', compact('laboratorios'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('QR_Laboratorios_ISTPET_' . date('Y-m-d') . '.pdf');
    }
}
