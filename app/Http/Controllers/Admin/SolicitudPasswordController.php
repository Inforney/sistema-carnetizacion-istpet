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

        // Actualizar contraseña del usuario + marcar como temporal
        $solicitud->usuario->update([
            'password'          => Hash::make($passwordTemporal),
            'password_temporal' => true,
        ]);

        // Marcar solicitud como atendida
        $solicitud->update([
            'estado' => 'atendida',
            'atendida_por_admin_id' => auth()->guard('administrador')->id(),
            'notas_admin' => 'Contraseña temporal generada: ' . $passwordTemporal,
        ]);

        // Generar enlace WhatsApp
        $telefono    = $solicitud->usuario->celular;
        $telefonoIntl = '593' . ltrim($telefono, '0');
        $nombre      = $solicitud->usuario->nombreCompleto;
        $mensaje     = "Hola {$nombre}, el administrador del Sistema ISTPET ha restablecido tu contraseña.\n\nNueva contraseña temporal: *{$passwordTemporal}*\n\nIngresa al sistema y cámbiala de inmediato.\n" . config('app.url');
        $waLink      = 'https://wa.me/' . $telefonoIntl . '?text=' . urlencode($mensaje);

        return back()
            ->with('success', "Solicitud atendida. Contraseña temporal: {$passwordTemporal}. El estudiante deberá cambiarla al iniciar sesión.")
            ->with('whatsapp', [
                'nombre'   => $nombre,
                'telefono' => $telefono,
                'password' => $passwordTemporal,
                'link'     => $waLink,
            ]);
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
