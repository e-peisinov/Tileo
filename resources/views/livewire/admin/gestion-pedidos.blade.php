<div class="min-h-screen py-10 px-4" style="background: linear-gradient(150deg, #faf6f0 0%, #f0e9de 100%);">
    <div class="max-w-6xl mx-auto">

        {{-- Nav admin --}}
        @include('livewire.admin.partials.nav')

        {{-- Encabezado --}}
        <div class="mb-8">
            <p class="text-[#8b5e3c]/60 tracking-[0.25em] uppercase text-[10px] font-semibold mb-1">Panel de administración</p>
            <h1 class="text-4xl text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">Pedidos</h1>
        </div>

        {{-- Filtros --}}
        <div class="flex flex-col gap-3 mb-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-[#8b5e3c]/40 text-xs"></i>
                    <input wire:model.live.debounce.300ms="busqueda" type="text"
                           placeholder="Buscar por número, nombre o email..."
                           class="w-full border border-[#d4b896]/40 bg-white rounded-xl pl-9 pr-4 py-2.5 text-sm text-[#2c1a0e] shadow-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#386641]/20 focus:border-[#386641] transition-all duration-200">
                </div>
                <div class="relative sm:w-52">
                    <i class="fa-solid fa-filter absolute left-3.5 top-1/2 -translate-y-1/2 text-[#8b5e3c]/40 text-xs pointer-events-none"></i>
                    <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-[#8b5e3c]/40 text-[10px] pointer-events-none"></i>
                    <select wire:model.live="filtroEstado"
                            class="w-full border border-[#d4b896]/40 bg-white rounded-xl pl-9 pr-9 py-2.5 text-sm text-[#2c1a0e] shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-[#386641]/20 focus:border-[#386641] transition-all duration-200 appearance-none">
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
            </div>

            <div class="flex flex-col sm:flex-row gap-3 items-end">
                <div class="flex items-center gap-2 flex-1">
                    <div class="flex-1">
                        <label class="block text-[10px] tracking-wider text-[#8b5e3c] uppercase mb-1 font-semibold">Desde</label>
                        <input wire:model.live="filtroFechaDesde" type="date"
                               class="w-full border border-[#d4b896]/40 bg-white rounded-xl px-3 py-2.5 text-sm text-[#2c1a0e] shadow-sm
                                      focus:outline-none focus:ring-2 focus:ring-[#386641]/20 focus:border-[#386641] transition-all duration-200">
                    </div>
                    <div class="flex-1">
                        <label class="block text-[10px] tracking-wider text-[#8b5e3c] uppercase mb-1 font-semibold">Hasta</label>
                        <input wire:model.live="filtroFechaHasta" type="date"
                               class="w-full border border-[#d4b896]/40 bg-white rounded-xl px-3 py-2.5 text-sm text-[#2c1a0e] shadow-sm
                                      focus:outline-none focus:ring-2 focus:ring-[#386641]/20 focus:border-[#386641] transition-all duration-200">
                    </div>
                </div>
                <button wire:click="exportarCsv"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 border border-[#386641]/30 text-[#386641] rounded-xl px-4 py-2.5 text-[12px] font-semibold
                               hover:bg-[#386641] hover:text-white transition-all duration-200 shadow-sm disabled:opacity-60 whitespace-nowrap">
                    <span wire:loading.remove wire:target="exportarCsv">
                        <i class="fa-solid fa-file-csv text-xs"></i> Exportar CSV
                    </span>
                    <span wire:loading wire:target="exportarCsv" class="flex items-center gap-2">
                        <i class="fa-solid fa-spinner fa-spin text-xs"></i> Exportando...
                    </span>
                </button>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="bg-white rounded-2xl shadow-sm border border-[#d4b896]/20 overflow-hidden">
            <div class="overflow-x-auto">
            <table class="w-full min-w-[520px] text-sm">
                <thead>
                    <tr style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                        <th class="text-left px-5 py-3.5 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-semibold border-b border-[#d4b896]/30">Pedido</th>
                        <th class="text-left px-4 py-3.5 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-semibold border-b border-[#d4b896]/30">Cliente</th>
                        <th class="text-left px-4 py-3.5 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-semibold border-b border-[#d4b896]/30 hidden md:table-cell">Entrega</th>
                        <th class="text-left px-4 py-3.5 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-semibold border-b border-[#d4b896]/30 hidden lg:table-cell">Total</th>
                        <th class="text-left px-4 py-3.5 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-semibold border-b border-[#d4b896]/30">Estado</th>
                        <th class="text-left px-5 py-3.5 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-semibold border-b border-[#d4b896]/30">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidos as $pedido)
                        <tr class="border-b border-[#d4b896]/15 hover:bg-[#faf6f0]/70 transition-colors duration-150 group">
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-[#2c1a0e]">{{ $pedido->numero_pedido }}</p>
                                <p class="text-[11px] text-[#8b5e3c]/60">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                            </td>
                            <td class="px-4 py-3.5">
                                <p class="text-[#2c1a0e] font-medium">{{ $pedido->nombre_cliente }}</p>
                                <p class="text-[11px] text-[#8b5e3c]/60">{{ $pedido->email_cliente }}</p>
                            </td>
                            <td class="px-4 py-3.5 hidden md:table-cell text-[#2c1a0e]/70">
                                <span class="inline-flex items-center gap-1">
                                    <i class="fa-solid {{ $pedido->metodo_entrega === 'envio' ? 'fa-truck' : 'fa-store' }} text-[10px] text-[#8b5e3c]/50"></i>
                                    {{ $pedido->metodo_entrega === 'envio' ? 'Envío' : 'Retiro' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 hidden lg:table-cell font-semibold text-[#2c1a0e]">
                                ${{ number_format($pedido->total, 2, ',', '.') }}
                                @if($pedido->metodo_entrega === 'envio' && is_null($pedido->costo_envio))
                                    <span class="text-[10px] text-[#8b5e3c]/60 block font-normal">+ envío a confirmar</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="inline-block px-2.5 py-1 text-[10px] font-semibold rounded-full text-white shadow-sm"
                                      style="background-color: {{ $pedido->colorEstado() }}">
                                    {{ $pedido->etiquetaEstado() }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <a href="{{ route('admin.detalle-pedido', $pedido->id) }}"
                                   class="inline-flex items-center gap-1.5 text-[#386641] text-[12px] font-medium hover:text-[#2d5534] transition-colors group-hover:underline">
                                    Ver detalle <i class="fa-solid fa-chevron-right text-[9px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-14 text-center">
                                <i class="fa-solid fa-inbox text-4xl text-[#d4b896] mb-3 block"></i>
                                <p class="text-[#8b5e3c]/60">No se encontraron pedidos.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

        <div class="mt-5">{{ $pedidos->links() }}</div>
    </div>
</div>
