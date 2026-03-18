<div>
    {{-- Encabezado --}}
    <div class="py-14 px-4 text-center" style="background-color: #f0e9de;">
        <p class="text-xs tracking-[0.3em] uppercase text-[#8b5e3c] mb-3 font-semibold">Tileo</p>
        <h1 class="text-4xl md:text-5xl text-[#2c1a0e] mb-4" style="font-family: 'DM Serif Display', serif;">
            Seguimiento de Pedido
        </h1>
        <p class="text-[#8b5e3c]/70 text-base max-w-xl mx-auto">
            Ingresá tu número de pedido para ver el estado actual y el historial de cambios.
        </p>
    </div>

    {{-- Formulario de búsqueda --}}
    <div class="py-12 px-4" style="background-color: #faf6f0;">
        <div class="max-w-lg mx-auto">
            <div class="bg-white rounded-2xl border border-[#d4b896]/40 shadow-sm p-8">
                <form wire:submit="buscar" class="space-y-4">
                    <div>
                        <label for="numeroPedido" class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-2 font-semibold">
                            Número de pedido
                        </label>
                        <input
                            wire:model="numeroPedido"
                            id="numeroPedido"
                            type="text"
                            placeholder="TIL-0001"
                            autocomplete="off"
                            class="w-full border border-[#d4b896]/60 bg-[#faf6f0] rounded-xl px-4 py-3 text-[#2c1a0e] text-base
                                   focus:outline-none focus:ring-2 focus:ring-[#386641]/25 focus:border-[#386641] transition-all duration-200
                                   placeholder:text-[#8b5e3c]/40"
                        >
                        @error('numeroPedido')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="w-full py-3 rounded-xl text-white text-sm font-semibold shadow-sm
                               hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200
                               disabled:opacity-60 disabled:cursor-not-allowed"
                        style="background: linear-gradient(135deg, #386641 0%, #2d5534 100%);"
                    >
                        <span wire:loading.remove wire:target="buscar">
                            <i class="fa-solid fa-magnifying-glass mr-2"></i> Buscar pedido
                        </span>
                        <span wire:loading wire:target="buscar" class="flex items-center justify-center gap-2">
                            <i class="fa-solid fa-spinner fa-spin text-xs"></i> Buscando...
                        </span>
                    </button>
                </form>
            </div>

            {{-- Pedido no encontrado --}}
            @if($buscado && !$pedido)
                <div class="mt-6 bg-white rounded-2xl border border-[#d4b896]/40 shadow-sm p-6 text-center">
                    <i class="fa-solid fa-circle-exclamation text-2xl mb-3" style="color: #8b5e3c;"></i>
                    <p class="text-[#2c1a0e] font-medium">No encontramos ningún pedido con ese número.</p>
                    <p class="text-sm text-[#8b5e3c]/70 mt-1">Verificá que el número sea correcto (ejemplo: TIL-0001).</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Resultado del pedido --}}
    @if($pedido)
        <div class="pb-16 px-4" style="background-color: #faf6f0;">
            <div class="max-w-3xl mx-auto space-y-5">

                {{-- Encabezado del pedido --}}
                <div class="bg-white rounded-2xl border border-[#d4b896]/40 shadow-sm px-6 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <p class="text-[10px] tracking-[0.25em] uppercase text-[#8b5e3c]/60 font-semibold mb-1">Tu pedido</p>
                        <h2 class="text-3xl text-[#2c1a0e]" style="font-family: 'DM Serif Display', serif;">
                            {{ $pedido->numero_pedido }}
                        </h2>
                        <p class="text-sm text-[#8b5e3c]/60 mt-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-clock text-[10px]"></i>
                            {{ $pedido->created_at->format('d/m/Y - H:i') }}
                        </p>
                    </div>
                    <span class="inline-block px-4 py-2 text-sm font-semibold rounded-xl text-white shadow-sm w-fit"
                          style="background-color: {{ $pedido->colorEstado() }}">
                        {{ $pedido->etiquetaEstado() }}
                    </span>
                </div>

                {{-- Ítems del pedido --}}
                <div class="bg-white rounded-2xl border border-[#d4b896]/40 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-[#d4b896]/20" style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                        <h3 class="text-base text-[#2c1a0e] flex items-center gap-2" style="font-family: 'DM Serif Display', serif;">
                            <i class="fa-solid fa-basket-shopping text-sm text-[#8b5e3c]/50"></i>
                            Productos del pedido
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($pedido->items as $item)
                                <div class="flex items-center justify-between py-2 border-b border-[#d4b896]/15 last:border-0">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-block text-[12px] font-semibold px-2 py-0.5 rounded-lg text-[#8b5e3c]"
                                              style="background-color: rgba(139,94,60,0.08);">
                                            × {{ $item->cantidad }}
                                        </span>
                                        <span class="text-sm text-[#2c1a0e] font-medium">{{ $item->nombre_producto }}</span>
                                    </div>
                                    <span class="text-sm font-semibold text-[#2c1a0e]">
                                        ${{ number_format($item->subtotal, 2, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 pt-4 border-t-2 border-[#d4b896]/30 space-y-1">
                            <div class="flex justify-between text-sm text-[#2c1a0e]/60">
                                <span>Subtotal</span>
                                <span>${{ number_format($pedido->subtotal, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-[#2c1a0e]/60">
                                <span>Envío</span>
                                <span>
                                    @if($pedido->metodo_entrega === 'retiro')
                                        <span class="text-[#386641] font-medium">Sin costo</span>
                                    @elseif(is_null($pedido->costo_envio))
                                        <span class="italic text-[#8b5e3c]">A confirmar</span>
                                    @else
                                        ${{ number_format($pedido->costo_envio, 2, ',', '.') }}
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between text-base font-bold text-[#2c1a0e] pt-1">
                                <span>Total</span>
                                <span style="color: #386641;">${{ number_format($pedido->total, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Método de entrega y pago --}}
                <div class="bg-white rounded-2xl border border-[#d4b896]/40 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-[#d4b896]/20" style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                        <h3 class="text-base text-[#2c1a0e] flex items-center gap-2" style="font-family: 'DM Serif Display', serif;">
                            <i class="fa-solid fa-truck text-sm text-[#8b5e3c]/50"></i>
                            Entrega y pago
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div class="p-3 rounded-xl" style="background-color: rgba(250,246,240,0.8);">
                                <p class="text-[10px] text-[#8b5e3c] uppercase tracking-wider mb-1 font-semibold">Método de entrega</p>
                                <p class="text-[#2c1a0e] font-medium">
                                    {{ $pedido->metodo_entrega === 'envio' ? 'Envío a domicilio' : 'Retiro en local' }}
                                </p>
                                @if($pedido->metodo_entrega === 'envio' && $pedido->direccion_envio)
                                    <p class="text-xs text-[#8b5e3c]/70 mt-0.5">{{ $pedido->direccion_envio }}</p>
                                @endif
                            </div>
                            <div class="p-3 rounded-xl" style="background-color: rgba(250,246,240,0.8);">
                                <p class="text-[10px] text-[#8b5e3c] uppercase tracking-wider mb-1 font-semibold">Método de pago</p>
                                <p class="text-[#2c1a0e] font-medium">
                                    {{ $pedido->metodo_pago === 'transferencia' ? 'Transferencia bancaria' : 'Efectivo' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Historial de estados --}}
                @if($pedido->historial->count() > 0)
                    <div class="bg-white rounded-2xl border border-[#d4b896]/40 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-[#d4b896]/20" style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                            <h3 class="text-base text-[#2c1a0e] flex items-center gap-2" style="font-family: 'DM Serif Display', serif;">
                                <i class="fa-solid fa-timeline text-sm text-[#8b5e3c]/50"></i>
                                Historial del pedido
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="relative">
                                {{-- Línea vertical del timeline --}}
                                <div class="absolute left-[5px] top-2 bottom-2 w-px" style="background-color: rgba(212,184,150,0.4);"></div>
                                <div class="space-y-4">
                                    @foreach($pedido->historial as $entrada)
                                        <div class="flex items-start gap-4 text-sm pl-5 relative">
                                            {{-- Punto del timeline --}}
                                            <div class="absolute left-0 top-1.5 w-2.5 h-2.5 rounded-full border-2 border-white shadow-sm shrink-0"
                                                 style="background-color: {{ App\Models\Pedido::colorParaEstado($entrada->estado_nuevo) }}"></div>
                                            <div class="text-[11px] text-[#8b5e3c]/60 w-28 shrink-0 pt-0.5">
                                                {{ $entrada->created_at->format('d/m/Y H:i') }}
                                            </div>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="inline-block px-2.5 py-1 text-[10px] font-semibold rounded-full text-white shadow-sm"
                                                      style="background-color: {{ App\Models\Pedido::colorParaEstado($entrada->estado_anterior) }}">
                                                    {{ App\Models\Pedido::etiquetaParaEstado($entrada->estado_anterior) }}
                                                </span>
                                                <i class="fa-solid fa-arrow-right text-[9px] text-[#8b5e3c]/40"></i>
                                                <span class="inline-block px-2.5 py-1 text-[10px] font-semibold rounded-full text-white shadow-sm"
                                                      style="background-color: {{ App\Models\Pedido::colorParaEstado($entrada->estado_nuevo) }}">
                                                    {{ App\Models\Pedido::etiquetaParaEstado($entrada->estado_nuevo) }}
                                                </span>
                                                @if($entrada->nota)
                                                    <span class="text-[11px] text-[#2c1a0e]/60 ml-1">— {{ $entrada->nota }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    @endif
</div>
