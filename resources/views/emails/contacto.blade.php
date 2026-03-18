<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #faf6f0; color: #2c1a0e; margin: 0; padding: 0; }
        .contenedor { max-width: 560px; margin: 40px auto; background: #fff; border: 1px solid #d4b896; padding: 32px; }
        h1 { font-size: 22px; color: #386641; margin-bottom: 4px; }
        .subtitulo { font-size: 13px; color: #8b5e3c; margin-bottom: 24px; }
        .campo { margin-bottom: 16px; }
        .label { font-size: 11px; text-transform: uppercase; letter-spacing: .1em; color: #8b5e3c; margin-bottom: 4px; }
        .valor { font-size: 13px; }
        .mensaje-box { background: #f0e9de; padding: 14px 16px; font-size: 13px; line-height: 1.6; white-space: pre-wrap; }
        .pie { margin-top: 24px; font-size: 12px; color: #8b5e3c; border-top: 1px solid #d4b896; padding-top: 16px; }
    </style>
</head>
<body>
<div class="contenedor">
    <h1>📩 Nuevo mensaje de contacto</h1>
    <p class="subtitulo">Recibido desde el formulario de contacto de tileo.com</p>

    <div class="campo">
        <p class="label">Nombre</p>
        <p class="valor">{{ $nombre }}</p>
    </div>

    @if($telefono)
    <div class="campo">
        <p class="label">Teléfono</p>
        <p class="valor">{{ $telefono }}</p>
    </div>
    @endif

    @if($asunto)
    <div class="campo">
        <p class="label">Asunto</p>
        <p class="valor">
            @php
                $asuntos = [
                    'consulta' => 'Consulta sobre un producto',
                    'pedido'   => 'Hacer un pedido',
                    'ferias'   => 'Próximas ferias y eventos',
                    'otro'     => 'Otro',
                ];
            @endphp
            {{ $asuntos[$asunto] ?? $asunto }}
        </p>
    </div>
    @endif

    <div class="campo">
        <p class="label">Mensaje</p>
        <div class="mensaje-box">{{ $mensaje }}</div>
    </div>

    <div class="pie">Tileo — Hierbas &amp; Especias Artesanales · Mercedes, Buenos Aires</div>
</div>
</body>
</html>
