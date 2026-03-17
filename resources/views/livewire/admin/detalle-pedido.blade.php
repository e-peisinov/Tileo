<div class="min-h-screen bg-[#faf6f0] py-10 px-4">
    <div class="max-w-4xl mx-auto">

        <div class="mb-6">
            <a href="{{ route('admin.pedidos') }}" class="text-[#386641] text-sm hover:underline">← Volver a pedidos</a>
        </div>

        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-[#8b5e3c]/70 tracking-[0.25em] uppercase text-[10px] font-medium mb-1">Detalle del pedido</p>
                <h1 class="text-3xl text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">{{ $pedido->numero_pedido }}</h1>
                <p class="text-sm text-[#8b5e3c]/60 mt-1">{{ $pedido->created_at->format('d/m/Y \ - \ H:i') }}</p>
            </div>
            <span class="inline-block px-3 py-1.5 text-sm font-medium rounded-full text-white"
                  style="background-color: {{ $pedido->colorEstado() }}">
                {{ $pedido->etiquetaEstado() }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Columna izquierda --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Productos --}}
                <div class="bg-white border border-[#d4b896]/30 p-6">
                    <h2 class="text-base font-medium text-[#2c1a0e] mb-4" style="font-family:'DM Serif Display',serif;">Productos</h2>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-[#d4b896]/30">
                                <th class="text-left py-2 text-[11px] tracking-wider text-[#8b5e3c] uppercase">Producto</th>
                                <th class="text-center py-2 text-[11px] tracking-wider text-[#8b5e3c] uppercase">Cant.</th>
                                <th class="text-right py-2 text-[11px] tracking-wider text-[#8b5e3c] uppercase">Precio</th>
                                <th class="text-right py-2 text-[11px] tracking-wider text-[#8b5e3c] uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedido->items as $item)
                                <tr class="border-b border-[#d4b896]/15">
                                    <td class="py-3 text-[#2c1a0e]">{{ $item->nombre_producto }}</td>
                                    <td class="py-3 text-center text-[#2c1a0e]/70">{{ $item->cantidad }}</td>
                                    <td class="py-3 text-right text-[#2c1a0e]/70">${{ number_format($item->precio_unitario, 2, ',', '.') }}</td>
                                    <td class="py-3 text-right font-medium text-[#2c1a0e]">${{ number_format($item->subtotal, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="pt-3 text-right text-sm text-[#2c1a0e]/70">Subtotal</td>
                                <td class="pt-3 text-right font-medium text-[#2c1a0e]">${{ number_format($pedido->subtotal, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="pt-1 text-right text-sm text-[#2c1a0e]/70">Envío</td>
                                <td class="pt-1 text-right text-[#2c1a0e]">
                                    @if($pedido->metodo_entrega === 'retiro')
                                        Sin costo
                                    @elseif(is_null($pedido->costo_envio))
                                        <span class="text-[#8b5e3c]">A confirmar</span>
                                    @else
                                        ${{ number_format($pedido->costo_envio, 2, ',', '.') }}
                                    @endif
                                </td>
                            </tr>
                            <tr class="border-t border-[#d4b896]/30">
                                <td colspan="3" class="pt-2 text-right font-semibold text-[#2c1a0e]">Total</td>
                                <td class="pt-2 text-right font-bold text-[#386641] text-base">${{ number_format($pedido->total, 2, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Datos del cliente --}}
                <div class="bg-white border border-[#d4b896]/30 p-6">
                    <h2 class="text-base font-medium text-[#2c1a0e] mb-4" style="font-family:'DM Serif Display',serif;">Cliente</h2>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div><p class="text-[11px] text-[#8b5e3c] uppercase tracking-wider mb-1">Nombre</p><p class="text-[#2c1a0e]">{{ $pedido->nombre_cliente }}</p></div>
                        <div><p class="text-[11px] text-[#8b5e3c] uppercase tracking-wider mb-1">Email</p><p class="text-[#2c1a0e]">{{ $pedido->email_cliente }}</p></div>
                        <div><p class="text-[11px] text-[#8b5e3c] uppercase tracking-wider mb-1">Teléfono</p><p class="text-[#2c1a0e]">{{ $pedido->telefono_cliente }}</p></div>
                        <div><p class="text-[11px] text-[#8b5e3c] uppercase tracking-wider mb-1">Entrega</p>
                            <p class="text-[#2c1a0e]">{{ $pedido->metodo_entrega === 'envio' ? 'Envío a domicilio' : 'Retiro en local' }}</p>
                            @if($pedido->metodo_entrega === 'envio')<p class="text-xs text-[#8b5e3c]/70 mt-0.5">{{ $pedido->direccion_envio }}</p>@endif
                        </div>
                        <div><p class="text-[11px] text-[#8b5e3c] uppercase tracking-wider mb-1">Pago</p><p class="text-[#2c1a0e]">{{ $pedido->metodo_pago === 'transferencia' ? 'Transferencia bancaria' : 'Efectivo' }}</p></div>
                    </div>
                    @if($pedido->notas_cliente)
                        <div class="mt-4 pt-4 border-t border-[#d4b896]/30">
                            <p class="text-[11px] text-[#8b5e3c] uppercase tracking-wider mb-1">Notas del cliente</p>
                            <p class="text-sm text-[#2c1a0e]/80">{{ $pedido->notas_cliente }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Columna derecha: gestión --}}
            <div class="space-y-5">

                {{-- Cambiar estado --}}
                <div class="bg-white border border-[#d4b896]/30 p-6">
                    <h2 class="text-base font-medium text-[#2c1a0e] mb-4" style="font-family:'DM Serif Display',serif;">Gestión</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5">Estado del pedido</label>
                            <select wire:model="estado"
                                    class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-3 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
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
                                <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5">Costo de envío ($)</label>
                                <input wire:model="costo_envio" type="number" min="0" step="0.01"
                                       placeholder="0.00"
                                       class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-3 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
                                @error('costo_envio') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        <div>
                            <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5">Notas internas</label>
                            <textarea wire:model="notas_admin" rows="3"
                                      placeholder="Notas visibles solo para el admin..."
                                      class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-3 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors resize-none"></textarea>
                        </div>

                        @if($guardado)
                            <div class="flex items-center gap-2 text-[#386641] text-sm bg-[#386641]/8 p-3">
                                <i class="fa-solid fa-check"></i> Cambios guardados
                            </div>
                        @endif

                        <button wire:click="guardar"
                                wire:loading.attr="disabled"
                                class="w-full bg-[#386641] text-[#faf6f0] py-2.5 text-[13px] tracking-wider font-medium
                                       hover:bg-[#2d5534] transition-colors duration-300 disabled:opacity-60">
                            <span wire:loading.remove wire:target="guardar">Guardar cambios</span>
                            <span wire:loading wire:target="guardar">Guardando...</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
