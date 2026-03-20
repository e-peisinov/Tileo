<div class="min-h-screen py-10 px-4" style="background: linear-gradient(150deg, #faf6f0 0%, #f0e9de 100%);">
    <div class="max-w-4xl mx-auto">

        {{-- Nav admin --}}
        @include('livewire.admin.partials.nav')

        {{-- Volver --}}
        <div class="mb-5">
            <a href="{{ route('admin.pedidos') }}" wire:navigate
               class="inline-flex items-center gap-1.5 text-[#386641] text-sm font-medium hover:text-[#2d5534] transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i> Volver a pedidos
            </a>
        </div>

        {{-- Encabezado del pedido --}}
        <div class="bg-white rounded-2xl shadow-sm border border-[#d4b896]/20 px-6 py-5 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-[#8b5e3c]/60 tracking-[0.25em] uppercase text-[10px] font-semibold mb-1">Detalle del pedido</p>
                <h1 class="text-3xl text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">{{ $pedido->numero_pedido }}</h1>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Columna izquierda --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Productos --}}
                <div class="bg-white rounded-2xl shadow-sm border border-[#d4b896]/20 overflow-hidden">
                    <div class="px-6 py-4 border-b border-[#d4b896]/20" style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                        <h2 class="text-base text-[#2c1a0e] flex items-center gap-2" style="font-family:'DM Serif Display',serif;">
                            <i class="fa-solid fa-basket-shopping text-sm text-[#8b5e3c]/50"></i>
                            Productos del pedido
                        </h2>
                    </div>
                    <div class="p-6">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-[#d4b896]/25">
                                    <th class="text-left pb-2.5 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-semibold">Producto</th>
                                    <th class="text-center pb-2.5 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-semibold">Cant.</th>
                                    <th class="text-right pb-2.5 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-semibold">Precio</th>
                                    <th class="text-right pb-2.5 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-semibold">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pedido->items as $item)
                                    <tr class="border-b border-[#d4b896]/15 hover:bg-[#faf6f0]/50 transition-colors">
                                        <td class="py-3 text-[#2c1a0e] font-medium">{{ $item->nombre_producto }}</td>
                                        <td class="py-3 text-center text-[#2c1a0e]/70">
                                            <span class="inline-block text-[12px] font-medium px-2 py-0.5 rounded-lg" style="background-color: rgba(139,94,60,0.08);">
                                                {{ $item->cantidad }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-right text-[#2c1a0e]/70">${{ number_format($item->precio_unitario, 2, ',', '.') }}</td>
                                        <td class="py-3 text-right font-semibold text-[#2c1a0e]">${{ number_format($item->subtotal, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="pt-3 text-right text-sm text-[#2c1a0e]/60">Subtotal</td>
                                    <td class="pt-3 text-right font-medium text-[#2c1a0e]">${{ number_format($pedido->subtotal, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="pt-1 text-right text-sm text-[#2c1a0e]/60">Envío</td>
                                    <td class="pt-1 text-right text-[#2c1a0e]">
                                        @if($pedido->metodo_entrega === 'retiro')
                                            <span class="text-[#386641] font-medium">Sin costo</span>
                                        @elseif(is_null($pedido->costo_envio))
                                            <span class="text-[#8b5e3c] italic">A confirmar</span>
                                        @else
                                            ${{ number_format($pedido->costo_envio, 2, ',', '.') }}
                                        @endif
                                    </td>
                                </tr>
                                <tr class="border-t-2 border-[#d4b896]/40">
                                    <td colspan="3" class="pt-3 text-right font-bold text-[#2c1a0e]">Total</td>
                                    <td class="pt-3 text-right font-bold text-lg" style="color: #386641;">${{ number_format($pedido->total, 2, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Datos del cliente --}}
                <div class="bg-white rounded-2xl shadow-sm border border-[#d4b896]/20 overflow-hidden">
                    <div class="px-6 py-4 border-b border-[#d4b896]/20" style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                        <h2 class="text-base text-[#2c1a0e] flex items-center gap-2" style="font-family:'DM Serif Display',serif;">
                            <i class="fa-solid fa-user text-sm text-[#8b5e3c]/50"></i>
                            Datos del cliente
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="p-3 rounded-xl" style="background-color: rgba(250,246,240,0.8);">
                                <p class="text-[10px] text-[#8b5e3c] uppercase tracking-wider mb-1 font-semibold">Nombre</p>
                                <p class="text-[#2c1a0e] font-medium">{{ $pedido->nombre_cliente }}</p>
                            </div>
                            <div class="p-3 rounded-xl" style="background-color: rgba(250,246,240,0.8);">
                                <p class="text-[10px] text-[#8b5e3c] uppercase tracking-wider mb-1 font-semibold">Email</p>
                                <p class="text-[#2c1a0e]">{{ $pedido->email_cliente }}</p>
                            </div>
                            <div class="p-3 rounded-xl" style="background-color: rgba(250,246,240,0.8);">
                                <p class="text-[10px] text-[#8b5e3c] uppercase tracking-wider mb-1 font-semibold">Teléfono</p>
                                <p class="text-[#2c1a0e]">{{ $pedido->telefono_cliente }}</p>
                            </div>
                            <div class="p-3 rounded-xl" style="background-color: rgba(250,246,240,0.8);">
                                <p class="text-[10px] text-[#8b5e3c] uppercase tracking-wider mb-1 font-semibold">Entrega</p>
                                <p class="text-[#2c1a0e]">{{ $pedido->metodo_entrega === 'envio' ? 'Envío a domicilio' : 'Retiro en local' }}</p>
                                @if($pedido->metodo_entrega === 'envio')
                                    <p class="text-xs text-[#8b5e3c]/70 mt-0.5">{{ $pedido->direccion_envio }}</p>
                                @endif
                            </div>
                            <div class="p-3 rounded-xl" style="background-color: rgba(250,246,240,0.8);">
                                <p class="text-[10px] text-[#8b5e3c] uppercase tracking-wider mb-1 font-semibold">Pago</p>
                                <p class="text-[#2c1a0e]">{{ $pedido->metodo_pago === 'transferencia' ? 'Transferencia bancaria' : 'Efectivo' }}</p>
                            </div>
                        </div>
                        @if($pedido->notas_cliente)
                            <div class="mt-4 pt-4 border-t border-[#d4b896]/25">
                                <p class="text-[10px] text-[#8b5e3c] uppercase tracking-wider mb-2 font-semibold">Notas del cliente</p>
                                <p class="text-sm text-[#2c1a0e]/80 bg-[#faf6f0] rounded-xl p-3 italic">{{ $pedido->notas_cliente }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Columna derecha: gestión --}}
            <div class="space-y-5">

                <div class="bg-white rounded-2xl shadow-sm border border-[#d4b896]/20 overflow-hidden">
                    <div class="px-6 py-4 border-b border-[#d4b896]/20" style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                        <h2 class="text-base text-[#2c1a0e] flex items-center gap-2" style="font-family:'DM Serif Display',serif;">
                            <i class="fa-solid fa-sliders text-sm text-[#8b5e3c]/50"></i>
                            Gestión
                        </h2>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5 font-semibold">Estado del pedido</label>
                            <select wire:model="estado"
                                    class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-lg px-3 py-2.5 text-sm text-[#2c1a0e]
                                           focus:outline-none focus:ring-2 focus:ring-[#386641]/20 focus:border-[#386641] transition-all duration-200">
                                <option value="pendiente">Pendiente</option>
                                <option value="confirmado">Confirmado</option>
                                <option value="preparando">Preparando</option>
                                <option value="enviado">Enviado</option>
                                <option value="listo_retiro">Listo para retirar</option>
                                <option value="entregado">Entregado</option>
                                <option value="rechazado">Rechazado</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>

                        @if($pedido->metodo_entrega === 'envio')
                            <div>
                                <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5 font-semibold">Costo de envío ($)</label>
                                <input wire:model="costo_envio" type="number" min="0" step="0.01"
                                       placeholder="0.00"
                                       class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-lg px-3 py-2.5 text-sm text-[#2c1a0e]
                                              focus:outline-none focus:ring-2 focus:ring-[#386641]/20 focus:border-[#386641] transition-all duration-200">
                                @error('costo_envio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        <div>
                            <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5 font-semibold">Notas internas</label>
                            <textarea wire:model="notas_admin" rows="3"
                                      placeholder="Notas visibles solo para el admin..."
                                      class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-lg px-3 py-2.5 text-sm text-[#2c1a0e]
                                             focus:outline-none focus:ring-2 focus:ring-[#386641]/20 focus:border-[#386641] transition-all duration-200 resize-none"></textarea>
                        </div>

                        @if($guardado)
                            <div class="flex items-center gap-2 text-[#386641] text-sm rounded-lg px-3 py-2.5 border border-[#386641]/20"
                                 style="background-color: rgba(56,102,65,0.06);">
                                <i class="fa-solid fa-check text-xs"></i> Cambios guardados
                            </div>
                        @endif

                        <button wire:click="guardar"
                                wire:loading.attr="disabled"
                                class="w-full rounded-xl py-2.5 text-[13px] font-semibold text-white shadow-sm
                                       hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed"
                                style="background: linear-gradient(135deg, #386641 0%, #2d5534 100%);">
                            <span wire:loading.remove wire:target="guardar">Guardar cambios</span>
                            <span wire:loading wire:target="guardar" class="flex items-center justify-center gap-2">
                                <i class="fa-solid fa-spinner fa-spin text-xs"></i> Guardando...
                            </span>
                        </button>
                    </div>
                </div>

            </div>
        </div>

        {{-- Historial de estados --}}
        @if($pedido->historial->count() > 0)
            <div class="mt-6 bg-white rounded-2xl shadow-sm border border-[#d4b896]/20 overflow-hidden">
                <div class="px-6 py-4 border-b border-[#d4b896]/20" style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                    <h2 class="text-base text-[#2c1a0e] flex items-center gap-2" style="font-family:'DM Serif Display',serif;">
                        <i class="fa-solid fa-timeline text-sm text-[#8b5e3c]/50"></i>
                        Historial de cambios
                    </h2>
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
                                        @if($entrada->notas)
                                            <span class="text-[11px] text-[#2c1a0e]/60 ml-1">— {{ $entrada->notas }}</span>
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
