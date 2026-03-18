<div class="min-h-screen py-10 px-4" style="background: linear-gradient(150deg, #faf6f0 0%, #f0e9de 100%);">
    <div class="max-w-6xl mx-auto">

        {{-- Nav admin --}}
        <nav class="flex flex-wrap gap-1 mb-8 bg-white/70 backdrop-blur-sm rounded-2xl p-1.5 border border-[#d4b896]/25 shadow-sm w-fit">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200
                      {{ request()->routeIs('admin.dashboard') ? 'bg-[#386641] text-white shadow-sm' : 'text-[#8b5e3c] hover:bg-[#f0e9de] hover:text-[#2c1a0e]' }}">
                <i class="fa-solid fa-gauge-high text-[10px]"></i> Dashboard
            </a>
            <a href="{{ route('admin.pedidos') }}"
               class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200
                      {{ request()->routeIs('admin.pedidos', 'admin.detalle-pedido') ? 'bg-[#386641] text-white shadow-sm' : 'text-[#8b5e3c] hover:bg-[#f0e9de] hover:text-[#2c1a0e]' }}">
                <i class="fa-solid fa-bag-shopping text-[10px]"></i> Pedidos
            </a>
            <a href="{{ route('admin.productos') }}"
               class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200
                      {{ request()->routeIs('admin.productos') ? 'bg-[#386641] text-white shadow-sm' : 'text-[#8b5e3c] hover:bg-[#f0e9de] hover:text-[#2c1a0e]' }}">
                <i class="fa-solid fa-seedling text-[10px]"></i> Productos
            </a>
            <a href="{{ route('admin.categorias') }}"
               class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200
                      {{ request()->routeIs('admin.categorias') ? 'bg-[#386641] text-white shadow-sm' : 'text-[#8b5e3c] hover:bg-[#f0e9de] hover:text-[#2c1a0e]' }}">
                <i class="fa-solid fa-tags text-[10px]"></i> Categorías
            </a>
        </nav>

        {{-- Encabezado --}}
        <div class="mb-8">
            <p class="text-[#8b5e3c]/60 tracking-[0.25em] uppercase text-[10px] font-semibold mb-1">Panel de administración</p>
            <h1 class="text-4xl text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">Pedidos</h1>
        </div>

        {{-- Filtros --}}
        <div class="flex flex-col sm:flex-row gap-3 mb-6 justify-between">
            <div class="relative sm:w-1/3">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-[#8b5e3c]/40 text-xs"></i>
                <input wire:model.live.debounce.300ms="busqueda" type="text"
                       placeholder="Buscar por número, nombre o email..."
                       class="w-full border border-[#d4b896]/40 bg-white rounded-xl pl-9 pr-4 py-2.5 text-sm text-[#2c1a0e] shadow-sm
                              focus:outline-none focus:ring-2 focus:ring-[#386641]/20 focus:border-[#386641] transition-all duration-200">
            </div>
            <div class="relative">
                <i class="fa-solid fa-filter absolute left-3.5 top-1/2 -translate-y-1/2 text-[#8b5e3c]/40 text-xs pointer-events-none"></i>
                <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-[#8b5e3c]/40 text-[10px] pointer-events-none"></i>
                <select wire:model.live="filtroEstado"
                        class="w-56 border border-[#d4b896]/40 bg-white rounded-xl pl-9 pr-9 py-2.5 text-sm text-[#2c1a0e] shadow-sm
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

        {{-- Tabla --}}
        <div class="bg-white rounded-2xl shadow-sm border border-[#d4b896]/20 overflow-hidden">
            <table class="w-full text-sm">
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

        <div class="mt-5">{{ $pedidos->links() }}</div>
    </div>
</div>
