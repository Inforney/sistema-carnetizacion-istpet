<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequirePasswordChange
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Estudiante con contraseña temporal
        if (auth()->check() && auth()->user()->tipo_usuario === 'estudiante') {
            if (auth()->user()->password_temporal) {
                if (!$request->routeIs('estudiante.cambiar-password*')) {
                    return redirect()->route('estudiante.cambiar-password.form')
                        ->with('warning', 'Debes cambiar tu contraseña temporal antes de continuar.');
                }
            }
        }

        // Profesor con contraseña temporal
        if (auth('profesor')->check() && auth('profesor')->user()->password_temporal) {
            if (!$request->routeIs('profesor.cambiar-password*')) {
                return redirect()->route('profesor.cambiar-password.form')
                    ->with('warning', 'Debes cambiar tu contraseña temporal antes de continuar.');
            }
        }

        return $next($request);
    }
}
