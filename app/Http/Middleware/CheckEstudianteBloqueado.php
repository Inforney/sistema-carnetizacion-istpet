<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckEstudianteBloqueado
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $usuario = Auth::user();

        // Verificar si el usuario está bloqueado
        if ($usuario && $usuario->estaBloqueado()) {
            // Cerrar sesión
            Auth::logout();

            // Limpiar sesión
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirigir al login con mensaje
            return redirect()->route('login')->with(
                'error',
                'Tu cuenta ha sido bloqueada. Por favor, contacta con el área de administración del instituto para más información.'
            );
        }

        return $next($request);
    }
}
