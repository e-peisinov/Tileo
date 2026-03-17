<div class="min-h-screen bg-[#faf6f0] py-10 px-4">
    <div class="max-w-6xl mx-auto">

        {{-- Encabezado --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <p class="text-[#8b5e3c]/70 tracking-[0.25em] uppercase text-[10px] font-medium mb-1">Panel de administración</p>
                <h1 class="text-3xl text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">Pedidos</h1>
            </div>
            <nav class="flex gap-3 text-[12px]">
                <a href="{{ route('admin.productos') }}" class="border border-[#d4b896]/50 text-[#8b5e3c] px-4 py-2 hover:border-[#386641] hover:text-[#386641] transition-colors">Productos</a>
                <a href="{{ route('admin.categorias') }}" class="border border-[#d4b896]/50 text-[#8b5e3c] px-4 py-2 hover:border-[#386641] hover:text-[#386641] transition-colors">Categorías</a>
            </nav>
        </div>

        {{-- Filtros --}}
        <div class="flex flex-col sm:flex-row gap-3 mb-6">
            <input wire:model.live.debounce.300ms="busqueda" type="text"
                   placeholder="Buscar por número, nombre o email..."
                   class="flex-1 border border-[#d4b896]/50 bg-white px-4 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
            <select wire:model.live="filtroEstado"
                    class="border border-[#d4b896]/50 bg-white px-4 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
                <option value="">Todos los estados</option>
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

        {{-- Tabla --}}
        <div class="bg-white border border-[#d4b896]/30 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-[#f0e9de] border-b border-[#d4b896]/40">
                    <tr>
                        <th class="text-left px-4 py-3 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-medium">Pedido</th>
                        <th class="text-left px-4 py-3 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-medium">Cliente</th>
                        <th class="text-left px-4 py-3 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-medium hidden md:table-cell">Entrega</th>
                        <th class="text-left px-4 py-3 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-medium hidden lg:table-cell">Total</th>
                        <th class="text-left px-4 py-3 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-medium">Estado</th>
                        <th class="text-left px-4 py-3 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidos as $pedido)
                        <tr class="border-b border-[#d4b896]/20 hover:bg-[#faf6f0] transition-colors">
                            <td class="px-4 py-3">
                                <p class="font-medium text-[#2c1a0e]">{{ $pedido->numero_pedido }}</p>
                                <p class="text-[11px] text-[#8b5e3c]/60">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-[#2c1a0e]">{{ $pedido->nombre_cliente }}</p>
                                <p class="text-[11px] text-[#8b5e3c]/60">{{ $pedido->email_cliente }}</p>
                            </td>
                            <td class="px-4 py-3 hidden md:table-cell text-[#2c1a0e]/70">
                                {{ $pedido->metodo_entrega === 'envio' ? 'Envío' : 'Retiro' }}
                            </td>
                            <td class="px-4 py-3 hidden lg:table-cell font-medium text-[#2c1a0e]">
                                ${{ number_format($pedido->total, 2, ',', '.') }}
                                @if($pedido->metodo_entrega === 'envio' && is_null($pedido->costo_envio))
                                    <span class="text-[10px] text-[#8b5e3c]/60 block">+ envío a confirmar</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2.5 py-1 text-[10px] font-medium rounded-full text-white"
                                      style="background-color: {{ $pedido->colorEstado() }}">
                                    {{ $pedido->etiquetaEstado() }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.detalle-pedido', $pedido->id) }}"
                                   class="text-[#386641] text-[12px] hover:underline">Ver detalle</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-12 text-center text-[#8b5e3c]/60">No se encontraron pedidos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $pedidos->links() }}</div>
    </div>
</div>
