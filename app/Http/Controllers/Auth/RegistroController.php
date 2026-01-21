<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Carnet;
use App\Models\SolicitudPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class RegistroController extends Controller
{
    /**
     * Mostrar formulario de registro
     */
    public function showRegistroForm()
    {
        return view('auth.registro');
    }

    /**
     * Procesar registro de nuevo estudiante
     */
    public function registro(Request $request)
    {
        $validated = $request->validate([
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'tipo_documento' => 'required|in:cedula,pasaporte',
            'documento' => 'required|string|max:20|unique:usuarios,cedula',
            'correo_institucional' => 'required|email|unique:usuarios,correo_institucional',
            'celular' => 'required|string|digits:10',
            'ciclo_nivel' => 'required|in:PRIMER NIVEL,SEGUNDO NIVEL,TERCER NIVEL,CUARTO NIVEL',
            'carrera' => 'required|string|max:200',
            'nacionalidad' => 'required|string|max:50',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/'
            ],
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'foto_base64' => 'nullable|string',
        ], [
            'foto.image' => 'El archivo debe ser una imagen',
            'foto.mimes' => 'Solo se permiten imágenes JPG, JPEG o PNG',
            'foto.max' => 'La foto no debe pesar más de 2MB',
            'celular.digits' => 'El celular debe tener 10 dígitos',
            'password.regex' => 'La contraseña debe contener mayúsculas, minúsculas, números y caracteres especiales (@$!%*?&#)',
        ]);

        DB::beginTransaction();

        try {
            // Subir foto (archivo o base64)
            $fotoPath = null;

            if ($request->hasFile('foto')) {
                // Foto subida como archivo
                $foto = $request->file('foto');
                $nombreFoto = 'estudiante_' . time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                $fotoPath = $foto->storeAs('fotos', $nombreFoto, 'public');
            } elseif ($request->filled('foto_base64')) {
                // Foto tomada con cámara (base64)
                $image = $request->foto_base64;
                $image = str_replace('data:image/jpeg;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = 'estudiante_' . time() . '_' . uniqid() . '.jpg';

                \Storage::disk('public')->put('fotos/' . $imageName, base64_decode($image));
                $fotoPath = 'fotos/' . $imageName;
            }

            // Crear usuario
            $usuario = Usuario::create([
                'nombres' => $validated['nombres'],
                'apellidos' => $validated['apellidos'],
                'cedula' => $validated['documento'],
                'tipo_documento' => $validated['tipo_documento'],
                'correo_institucional' => $validated['correo_institucional'],
                'celular' => $validated['celular'],
                'ciclo_nivel' => $validated['ciclo_nivel'],
                'carrera' => $validated['carrera'],
                'nacionalidad' => $validated['nacionalidad'],
                'foto_url' => $fotoPath,
                'tipo_usuario' => 'estudiante',
                'estado' => 'activo',
                'password' => Hash::make($validated['password']),
                'fecha_registro' => Carbon::now(),
            ]);

            // Generar carnet automáticamente
            $codigoQr = $this->generarCodigoQrUnico($usuario->cedula);

            Carnet::create([
                'usuario_id' => $usuario->id,
                'codigo_qr' => $codigoQr,
                'fecha_emision' => Carbon::now(),
                'fecha_vencimiento' => Carbon::now()->addYears(4), // 4 años (todo el período académico)
                'estado' => 'activo',
            ]);

            DB::commit();

            return redirect()->route('login')->with(
                'success',
                '¡Registro exitoso! Tu carnet ha sido generado. Ya puedes iniciar sesión con tu documento y contraseña.'
            );
        } catch (\Exception $e) {
            DB::rollBack();

            // Eliminar foto si se subió
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }

            return back()->withInput()->with('error', 'Error al registrar. Por favor intenta de nuevo.');
        }
    }

    /**
     * Generar código QR único y permanente
     */
    private function generarCodigoQrUnico($documento)
    {
        // Formato: ISTPET-2026-DOCUMENTO
        // Este código será único y permanente para el estudiante
        return 'ISTPET-2026-' . strtoupper($documento);
    }

    /**
     * Mostrar formulario de solicitud de recuperación
     */
    public function showRecuperarForm()
    {
        return view('auth.recuperar-password');
    }

    /**
     * Procesar solicitud de recuperación de contraseña
     */
    public function enviarRecuperacion(Request $request)
    {
        $request->validate([
            'documento' => 'required|string',
            'correo' => 'required|email',
        ]);

        // Buscar usuario
        $usuario = Usuario::where('cedula', $request->documento)
            ->where('correo_institucional', $request->correo)
            ->where('tipo_usuario', 'estudiante')
            ->first();

        if (!$usuario) {
            return back()->with(
                'error',
                'No se encontró ningún estudiante con ese documento y correo. Verifica tus datos.'
            );
        }

        // Crear solicitud de cambio de contraseña
        SolicitudPassword::create([
            'usuario_id' => $usuario->id,
            'tipo_usuario' => 'estudiante',
            'documento' => $request->documento,
            'correo' => $request->correo,
            'estado' => 'pendiente',
        ]);

        return back()->with(
            'success',
            'Solicitud enviada correctamente. El administrador te contactará para proporcionarte una nueva contraseña temporal. Por favor revisa tu correo institucional.'
        );
    }
}
