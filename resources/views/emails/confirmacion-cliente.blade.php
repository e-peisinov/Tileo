<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #faf6f0; color: #2c1a0e; margin: 0; padding: 0; }
        .contenedor { max-width: 560px; margin: 40px auto; background: #fff; border: 1px solid #d4b896; padding: 32px; }
        h1 { font-size: 22px; color: #386641; margin-bottom: 4px; }
        .numero { font-size: 13px; color: #8b5e3c; margin-bottom: 24px; }
        .aviso { background: #f0e9de; border-left: 3px solid #386641; padding: 12px 16px; font-size: 13px; color: #2c1a0e; margin-bottom: 24px; line-height: 1.5; }
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

    <h1>🌿 ¡Gracias por tu pedido!</h1>
    <p class="numero">{{ $pedido->numero_pedido }} · {{ $pedido->created_at->format('d/m/Y H:i') }}</p>

    <div class="aviso">
        Recibimos tu pedido y en breve nos comunicaremos con vos para coordinar los detalles.
        @if($pedido->metodo_entrega === 'envio')
            Te informaremos el costo de envío al confirmar el pedido.
        @endif
    </div>

    <h3>Tus datos</h3>
    <p style="margin:4px 0; font-size:13px;">{{ $pedido->nombre_cliente }}</p>
    <p style="margin:4px 0; font-size:13px;">{{ $pedido->email_cliente }}</p>
    <p style="margin:4px 0; font-size:13px;">{{ $pedido->telefono_cliente }}</p>

    <h3>Entrega y pago</h3>
    <p style="margin:4px 0; font-size:13px;">
        <strong>Entrega:</strong>
        {{ $pedido->metodo_entrega === 'envio' ? 'Envío a domicilio — ' . $pedido->direccion_envio : 'Retiro en local (Mercedes, Buenos Aires)' }}
    </p>
    <p style="margin:4px 0; font-size:13px;">
        <strong>Pago:</strong> {{ $pedido->metodo_pago === 'transferencia' ? 'Transferencia bancaria' : 'Efectivo' }}
    </p>

    <h3>Productos</h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cant.</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedido->items as $item)
            <tr>
                <td>{{ $item->nombre_producto }}</td>
                <td>{{ $item->cantidad }}</td>
                <td>${{ number_format($item->precio_unitario, 2, ',', '.') }}</td>
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
                @else
                    A confirmar
                @endif
            </td>
        </tr>
        <tr class="total-row">
            <td>Total estimado</td>
            <td style="text-align:right">${{ number_format($pedido->subtotal, 2, ',', '.') }}</td>
        </tr>
    </table>

    @if($pedido->notas_cliente)
    <h3>Tus notas</h3>
    <p style="font-size:13px; color:#2c1a0e/80;">{{ $pedido->notas_cliente }}</p>
    @endif

    <div class="pie">
        <strong>Tileo</strong> — Hierbas &amp; Especias Artesanales<br>
        Mercedes, Buenos Aires · Argentina<br>
        Ante cualquier consulta respondé este email o contactanos por WhatsApp.
    </div>

</div>
</body>
</html>
