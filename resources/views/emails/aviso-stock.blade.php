<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #faf6f0; margin: 0; padding: 40px 20px; }
        .container { max-width: 560px; margin: 0 auto; background: white; border: 1px solid #d4b896; }
        .header { background-color: #386641; padding: 32px; text-align: center; }
        .header h1 { color: #faf6f0; font-size: 22px; margin: 0; font-weight: normal; }
        .header p { color: rgba(250,246,240,0.7); font-size: 11px; text-transform: uppercase; letter-spacing: 0.25em; margin: 6px 0 0; }
        .body { padding: 32px; }
        .body p { color: #2c1a0e; font-size: 14px; line-height: 1.7; margin: 0 0 16px; }
        .producto-card { border: 1px solid #d4b896; padding: 20px; margin: 20px 0; text-align: center; }
        .producto-card h2 { color: #2c1a0e; font-size: 20px; margin: 0 0 8px; }
        .producto-card .precio { color: #386641; font-size: 18px; font-weight: bold; }
        .btn { display: inline-block; background-color: #386641; color: #faf6f0; padding: 12px 28px; text-decoration: none; font-size: 13px; letter-spacing: 0.1em; margin-top: 8px; }
        .footer { background-color: #2c1a0e; padding: 20px 32px; text-align: center; }
        .footer p { color: rgba(212,184,150,0.5); font-size: 11px; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <p>Tileo · Hierbas & Especias</p>
            <h1>¡Ya hay stock disponible!</h1>
        </div>
        <div class="body">
            @if($aviso->nombre)
                <p>Hola <strong>{{ $aviso->nombre }}</strong>,</p>
            @else
                <p>Hola,</p>
            @endif
            <p>Tenemos buenas noticias. El producto que querías volver a tener está disponible nuevamente:</p>
            <div class="producto-card">
                <h2>{{ $producto->nombre }}</h2>
                @if($producto->precio > 0)
                    <p class="precio">${{ number_format($producto->precio, 2, ',', '.') }}</p>
                @endif
                <p style="font-size: 12px; color: #8b5e3c; margin: 8px 0;">{{ ucfirst($producto->unidad) }}</p>
                <a href="{{ route('detalle-producto', $producto) }}" class="btn">Ver producto</a>
            </div>
            <p style="font-size: 12px; color: rgba(139,94,60,0.6);">¡No esperes demasiado, el stock puede agotarse rápidamente!</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Tileo — Mercedes, Buenos Aires</p>
        </div>
    </div>
</body>
</html>
