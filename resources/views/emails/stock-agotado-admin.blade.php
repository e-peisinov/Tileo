<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #faf6f0; margin: 0; padding: 40px 20px; }
        .container { max-width: 560px; margin: 0 auto; background: white; border: 1px solid #d4b896; }
        .header { background-color: #8b5e3c; padding: 28px; text-align: center; }
        .header h1 { color: #faf6f0; font-size: 20px; margin: 0; font-weight: normal; }
        .body { padding: 32px; }
        .body p { color: #2c1a0e; font-size: 14px; line-height: 1.7; margin: 0 0 16px; }
        .alerta { background-color: #fff3cd; border: 1px solid #ffc107; padding: 16px 20px; margin: 20px 0; }
        .alerta p { margin: 0; color: #856404; font-size: 14px; }
        .btn { display: inline-block; background-color: #386641; color: #faf6f0; padding: 12px 28px; text-decoration: none; font-size: 13px; letter-spacing: 0.1em; }
        .footer { background-color: #2c1a0e; padding: 20px; text-align: center; }
        .footer p { color: rgba(212,184,150,0.5); font-size: 11px; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ Stock agotado — Tileo Admin</h1>
        </div>
        <div class="body">
            <div class="alerta">
                <p><strong>{{ $producto->nombre }}</strong> se quedó sin stock.</p>
            </div>
            <p><strong>Producto:</strong> {{ $producto->nombre }}</p>
            <p><strong>Categoría:</strong> {{ $producto->categorias->pluck('nombre')->join(', ') ?: 'Sin categoría' }}</p>
            <p><strong>Stock actual:</strong> 0 unidades</p>
            <p>Acordate de reponer el stock desde el panel de administración.</p>
            <a href="{{ route('admin.productos') }}" class="btn">Ir a productos</a>
        </div>
        <div class="footer">
            <p>Panel Admin · Tileo</p>
        </div>
    </div>
</body>
</html>
