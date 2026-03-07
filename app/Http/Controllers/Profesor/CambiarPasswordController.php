<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CambiarPasswordController extends Controller
{
    /**
     * Mostrar formulario de cambio de contraseña
     */
    public function showForm()
    {
        $profesor = auth('profesor')->user();

        // Solo se puede acceder a esta página una vez (mientras sea temporal)
        // o si el profesor tiene password_temporal activo
        if (!$profesor->password_temporal) {
            return redirect()->route('profesor.dashboard')
                ->with('info', 'Tu contraseña ya fue actualizada.');
        }

        return view('profesor.cambiar-password');
    }

    /**
     * Procesar el cambio de contraseña
     */
    public function cambiar(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/',
            ],
        ], [
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.regex'    => 'La contraseña debe contener mayúsculas, minúsculas, números y caracteres especiales (@$!%*?&#).',
        ]);

        $profesor = auth('profesor')->user();

        $profesor->update([
            'password'          => Hash::make($request->password),
            'password_temporal' => false,
        ]);

        return redirect()->route('profesor.dashboard')
            ->with('success', '¡Contraseña actualizada exitosamente! Bienvenido al sistema.');
    }
}
