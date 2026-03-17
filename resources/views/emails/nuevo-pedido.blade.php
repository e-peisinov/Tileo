<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #faf6f0; color: #2c1a0e; margin: 0; padding: 0; }
        .contenedor { max-width: 560px; margin: 40px auto; background: #fff; border: 1px solid #d4b896; padding: 32px; }
        h1 { font-size: 22px; color: #386641; margin-bottom: 4px; }
        .numero { font-size: 13px; color: #8b5e3c; margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        th { background: #f0e9de; text-align: left; padding: 8px 10px; font-size: 12px; }
        td { padding: 8px 10px; border-bottom: 1px solid #f0e9de; font-size: 13px; }
        .total { font-size: 15px; font-weight: bold; color: #386641; }
        .pie { margin-top: 24px; font-size: 12px; color: #8b5e3c; border-top: 1px solid #d4b896; padding-top: 16px; }
        .badge { display: inline-block; background: #386641; color: #fff; padding: 3px 10px; font-size: 12px; border-radius: 20px; }
    </style>
</head>
<body>
<div class="contenedor">
    <h1>🌿 Nuevo pedido recibido</h1>
    <p class="numero">{{ $pedido->numero_pedido }} · {{ $pedido->created_at->format('d/m/Y H:i') }}</p>

    <span class="badge">{{ $pedido->etiquetaEstado() }}</span>

    <h3 style="margin-top:20px; font-size:14px; color:#8b5e3c; text-transform:uppercase; letter-spacing:.1em;">Cliente</h3>
    <p style="margin:4px 0; font-size:13px;">{{ $pedido->nombre_cliente }}</p>
    <p style="margin:4px 0; font-size:13px;">{{ $pedido->email_cliente }}</p>
    <p style="margin:4px 0; font-size:13px;">{{ $pedido->telefono_cliente }}</p>

    <h3 style="margin-top:20px; font-size:14px; color:#8b5e3c; text-transform:uppercase; letter-spacing:.1em;">Entrega</h3>
    <p style="margin:4px 0; font-size:13px;">
        {{ $pedido->metodo_entrega === 'envio' ? 'Envío a domicilio' : 'Retiro en local' }}
        @if($pedido->metodo_entrega === 'envio')
            — {{ $pedido->direccion_envio }}
        @endif
    </p>
    <p style="margin:4px 0; font-size:13px;">Pago: {{ $pedido->metodo_pago === 'transferencia' ? 'Transferencia bancaria' : 'Efectivo' }}</p>

    <h3 style="margin-top:20px; font-size:14px; color:#8b5e3c; text-transform:uppercase; letter-spacing:.1em;">Productos</h3>
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
        <tr><td>Subtotal</td><td style="text-align:right">${{ number_format($pedido->subtotal, 2, ',', '.') }}</td></tr>
        <tr>
            <td>Envío</td>
            <td style="text-align:right">
                {{ is_null($pedido->costo_envio) ? 'A confirmar' : '$' . number_format($pedido->costo_envio, 2, ',', '.') }}
            </td>
        </tr>
        <tr class="total">
            <td><strong>Total</strong></td>
            <td style="text-align:right"><strong>${{ number_format($pedido->total, 2, ',', '.') }}</strong></td>
        </tr>
    </table>

    @if($pedido->notas_cliente)
    <h3 style="margin-top:20px; font-size:14px; color:#8b5e3c; text-transform:uppercase; letter-spacing:.1em;">Notas del cliente</h3>
    <p style="font-size:13px;">{{ $pedido->notas_cliente }}</p>
    @endif

    <div class="pie">Tileo — Hierbas &amp; Especias Artesanales · Mercedes, Buenos Aires</div>
</div>
</body>
</html>
