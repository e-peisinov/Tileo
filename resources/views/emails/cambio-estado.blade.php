<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #faf6f0; color: #2c1a0e; margin: 0; padding: 0; }
        .contenedor { max-width: 560px; margin: 40px auto; background: #fff; border: 1px solid #d4b896; padding: 32px; }
        h1 { font-size: 22px; color: #386641; margin-bottom: 4px; }
        .numero { font-size: 13px; color: #8b5e3c; margin-bottom: 24px; }
        .estado-badge { display: inline-block; padding: 6px 16px; font-size: 14px; font-weight: bold; color: #fff; background: {{ $pedido->colorEstado() }}; border-radius: 20px; margin-bottom: 20px; }
        .aviso { background: #f0e9de; border-left: 3px solid #386641; padding: 12px 16px; font-size: 13px; color: #2c1a0e; margin-bottom: 24px; line-height: 1.5; }
        .aviso-rojo { background: #fef2f2; border-left: 3px solid #c0392b; }
        h3 { font-size: 13px; color: #8b5e3c; text-transform: uppercase; letter-spacing: .1em; margin: 20px 0 6px; }
        table { width: 100%; border-collapse: collapse; margin: 8px 0; }
        th { background: #f0e9de; text-align: left; padding: 8px 10px; font-size: 12px; }
        td { padding: 8px 10px; border-bottom: 1px solid #f0e9de; font-size: 13px; }
        .total-row td { font-weight: bold; font-size: 15px; color: #386641; border-top: 2px solid #d4b896; border-bottom: none; }
        .pie { margin-top: 28px; font-size: 12px; color: #8b5e3c; border-top: 1px solid #d4b896; padding-top: 16px; line-height: 1.8; }
    </style>
</head>
<body>
<div class="contenedor">

    <h1>🌿 Actualización de tu pedido</h1>
    <p class="numero">{{ $pedido->numero_pedido }} · {{ $pedido->created_at->format('d/m/Y H:i') }}</p>

    <p style="font-size:13px; color:#8b5e3c; margin-bottom:8px;">Nuevo estado:</p>
    <span class="estado-badge">{{ $pedido->etiquetaEstado() }}</span>

    @php
        $mensajes = [
            'confirmado'   => 'Confirmamos tu pedido y estamos organizando todo.',
            'preparando'   => 'Estamos preparando tu pedido con cuidado artesanal.',
            'enviado'      => 'Tu pedido está en camino. Te avisaremos cuando llegue.',
            'listo_retiro' => 'Tu pedido está listo para retirar en nuestro local en Mercedes, Buenos Aires.',
            'entregado'    => '¡Tu pedido fue entregado! Esperamos que lo disfrutes.',
            'rechazado'    => 'Lamentamos informarte que tu pedido no pudo procesarse. Contactanos para más información.',
            'cancelado'    => 'Tu pedido fue cancelado. Si tenés dudas, no dudes en contactarnos.',
        ];
    @endphp

    @if(isset($mensajes[$pedido->estado]))
        <div class="aviso {{ in_array($pedido->estado, ['rechazado', 'cancelado']) ? 'aviso-rojo' : '' }}">
            {{ $mensajes[$pedido->estado] }}
        </div>
    @endif

    @if($pedido->estado === 'enviado' && $pedido->metodo_entrega === 'envio' && $pedido->costo_envio !== null)
        <div class="aviso">
            <strong>Costo de envío confirmado:</strong> ${{ number_format($pedido->costo_envio, 2, ',', '.') }}<br>
            <strong>Total final:</strong> ${{ number_format($pedido->total, 2, ',', '.') }}
        </div>
    @endif

    <h3>Resumen del pedido</h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cant.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedido->items as $item)
            <tr>
                <td>{{ $item->nombre_producto }}</td>
                <td>{{ $item->cantidad }}</td>
                <td>${{ number_format($item->subtotal, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table>
        <tr>
            <td>Subtotal</td>
            <td style="text-align:right">${{ number_format($pedido->subtotal, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Envío</td>
            <td style="text-align:right">
                @if($pedido->metodo_entrega === 'retiro')
                    Sin costo
                @elseif(is_null($pedido->costo_envio))
                    A confirmar
                @else
                    ${{ number_format($pedido->costo_envio, 2, ',', '.') }}
                @endif
            </td>
        </tr>
        <tr class="total-row">
            <td>Total</td>
            <td style="text-align:right">${{ number_format($pedido->total, 2, ',', '.') }}</td>
        </tr>
    </table>

    <div class="pie">
        <strong>Tileo</strong> — Hierbas &amp; Especias Artesanales<br>
        Mercedes, Buenos Aires · Argentina<br>
        Ante cualquier consulta respondé este email o contactanos por WhatsApp.
    </div>

</div>
</body>
</html>
