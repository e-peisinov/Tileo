<div class="min-h-screen bg-[#faf6f0] py-12 px-4">
    <div class="max-w-5xl mx-auto">

        <div class="text-center mb-10">
            <p class="text-[#8b5e3c]/70 tracking-[0.3em] uppercase text-[11px] font-medium mb-2">Último paso</p>
            <h1 class="text-4xl text-[#2c1a0e]" style="font-family: 'DM Serif Display', serif;">Finalizar pedido</h1>
        </div>

        {{-- Modo vacaciones --}}
        @if($modoVacaciones)
            <div class="max-w-xl mx-auto text-center py-16">
                <div class="w-16 h-16 bg-[#f0e9de] rounded-full flex items-center justify-center mx-auto mb-5">
                    <i class="fa-solid fa-umbrella-beach text-3xl text-[#8b5e3c]"></i>
                </div>
                <h2 class="text-2xl text-[#2c1a0e] mb-3" style="font-family:'DM Serif Display',serif;">Estamos de vacaciones</h2>
                <p class="text-[#8b5e3c]/80 text-sm leading-relaxed mb-6">{{ $msgVacaciones }}</p>
                <a href="{{ route('catalogo') }}" wire:navigate class="inline-block border border-[#386641]/40 text-[#386641] px-8 py-3 text-[13px] tracking-wider hover:bg-[#386641] hover:text-white transition-colors">
                    Ver catálogo
                </a>
            </div>
        @elseif(empty($items))
            <div class="text-center py-20">
                <i class="fa-solid fa-basket-shopping text-5xl text-[#d4b896] mb-4"></i>
                <p class="text-[#8b5e3c] text-lg mb-6">Tu carrito está vacío</p>
                <a href="{{ route('catalogo') }}" wire:navigate class="inline-block bg-[#386641] text-[#faf6f0] px-8 py-3 text-[13px] tracking-wider hover:bg-[#2d5534] transition-colors">
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
                            <input type="radio" wire:model.live="metodo_pago" value="efectivo" class="sr-only peer">
                            <div class="border-2 border-[#d4b896]/50 peer-checked:border-[#386641] peer-checked:bg-[#386641]/5 p-4 text-center transition-all duration-200">
                                <i class="fa-solid fa-money-bill-wave text-xl text-[#386641] mb-2"></i>
                                <p class="text-sm font-medium text-[#2c1a0e]">Efectivo</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" wire:model.live="metodo_pago" value="transferencia" class="sr-only peer">
                            <div class="border-2 border-[#d4b896]/50 peer-checked:border-[#386641] peer-checked:bg-[#386641]/5 p-4 text-center transition-all duration-200">
                                <i class="fa-solid fa-building-columns text-xl text-[#386641] mb-2"></i>
                                <p class="text-sm font-medium text-[#2c1a0e]">Transferencia</p>
                            </div>
                        </label>
                    </div>

                    @if($metodo_pago === 'transferencia' && ($cbu || $aliasCbu || $titularCuenta))
                        <div class="mt-4 p-4 rounded-xl border border-[#d4b896]/40" style="background-color: rgba(250,246,240,0.8);">
                            <p class="text-[11px] tracking-wider text-[#8b5e3c] uppercase font-semibold mb-2.5">Datos para la transferencia</p>
                            <div class="space-y-1.5 text-sm">
                                @if($titularCuenta)
                                    <p class="text-[#2c1a0e]">
                                        <span class="text-[#8b5e3c]">Titular:</span> {{ $titularCuenta }}
                                    </p>
                                @endif
                                @if($cbu)
                                    <p class="text-[#2c1a0e] font-mono text-xs">
                                        <span class="text-[#8b5e3c] font-sans">CBU:</span> {{ $cbu }}
                                    </p>
                                @endif
                                @if($aliasCbu)
                                    <p class="text-[#2c1a0e]">
                                        <span class="text-[#8b5e3c]">Alias:</span> {{ $aliasCbu }}
                                    </p>
                                @endif
                            </div>
                            <p class="text-[10px] text-[#8b5e3c]/60 mt-2">Realizá la transferencia una vez confirmado el pedido.</p>
                        </div>
                    @elseif($metodo_pago === 'transferencia')
                        <p class="mt-3 text-[11px] text-[#8b5e3c]/60">Los datos bancarios serán enviados por email al confirmar el pedido.</p>
                    @endif
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

                    {{-- Código de descuento --}}
                    <div class="border-t border-[#d4b896]/30 pt-4 mb-2">
                        <p class="text-[10px] tracking-wider text-[#8b5e3c] uppercase font-semibold mb-2">Código de descuento</p>
                        @if($descuentoAplicado)
                            <div class="flex items-center justify-between bg-[#386641]/8 border border-[#386641]/30 rounded-xl px-3 py-2.5">
                                <div class="flex items-center gap-2 min-w-0">
                                    <i class="fa-solid fa-tag text-[#386641] text-xs flex-shrink-0"></i>
                                    <span class="text-xs font-semibold text-[#386641] truncate">{{ $mensajeDescuento }}</span>
                                </div>
                                <button wire:click="quitarDescuento"
                                        class="text-[#8b5e3c]/60 hover:text-red-500 transition-colors ml-2 flex-shrink-0">
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </button>
                            </div>
                        @else
                            <div class="flex gap-2">
                                <input wire:model="codigoDescuentoInput"
                                       type="text"
                                       placeholder="CÓDIGO"
                                       class="flex-1 min-w-0 border border-[#d4b896]/50 bg-[#faf6f0] px-3 py-2 text-sm text-[#2c1a0e] uppercase tracking-wider focus:outline-none focus:border-[#386641] transition-colors">
                                <button wire:click="aplicarDescuento"
                                        wire:loading.attr="disabled"
                                        class="px-4 py-2 bg-[#8b5e3c]/10 border border-[#d4b896]/50 text-[#8b5e3c] text-xs font-semibold hover:bg-[#8b5e3c] hover:text-white transition-all duration-200 flex-shrink-0">
                                    Aplicar
                                </button>
                            </div>
                            @if($mensajeDescuento)
                                <p class="text-xs mt-1.5 {{ $descuentoExitoso ? 'text-[#386641]' : 'text-red-500' }}">
                                    {{ $mensajeDescuento }}
                                </p>
                            @endif
                        @endif
                    </div>

                    <div class="border-t border-[#d4b896]/30 pt-4 space-y-2">
                        <div class="flex justify-between text-sm text-[#2c1a0e]/70">
                            <span>Subtotal</span>
                            <span>${{ number_format($subtotal, 2, ',', '.') }}</span>
                        </div>
                        @if($montoDescuento > 0)
                            <div class="flex justify-between text-sm text-[#386641]">
                                <span>Descuento</span>
                                <span>− ${{ number_format($montoDescuento, 2, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm text-[#2c1a0e]/70">
                            <span>Envío</span>
                            <span>{{ $metodo_entrega === 'retiro' ? 'Sin costo' : 'A confirmar' }}</span>
                        </div>
                        <div class="flex justify-between text-base font-semibold text-[#2c1a0e] pt-2 border-t border-[#d4b896]/30">
                            <span>Total estimado</span>
                            <span>${{ number_format($total, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($tiempoEntrega)
                        <p class="text-[11px] text-[#8b5e3c]/60 mt-3 flex items-center gap-1.5">
                            <i class="fa-solid fa-clock text-[10px]"></i>
                            Tiempo de entrega estimado: {{ $tiempoEntrega }}
                        </p>
                    @endif

                    @error('carrito')
                        <p class="mt-3 text-red-600 text-xs bg-red-50 border border-red-200 p-3">{{ $message }}</p>
                    @enderror

                    <button wire:click="revisarPedido"
                            wire:loading.attr="disabled"
                            class="mt-5 w-full bg-[#386641] text-[#faf6f0] py-3.5 text-[13px] tracking-wider font-medium
                                   hover:bg-[#2d5534] transition-colors duration-300 disabled:opacity-60">
                        <span wire:loading.remove wire:target="revisarPedido">
                            <i class="fa-solid fa-eye text-xs mr-1.5"></i> Revisar pedido
                        </span>
                        <span wire:loading wire:target="revisarPedido">Verificando...</span>
                    </button>

                    <p class="text-[10px] text-[#2c1a0e]/40 text-center mt-3 leading-relaxed">
                        Al confirmar aceptás que tus datos sean utilizados exclusivamente para procesar este pedido.
                    </p>
                </div>
            </div>

        </div>
        @endif
    </div>

    {{-- MODAL DE REVISIÓN --}}
    @if($revisando)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
             style="background-color: rgba(44,26,14,0.6); backdrop-filter: blur(4px);">
            <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl my-4"
                 x-data x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                <div class="px-6 py-4 border-b border-[#d4b896]/30 flex items-center justify-between"
                     style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                    <h2 class="text-lg text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">
                        Revisá tu pedido
                    </h2>
                    <button wire:click="volverFormulario"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-[#8b5e3c] hover:bg-[#d4b896]/30 transition-all duration-200">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">

                    {{-- Productos --}}
                    <div>
                        <p class="text-[10px] tracking-wider text-[#8b5e3c] uppercase font-semibold mb-2">Productos</p>
                        <div class="space-y-1.5">
                            @foreach($items as $item)
                                <div class="flex justify-between text-sm">
                                    <span class="text-[#2c1a0e]">{{ $item['nombre'] }} <span class="text-[#8b5e3c]/60">×{{ $item['cantidad'] }}</span></span>
                                    <span class="font-medium text-[#2c1a0e]">
                                        {{ $item['precio'] > 0 ? '$' . number_format($item['subtotal'], 2, ',', '.') : 'A confirmar' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 pt-3 border-t border-[#d4b896]/30 space-y-1.5">
                            <div class="flex justify-between text-sm text-[#2c1a0e]/70">
                                <span>Subtotal</span>
                                <span>${{ number_format($subtotal, 2, ',', '.') }}</span>
                            </div>
                            @if($montoDescuento > 0)
                                <div class="flex justify-between text-sm text-[#386641]">
                                    <span>Descuento</span>
                                    <span>− ${{ number_format($montoDescuento, 2, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-sm font-semibold text-[#2c1a0e] pt-1 border-t border-[#d4b896]/20">
                                <span>Total</span>
                                <span>${{ number_format($total, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Datos --}}
                    <div class="space-y-1.5 text-sm border-t border-[#d4b896]/20 pt-4">
                        <p class="text-[10px] tracking-wider text-[#8b5e3c] uppercase font-semibold mb-2">Tus datos</p>
                        <p class="text-[#2c1a0e]"><span class="text-[#8b5e3c]">Nombre:</span> {{ $nombre }}</p>
                        <p class="text-[#2c1a0e]"><span class="text-[#8b5e3c]">Email:</span> {{ $email }}</p>
                        <p class="text-[#2c1a0e]"><span class="text-[#8b5e3c]">Teléfono:</span> {{ $telefono }}</p>
                        <p class="text-[#2c1a0e]">
                            <span class="text-[#8b5e3c]">Entrega:</span>
                            {{ $metodo_entrega === 'envio' ? 'Envío a domicilio' : 'Retiro en local' }}
                            @if($metodo_entrega === 'envio' && $direccion) — {{ $direccion }}@endif
                        </p>
                        <p class="text-[#2c1a0e]">
                            <span class="text-[#8b5e3c]">Pago:</span>
                            {{ $metodo_pago === 'transferencia' ? 'Transferencia bancaria' : 'Efectivo' }}
                        </p>
                        @if($notas)
                            <p class="text-[#2c1a0e]"><span class="text-[#8b5e3c]">Notas:</span> {{ $notas }}</p>
                        @endif
                    </div>

                    {{-- Error --}}
                    @error('carrito')
                        <p class="text-red-600 text-xs bg-red-50 border border-red-200 p-3 rounded-lg">{{ $message }}</p>
                    @enderror

                    {{-- Privacidad --}}
                    <p class="text-[10px] text-[#2c1a0e]/40 leading-relaxed border-t border-[#d4b896]/20 pt-4">
                        Tus datos personales se utilizan exclusivamente para procesar este pedido y no se comparten con terceros.
                    </p>
                </div>

                <div class="px-6 pb-6 flex gap-3">
                    <button wire:click="volverFormulario"
                            class="flex-1 border border-[#d4b896]/50 text-[#8b5e3c] rounded-xl py-2.5 text-[13px] font-medium
                                   hover:border-[#8b5e3c] hover:bg-[#f0e9de] transition-all duration-200">
                        <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Modificar
                    </button>
                    <button wire:click="confirmarPedido"
                            wire:loading.attr="disabled"
                            class="flex-1 rounded-xl py-2.5 text-[13px] font-semibold text-white shadow-sm
                                   hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 disabled:opacity-60"
                            style="background: linear-gradient(135deg, #386641 0%, #2d5534 100%);">
                        <span wire:loading.remove wire:target="confirmarPedido">
                            <i class="fa-solid fa-check text-xs mr-1"></i> Confirmar pedido
                        </span>
                        <span wire:loading wire:target="confirmarPedido" class="flex items-center justify-center gap-2">
                            <i class="fa-solid fa-spinner fa-spin text-xs"></i> Procesando...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
