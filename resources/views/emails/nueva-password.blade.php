<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña - ISTPET</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: #222c57; padding: 30px 40px; text-align: center; }
        .header h1 { color: #c4a857; font-size: 24px; margin: 0; }
        .header p { color: rgba(255,255,255,0.8); margin: 5px 0 0; font-size: 13px; }
        .body { padding: 30px 40px; }
        .body p { color: #444; line-height: 1.6; }
        .password-box { background: #f0f4ff; border: 2px dashed #222c57; border-radius: 8px; padding: 20px; text-align: center; margin: 20px 0; }
        .password-box .label { color: #666; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .password-box .password { font-size: 28px; font-weight: bold; color: #222c57; letter-spacing: 4px; font-family: monospace; }
        .warning { background: #fff8e1; border-left: 4px solid #c4a857; padding: 12px 16px; border-radius: 4px; margin: 20px 0; }
        .warning p { margin: 0; color: #7a6000; font-size: 13px; }
        .footer { background: #f4f6f9; padding: 20px 40px; text-align: center; border-top: 1px solid #eee; }
        .footer p { color: #888; font-size: 12px; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ISTPET</h1>
            <p>Sistema de Carnetización Estudiantil</p>
        </div>
        <div class="body">
            <p>Estimado/a <strong>{{ $nombreCompleto }}</strong>,</p>
            <p>El administrador del sistema ha restablecido tu contraseña. A continuación encontrarás tu nueva contraseña temporal:</p>

            <div class="password-box">
                <div class="label">Tu nueva contraseña temporal es:</div>
                <div class="password">{{ $nuevaPassword }}</div>
            </div>

            <div class="warning">
                <p><strong>⚠️ Importante:</strong> Esta es una contraseña temporal. Al iniciar sesión, el sistema te pedirá que la cambies por una contraseña personal y segura.</p>
            </div>

            <p>Puedes ingresar al sistema en: <strong>{{ config('app.url') }}</strong></p>
            <p>Si tienes alguna duda, contacta al área administrativa del instituto.</p>
        </div>
        <div class="footer">
            <p>Este es un correo automático del Sistema de Carnetización ISTPET. Por favor no respondas a este mensaje.</p>
            <p style="margin-top: 8px;">© {{ date('Y') }} Instituto Superior Tecnológico ISTPET</p>
        </div>
    </div>
</body>
</html>
