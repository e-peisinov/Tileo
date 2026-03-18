<div class="min-h-screen bg-[#faf6f0] py-10 px-4">
    <div class="max-w-6xl mx-auto">

        {{-- Encabezado --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <p class="text-[#8b5e3c]/70 tracking-[0.25em] uppercase text-[10px] font-medium mb-1">Panel de administración</p>
                <h1 class="text-3xl text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">Dashboard</h1>
            </div>
        </div>

        {{-- Tarjetas de estadísticas --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-white border border-[#d4b896]/30 p-5">
                <p class="text-[10px] tracking-wider text-[#8b5e3c] uppercase mb-2">Total pedidos</p>
                <p class="text-3xl font-semibold text-[#2c1a0e]">{{ $estadisticas['total_pedidos'] }}</p>
            </div>
            <div class="bg-white border border-[#d4b896]/30 p-5">
                <p class="text-[10px] tracking-wider text-[#8b5e3c] uppercase mb-2">Pendientes</p>
                <p class="text-3xl font-semibold {{ $estadisticas['pendientes'] > 0 ? 'text-[#8b5e3c]' : 'text-[#2c1a0e]' }}">
                    {{ $estadisticas['pendientes'] }}
                </p>
                @if($estadisticas['pendientes'] > 0)
                    <p class="text-[10px] text-[#8b5e3c]/60 mt-1">Requieren atención</p>
                @endif
            </div>
            <div class="bg-white border border-[#d4b896]/30 p-5">
                <p class="text-[10px] tracking-wider text-[#8b5e3c] uppercase mb-2">Hoy</p>
                <p class="text-3xl font-semibold text-[#2c1a0e]">{{ $estadisticas['hoy'] }}</p>
            </div>
            <div class="bg-white border border-[#d4b896]/30 p-5">
                <p class="text-[10px] tracking-wider text-[#8b5e3c] uppercase mb-2">Stock bajo</p>
                <p class="text-3xl font-semibold {{ $estadisticas['stock_bajo'] > 0 ? 'text-red-600' : 'text-[#2c1a0e]' }}">
                    {{ $estadisticas['stock_bajo'] }}
                </p>
                @if($estadisticas['stock_bajo'] > 0)
                    <p class="text-[10px] text-red-500/70 mt-1">≤ 3 unidades</p>
                @endif
            </div>
            <div class="col-span-2 lg:col-span-1 bg-[#386641] p-5">
                <p class="text-[10px] tracking-wider text-[#faf6f0]/70 uppercase mb-2">Ingresos del mes</p>
                <p class="text-2xl font-semibold text-[#faf6f0]">${{ number_format($estadisticas['ingresos_mes'], 0, ',', '.') }}</p>
                <p class="text-[10px] text-[#faf6f0]/50 mt-1">Pedidos activos</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Últimos pedidos --}}
            <div class="lg:col-span-2 bg-white border border-[#d4b896]/30">
                <div class="px-6 py-4 border-b border-[#d4b896]/30 flex items-center justify-between">
                    <h2 class="text-base text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">Últimos pedidos</h2>
                    <a href="{{ route('admin.pedidos') }}" class="text-[12px] text-[#386641] hover:underline">Ver todos</a>
                </div>
                <table class="w-full text-sm">
                    <tbody>
                        @forelse($ultimosPedidos as $pedido)
                            <tr class="border-b border-[#d4b896]/20 hover:bg-[#faf6f0] transition-colors">
                                <td class="px-4 py-3">
                                    <p class="font-medium text-[#2c1a0e] text-[12px]">{{ $pedido->numero_pedido }}</p>
                                    <p class="text-[10px] text-[#8b5e3c]/60">{{ $pedido->created_at->format('d/m H:i') }}</p>
                                </td>
                                <td class="px-4 py-3 text-[12px] text-[#2c1a0e]/70">{{ $pedido->nombre_cliente }}</td>
                                <td class="px-4 py-3 text-right text-[12px] font-medium text-[#2c1a0e]">
                                    ${{ number_format($pedido->total, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span class="inline-block px-2 py-0.5 text-[10px] font-medium rounded-full text-white"
                                          style="background-color: {{ $pedido->colorEstado() }}">
                                        {{ $pedido->etiquetaEstado() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.detalle-pedido', $pedido->id) }}"
                                       class="text-[#386641] text-[11px] hover:underline">Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-10 text-center text-[#8b5e3c]/60 text-sm">No hay pedidos aún.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Productos con stock bajo --}}
            <div class="bg-white border border-[#d4b896]/30">
                <div class="px-6 py-4 border-b border-[#d4b896]/30 flex items-center justify-between">
                    <h2 class="text-base text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">Stock bajo</h2>
                    <a href="{{ route('admin.productos') }}" class="text-[12px] text-[#386641] hover:underline">Gestionar</a>
                </div>
                @forelse($productosBajoStock as $producto)
                    <div class="px-5 py-3 border-b border-[#d4b896]/20 flex items-center justify-between">
                        <div>
                            <p class="text-[12px] font-medium text-[#2c1a0e]">{{ $producto->nombre }}</p>
                            <p class="text-[10px] text-[#8b5e3c]/60">{{ $producto->unidad }}</p>
                        </div>
                        <span class="text-sm font-bold {{ $producto->stock === 0 ? 'text-red-600' : 'text-[#8b5e3c]' }}">
                            {{ $producto->stock }}
                        </span>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-[#8b5e3c]/60 text-sm">
                        <i class="fa-solid fa-check text-[#386641] text-xl mb-2 block"></i>
                        Todo el stock está en orden.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
