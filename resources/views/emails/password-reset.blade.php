<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablece tu contraseña</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, Helvetica, sans-serif; }
        .container { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 8px; overflow: hidden; }
        .header { background: linear-gradient(90deg, #eb0606 0%, #095fc8 100%); padding: 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 22px; letter-spacing: 1px; }
        .body { padding: 30px; color: #333333; }
        .body p { font-size: 15px; line-height: 1.6; margin: 0 0 16px; }
        .btn { display: inline-block; background: #095fc8; color: #ffffff !important; text-decoration: none; padding: 14px 32px; border-radius: 6px; font-size: 16px; font-weight: bold; margin: 20px 0; }
        .footer { padding: 20px 30px; text-align: center; color: #999999; font-size: 12px; border-top: 1px solid #eeeeee; }
        .footer p { margin: 4px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>P&amp;P Services</h1>
        </div>
        <div class="body">
            <p>Hola <strong>{{ $user->name }}</strong>,</p>
            <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta. Haz clic en el siguiente botón para crear una nueva contraseña:</p>
            <p style="text-align: center;">
                <a href="{{ $resetUrl }}" class="btn">Restablecer contraseña</a>
            </p>
            <p>Este enlace expirar&aacute; en <strong>60 minutos</strong>.</p>
            <p>Si no solicitaste este cambio, ignora este correo y tu contrase&ntilde;a permanecer&aacute; igual.</p>
            <hr style="margin: 24px 0; border: none; border-top: 1px solid #eeeeee;">
            <p style="font-size: 13px; color: #666666;">— P&amp;P Services</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} P&amp;P Services. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
