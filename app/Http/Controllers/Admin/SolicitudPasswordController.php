<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SolicitudPassword;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SolicitudPasswordController extends Controller
{
    /**
     * Ver todas las solicitudes
     */
    public function index()
    {
        $solicitudes = SolicitudPassword::with('usuario')
            ->orderBy('estado', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $pendientes = SolicitudPassword::where('estado', 'pendiente')->count();

        return view('admin.solicitudes.index', compact('solicitudes', 'pendientes'));
    }

    /**
     * Atender solicitud (generar nueva contraseña y enviar por correo)
     */
    public function atender($id)
    {
        $solicitud = SolicitudPassword::with('usuario')->findOrFail($id);

        if ($solicitud->estado !== 'pendiente') {
            return back()->with('error', 'Esta solicitud ya fue atendida.');
        }

        // Generar contraseña temporal
        $passwordTemporal = 'ISTPET' . substr($solicitud->usuario->cedula, -4);

        // Actualizar contraseña del usuario
        $solicitud->usuario->update([
            'password' => Hash::make($passwordTemporal),
        ]);

        // Marcar solicitud como atendida
        $solicitud->update([
            'estado' => 'atendida',
            'atendida_por_admin_id' => auth()->guard('administrador')->id(),
            'notas_admin' => 'Contraseña temporal generada: ' . $passwordTemporal,
        ]);

        // Intentar enviar correo (si falla, continúa igual)
        try {
            Mail::raw(
                "Hola {$solicitud->usuario->nombreCompleto},\n\n" .
                    "Tu solicitud de cambio de contraseña ha sido atendida.\n\n" .
                    "Tu nueva contraseña temporal es: {$passwordTemporal}\n\n" .
                    "Por favor inicia sesión y cámbiala por una contraseña segura.\n\n" .
                    "Saludos,\nAdministración ISTPET",
                function ($message) use ($solicitud) {
                    $message->to($solicitud->correo)
                        ->subject('Recuperación de Contraseña - ISTPET');
                }
            );

            $mensajeCorreo = ' Se ha enviado un correo con la contraseña temporal.';
        } catch (\Exception $e) {
            $mensajeCorreo = '';
        }

        return back()->with(
            'success',
            "Solicitud atendida. Nueva contraseña temporal: {$passwordTemporal}.{$mensajeCorreo} Informa al estudiante."
        );
    }

    /**
     * Rechazar solicitud
     */
    public function rechazar(Request $request, $id)
    {
        $solicitud = SolicitudPassword::findOrFail($id);

        $solicitud->update([
            'estado' => 'rechazada',
            'atendida_por_admin_id' => auth()->guard('administrador')->id(),
            'notas_admin' => $request->motivo ?? 'Sin motivo especificado',
        ]);

        return back()->with('success', 'Solicitud rechazada.');
    }
}
