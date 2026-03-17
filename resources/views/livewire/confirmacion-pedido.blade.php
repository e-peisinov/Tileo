<div class="min-h-screen bg-[#faf6f0] py-16 px-4">
    <div class="max-w-2xl mx-auto">

        {{-- Encabezado --}}
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-[#386641]/10 rounded-full flex items-center justify-center mx-auto mb-5">
                <i class="fa-solid fa-check text-3xl text-[#386641]"></i>
            </div>
            <h1 class="text-4xl text-[#2c1a0e] mb-2" style="font-family: 'DM Serif Display', serif;">¡Pedido recibido!</h1>
            <p class="text-[#8b5e3c]/80 text-sm">{{ $pedido->numero_pedido }} · {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
        </div>

        {{-- Resumen del pedido --}}
        <div class="bg-white border border-[#d4b896]/30 p-6 mb-5">
            <h2 class="text-base font-medium text-[#2c1a0e] mb-4" style="font-family: 'DM Serif Display', serif;">Detalle del pedido</h2>

            <div class="space-y-2 mb-5">
                @foreach($pedido->items as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-[#2c1a0e]">{{ $item->nombre_producto }} <span class="text-[#8b5e3c]/70">×{{ $item->cantidad }}</span></span>
                        <span class="font-medium text-[#2c1a0e]">${{ number_format($item->subtotal, 2, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            <div class="border-t border-[#d4b896]/30 pt-3 space-y-2">
                <div class="flex justify-between text-sm text-[#2c1a0e]/70">
                    <span>Subtotal</span>
                    <span>${{ number_format($pedido->subtotal, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm text-[#2c1a0e]/70">
                    <span>Envío</span>
                    <span>{{ $pedido->metodo_entrega === 'retiro' ? 'Sin costo' : 'A confirmar' }}</span>
                </div>
            </div>
        </div>

        {{-- Info de entrega --}}
        <div class="bg-white border border-[#d4b896]/30 p-6 mb-5">
            <h2 class="text-base font-medium text-[#2c1a0e] mb-4" style="font-family: 'DM Serif Display', serif;">Información de contacto</h2>
            <div class="space-y-1.5 text-sm text-[#2c1a0e]/80">
                <p><span class="text-[#8b5e3c]">Nombre:</span> {{ $pedido->nombre_cliente }}</p>
                <p><span class="text-[#8b5e3c]">Email:</span> {{ $pedido->email_cliente }}</p>
                <p><span class="text-[#8b5e3c]">Teléfono:</span> {{ $pedido->telefono_cliente }}</p>
                <p><span class="text-[#8b5e3c]">Entrega:</span>
                    {{ $pedido->metodo_entrega === 'envio' ? 'Envío a ' . $pedido->direccion_envio : 'Retiro en local' }}
                </p>
                <p><span class="text-[#8b5e3c]">Pago:</span>
                    {{ $pedido->metodo_pago === 'transferencia' ? 'Transferencia bancaria' : 'Efectivo' }}
                </p>
            </div>
        </div>

        {{-- Aviso y botón WhatsApp --}}
        <div class="bg-[#386641]/8 border border-[#386641]/20 p-5 mb-8 text-center">
            <p class="text-sm text-[#2c1a0e]/80 mb-4">
                Recibirás una confirmación por email. Para agilizar tu pedido, podés enviarnos los detalles por WhatsApp.
            </p>
            <a href="https://wa.me/{{ preg_replace('/\D/', '', config('tileo.whatsapp', '')) }}?text={{ $mensajeWa }}"
               target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 bg-[#25D366] text-white px-7 py-3 text-[13px] font-medium tracking-wide hover:bg-[#1ebe5a] transition-colors duration-300">
                <i class="fa-brands fa-whatsapp text-lg"></i>
                Enviar pedido por WhatsApp
            </a>
        </div>

        <div class="text-center">
            <a href="{{ route('catalogo') }}" class="text-sm text-[#386641] hover:underline">← Seguir comprando</a>
        </div>

    </div>
</div>
