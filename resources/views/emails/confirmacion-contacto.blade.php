<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #faf6f0; margin: 0; padding: 40px 20px; }
        .container { max-width: 560px; margin: 0 auto; background: white; border: 1px solid #d4b896; }
        .header { background-color: #386641; padding: 32px; text-align: center; }
        .header h1 { color: #faf6f0; font-size: 24px; margin: 0; font-weight: normal; letter-spacing: 0.05em; }
        .header p { color: rgba(250,246,240,0.7); font-size: 11px; text-transform: uppercase; letter-spacing: 0.25em; margin: 6px 0 0; }
        .body { padding: 32px; }
        .body p { color: #2c1a0e; font-size: 14px; line-height: 1.7; margin: 0 0 16px; }
        .highlight { background-color: #f0e9de; border-left: 3px solid #386641; padding: 16px 20px; margin: 24px 0; }
        .highlight p { margin: 0; color: #2c1a0e; font-size: 14px; }
        .footer { background-color: #2c1a0e; padding: 20px 32px; text-align: center; }
        .footer p { color: rgba(212,184,150,0.5); font-size: 11px; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <p>Tileo · Hierbas & Especias</p>
            <h1>¡Recibimos tu mensaje!</h1>
        </div>
        <div class="body">
            <p>Hola <strong>{{ $nombreCliente }}</strong>,</p>
            <p>Gracias por ponerte en contacto con nosotros. Recibimos tu mensaje y te responderemos a la brevedad.</p>
            <div class="highlight">
                <p><strong>Asunto:</strong> {{ $asunto ?: 'Consulta general' }}</p>
            </div>
            <p>Nuestro equipo revisará tu mensaje y te contactará lo antes posible.</p>
            <p>Si tu consulta es urgente, también podés escribirnos por WhatsApp.</p>
            <p style="margin-top: 24px;">Gracias,<br><strong style="color: #386641;">Equipo Tileo</strong></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Tileo — Mercedes, Buenos Aires</p>
        </div>
    </div>
</body>
</html>
