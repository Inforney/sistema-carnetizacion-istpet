<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Profesor;
use App\Models\Administrador;

class LoginController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesar el login
     */
    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required',
            'password' => 'required',
        ]);

        $usuario = $request->usuario;
        $password = $request->password;

        // Intentar login como Administrador
        if (Auth::guard('administrador')->attempt(['usuario' => $usuario, 'password' => $password])) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        // Intentar login como Profesor
        if (Auth::guard('profesor')->attempt(['cedula' => $usuario, 'password' => $password])) {
            $request->session()->regenerate();
            return redirect()->route('profesor.dashboard');
        }

        // Intentar login como Estudiante (por cédula)
        $estudiante = Usuario::where('cedula', $usuario)
            ->where('tipo_usuario', 'estudiante')
            ->first();

        if ($estudiante) {
            // Verificar si está bloqueado ANTES de autenticar
            if ($estudiante->estaBloqueado()) {
                return back()->withInput()->with(
                    'error',
                    'Tu cuenta ha sido bloqueada. Por favor, contacta con el área de administración del instituto para más información. Puedes escribir a administracion@istpet.edu.ec o llamar al (02) 123-4567.'
                );
            }

            // Verificar contraseña
            if (Auth::attempt(['cedula' => $usuario, 'password' => $password, 'tipo_usuario' => 'estudiante'])) {
                $request->session()->regenerate();
                return redirect()->route('estudiante.dashboard');
            }
        }

        // Si llegamos aquí, las credenciales son incorrectas
        return back()->withInput()->with('error', 'Credenciales incorrectas. Verifica tu usuario y contraseña.');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        // Detectar qué guard está autenticado y cerrar sesión
        if (Auth::guard('administrador')->check()) {
            Auth::guard('administrador')->logout();
        } elseif (Auth::guard('profesor')->check()) {
            Auth::guard('profesor')->logout();
        } else {
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesión cerrada exitosamente.');
    }
}
