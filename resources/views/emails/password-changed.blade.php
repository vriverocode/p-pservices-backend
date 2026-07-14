<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contraseña actualizada</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, Helvetica, sans-serif; }
        .container { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 8px; overflow: hidden; }
        .header { background: linear-gradient(90deg, #eb0606 0%, #095fc8 100%); padding: 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 22px; letter-spacing: 1px; }
        .body { padding: 30px; color: #333333; }
        .body p { font-size: 15px; line-height: 1.6; margin: 0 0 16px; }
        .alert { background: #fff3cd; color: #856404; padding: 14px; border-radius: 6px; font-size: 14px; margin: 20px 0; }
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
            <p>La contrase&ntilde;a de tu cuenta en P&amp;P Services ha sido cambiada exitosamente.</p>
            <div class="alert">
                <strong>&#9888;&#65039; No reconoces esta actividad?</strong><br>
                Si no realizaste este cambio, contacta a soporte de inmediato.
            </div>
            <p>Si fuiste t&uacute;, puedes ignorar este mensaje.</p>
            <hr style="margin: 24px 0; border: none; border-top: 1px solid #eeeeee;">
            <p style="font-size: 13px; color: #666666;">&mdash; P&amp;P Services</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} P&amp;P Services. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
