<div>
    {{-- Botón flotante del carrito --}}
    <button wire:click="abrirCarrito"
            data-carrito-btn
            class="fixed bottom-6 right-6 z-40 bg-[#386641] text-[#faf6f0] shadow-lg
                   flex items-center hover:bg-[#2d5534] transition-colors duration-300 group
                   {{ $cantidadTotal > 0 ? 'rounded-full pl-3 pr-4 py-2.5 gap-2' : 'w-14 h-14 rounded-full justify-center' }}">
        <i class="fa-solid fa-basket-shopping text-xl"></i>
        @if($cantidadTotal > 0)
            <span class="text-sm font-semibold">${{ number_format($total, 0, ',', '.') }}</span>
            <span class="absolute -top-1 -right-1 bg-[#8b5e3c] text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">
                {{ $cantidadTotal }}
            </span>
        @endif
    </button>

    {{-- Overlay + Drawer --}}
    @if($abierto)
        {{-- Fondo oscuro --}}
        <div class="fixed inset-0 z-40 bg-black/40"
             wire:click="cerrarCarrito"
             x-data
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"></div>

        {{-- Panel lateral --}}
        <div class="fixed top-0 right-0 h-full w-full max-w-sm z-50 bg-[#faf6f0] shadow-2xl flex flex-col"
             x-data
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-full"
             x-transition:enter-end="opacity-100 translate-x-0">

            {{-- Encabezado del carrito --}}
            <div class="flex items-center justify-between px-5 py-4 border-b border-[#d4b896]/40 bg-[#f0e9de]">
                <div>
                    <h2 class="text-lg text-[#2c1a0e]" style="font-family: 'DM Serif Display', serif;">
                        Tu carrito
                    </h2>
                    @if($cantidadTotal > 0)
                        <p class="text-[11px] text-[#8b5e3c]/70 tracking-wider">{{ $cantidadTotal }} {{ $cantidadTotal === 1 ? 'producto' : 'productos' }}</p>
                    @endif
                </div>
                <button wire:click="cerrarCarrito" class="text-[#8b5e3c] hover:text-[#2c1a0e] transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            {{-- Items --}}
            <div class="flex-1 overflow-y-auto px-5 py-4">
                @forelse($items as $item)
                    <div class="flex gap-3 py-4 border-b border-[#d4b896]/25 last:border-0">
                        {{-- Imagen --}}
                        <div class="w-16 h-16 flex-shrink-0 overflow-hidden bg-[#f0e9de]">
                            @if($item['imagen'])
                                <img src="{{ asset('imagenes/' . rawurlencode($item['imagen'])) }}"
                                     alt="{{ $item['nombre'] }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fa-solid fa-leaf text-[#386641]/40 text-xl"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-[#2c1a0e] truncate">{{ $item['nombre'] }}</p>
                            <p class="text-xs text-[#8b5e3c]">{{ $item['unidad'] }} · ${{ number_format($item['precio'], 2, ',', '.') }}</p>

                            {{-- Controles cantidad --}}
                            <div class="flex items-center gap-2 mt-2">
                                <button wire:click="actualizarCantidad({{ $item['id'] }}, {{ $item['cantidad'] - 1 }})"
                                        class="w-6 h-6 flex items-center justify-center border border-[#d4b896] text-[#8b5e3c] hover:border-[#386641] hover:text-[#386641] transition-colors text-sm">
                                    <i class="fa-solid fa-minus text-[10px]"></i>
                                </button>
                                <span class="text-sm font-medium text-[#2c1a0e] w-5 text-center">{{ $item['cantidad'] }}</span>
                                <button wire:click="actualizarCantidad({{ $item['id'] }}, {{ $item['cantidad'] + 1 }})"
                                        @disabled($item['cantidad'] >= $item['stock'])
                                        class="w-6 h-6 flex items-center justify-center border border-[#d4b896] text-[#8b5e3c] hover:border-[#386641] hover:text-[#386641] transition-colors text-sm disabled:opacity-40">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Subtotal + eliminar --}}
                        <div class="flex flex-col items-end justify-between">
                            <button wire:click="removerItem({{ $item['id'] }})"
                                    class="text-[#d4b896] hover:text-[#c0392b] transition-colors">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                            <p class="text-sm font-semibold text-[#2c1a0e]">
                                ${{ number_format($item['subtotal'], 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full py-20 gap-4 text-center">
                        <i class="fa-solid fa-basket-shopping text-4xl text-[#d4b896]"></i>
                        <p class="text-[#8b5e3c]/70 text-sm">Tu carrito está vacío</p>
                        <a href="{{ route('catalogo') }}" wire:navigate wire:click="cerrarCarrito"
                                class="text-[12px] text-[#386641] border border-[#386641]/40 px-5 py-2 hover:bg-[#386641] hover:text-white transition-all duration-300">
                            Ver catálogo
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pie del carrito --}}
            @if(count($items) > 0)
                <div class="px-5 py-4 border-t border-[#d4b896]/40 bg-[#f0e9de] space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-[#2c1a0e]/70">Subtotal</span>
                        <span class="text-base font-semibold text-[#2c1a0e]">${{ number_format($total, 2, ',', '.') }}</span>
                    </div>
                    <p class="text-[11px] text-[#8b5e3c]/60">El costo de envío se calcula al finalizar el pedido.</p>
                    <a href="{{ route('checkout') }}" wire:navigate
                       class="block w-full text-center bg-[#386641] text-[#faf6f0] py-3 text-[13px] tracking-wider font-medium
                              hover:bg-[#2d5534] transition-colors duration-300">
                        Finalizar pedido
                    </a>
                    <button wire:click="vaciarCarrito"
                            class="block w-full text-center text-[#8b5e3c]/60 text-[11px] hover:text-[#c0392b] transition-colors">
                        Vaciar carrito
                    </button>
                </div>
            @endif

        </div>
    @endif
</div>
