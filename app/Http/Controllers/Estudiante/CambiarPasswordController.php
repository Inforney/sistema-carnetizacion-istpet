<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CambiarPasswordController extends Controller
{
    /**
     * Mostrar formulario de cambio de contraseña
     */
    public function showForm()
    {
        return view('estudiante.cambiar-password');
    }

    /**
     * Procesar cambio de contraseña
     */
    public function cambiar(Request $request)
    {
        $request->validate([
            'password_actual' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'password_actual.required' => 'Debes ingresar tu contraseña actual',
            'password.required' => 'Debes ingresar una nueva contraseña',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        $user = Auth::user();

        // Verificar contraseña actual
        if (!Hash::check($request->password_actual, $user->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual es incorrecta']);
        }

        // Actualizar contraseña
        $user->update([
            'password' => Hash::make($request->password),
            'password_temporal' => false,
        ]);

        return redirect()->route('estudiante.dashboard')
            ->with('success', '¡Contraseña cambiada exitosamente!');
    }
}
