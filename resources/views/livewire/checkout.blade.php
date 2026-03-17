<div class="min-h-screen bg-[#faf6f0] py-12 px-4">
    <div class="max-w-5xl mx-auto">

        <div class="text-center mb-10">
            <p class="text-[#8b5e3c]/70 tracking-[0.3em] uppercase text-[11px] font-medium mb-2">Último paso</p>
            <h1 class="text-4xl text-[#2c1a0e]" style="font-family: 'DM Serif Display', serif;">Finalizar pedido</h1>
        </div>

        @if(empty($items))
            <div class="text-center py-20">
                <i class="fa-solid fa-basket-shopping text-5xl text-[#d4b896] mb-4"></i>
                <p class="text-[#8b5e3c] text-lg mb-6">Tu carrito está vacío</p>
                <a href="{{ route('catalogo') }}" class="inline-block bg-[#386641] text-[#faf6f0] px-8 py-3 text-[13px] tracking-wider hover:bg-[#2d5534] transition-colors">
                    Ver catálogo
                </a>
            </div>
        @else

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

            {{-- FORMULARIO (3/5) --}}
            <div class="lg:col-span-3 space-y-6">

                {{-- Datos personales --}}
                <div class="bg-white border border-[#d4b896]/30 p-6">
                    <h2 class="text-lg text-[#2c1a0e] mb-5" style="font-family: 'DM Serif Display', serif;">Tus datos</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5">Nombre completo *</label>
                            <input wire:model="nombre" type="text" placeholder="Tu nombre y apellido"
                                   class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-4 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
                            @error('nombre') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5">Email *</label>
                            <input wire:model="email" type="email" placeholder="tu@email.com"
                                   class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-4 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
                            @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5">Teléfono / WhatsApp *</label>
                            <input wire:model="telefono" type="tel" placeholder="Ej: 2324 123456"
                                   class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-4 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
                            @error('telefono') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Entrega --}}
                <div class="bg-white border border-[#d4b896]/30 p-6">
                    <h2 class="text-lg text-[#2c1a0e] mb-5" style="font-family: 'DM Serif Display', serif;">Método de entrega</h2>
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <label class="cursor-pointer">
                            <input type="radio" wire:model.live="metodo_entrega" value="retiro" class="sr-only peer">
                            <div class="border-2 border-[#d4b896]/50 peer-checked:border-[#386641] peer-checked:bg-[#386641]/5 p-4 text-center transition-all duration-200">
                                <i class="fa-solid fa-store text-xl text-[#386641] mb-2"></i>
                                <p class="text-sm font-medium text-[#2c1a0e]">Retiro en local</p>
                                <p class="text-[11px] text-[#8b5e3c]/70 mt-1">Sin costo</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" wire:model.live="metodo_entrega" value="envio" class="sr-only peer">
                            <div class="border-2 border-[#d4b896]/50 peer-checked:border-[#386641] peer-checked:bg-[#386641]/5 p-4 text-center transition-all duration-200">
                                <i class="fa-solid fa-truck text-xl text-[#386641] mb-2"></i>
                                <p class="text-sm font-medium text-[#2c1a0e]">Envío a domicilio</p>
                                <p class="text-[11px] text-[#8b5e3c]/70 mt-1">Costo a confirmar</p>
                            </div>
                        </label>
                    </div>

                    @if($metodo_entrega === 'envio')
                        <div x-data x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                            <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5">Dirección de envío *</label>
                            <input wire:model="direccion" type="text" placeholder="Calle, número, localidad"
                                   class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-4 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
                            @error('direccion') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            <p class="text-[11px] text-[#8b5e3c]/60 mt-2">El costo de envío será informado al confirmar el pedido.</p>
                        </div>
                    @endif
                </div>

                {{-- Pago --}}
                <div class="bg-white border border-[#d4b896]/30 p-6">
                    <h2 class="text-lg text-[#2c1a0e] mb-5" style="font-family: 'DM Serif Display', serif;">Método de pago</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" wire:model="metodo_pago" value="efectivo" class="sr-only peer">
                            <div class="border-2 border-[#d4b896]/50 peer-checked:border-[#386641] peer-checked:bg-[#386641]/5 p-4 text-center transition-all duration-200">
                                <i class="fa-solid fa-money-bill-wave text-xl text-[#386641] mb-2"></i>
                                <p class="text-sm font-medium text-[#2c1a0e]">Efectivo</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" wire:model="metodo_pago" value="transferencia" class="sr-only peer">
                            <div class="border-2 border-[#d4b896]/50 peer-checked:border-[#386641] peer-checked:bg-[#386641]/5 p-4 text-center transition-all duration-200">
                                <i class="fa-solid fa-building-columns text-xl text-[#386641] mb-2"></i>
                                <p class="text-sm font-medium text-[#2c1a0e]">Transferencia</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Notas --}}
                <div class="bg-white border border-[#d4b896]/30 p-6">
                    <h2 class="text-lg text-[#2c1a0e] mb-5" style="font-family: 'DM Serif Display', serif;">Notas (opcional)</h2>
                    <textarea wire:model="notas" rows="3" placeholder="Aclaraciones sobre el pedido..."
                              class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-4 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors resize-none"></textarea>
                    @error('notas') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

            </div>

            {{-- RESUMEN (2/5) --}}
            <div class="lg:col-span-2">
                <div class="bg-white border border-[#d4b896]/30 p-6 sticky top-24">
                    <h2 class="text-lg text-[#2c1a0e] mb-5" style="font-family: 'DM Serif Display', serif;">Resumen</h2>

                    <div class="space-y-3 mb-5">
                        @foreach($items as $item)
                            <div class="flex justify-between items-start gap-2">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-[#2c1a0e] truncate">{{ $item['nombre'] }}</p>
                                    <p class="text-[11px] text-[#8b5e3c]/70">{{ $item['cantidad'] }} × ${{ number_format($item['precio'], 2, ',', '.') }}</p>
                                </div>
                                <p class="text-sm font-medium text-[#2c1a0e] flex-shrink-0">
                                    ${{ number_format($item['subtotal'], 2, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-[#d4b896]/30 pt-4 space-y-2">
                        <div class="flex justify-between text-sm text-[#2c1a0e]/70">
                            <span>Subtotal</span>
                            <span>${{ number_format($subtotal, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-[#2c1a0e]/70">
                            <span>Envío</span>
                            <span>{{ $metodo_entrega === 'retiro' ? 'Sin costo' : 'A confirmar' }}</span>
                        </div>
                        <div class="flex justify-between text-base font-semibold text-[#2c1a0e] pt-2 border-t border-[#d4b896]/30">
                            <span>Total estimado</span>
                            <span>${{ number_format($subtotal, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    @error('carrito')
                        <p class="mt-3 text-red-600 text-xs bg-red-50 border border-red-200 p-3">{{ $message }}</p>
                    @enderror

                    <button wire:click="confirmarPedido"
                            wire:loading.attr="disabled"
                            class="mt-5 w-full bg-[#386641] text-[#faf6f0] py-3.5 text-[13px] tracking-wider font-medium
                                   hover:bg-[#2d5534] transition-colors duration-300 disabled:opacity-60">
                        <span wire:loading.remove wire:target="confirmarPedido">Confirmar pedido</span>
                        <span wire:loading wire:target="confirmarPedido">Procesando...</span>
                    </button>
                </div>
            </div>

        </div>
        @endif
    </div>
</div>
