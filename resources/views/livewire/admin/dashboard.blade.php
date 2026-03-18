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
            <h1 class="text-4xl text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">Dashboard</h1>
        </div>

        {{-- Tarjetas de estadísticas --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">

            <div class="bg-white rounded-2xl shadow-sm border border-[#d4b896]/20 p-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background-color: rgba(56,102,65,0.1);">
                    <i class="fa-solid fa-receipt text-sm" style="color: #386641;"></i>
                </div>
                <p class="text-[10px] tracking-wider text-[#8b5e3c] uppercase mb-1">Total pedidos</p>
                <p class="text-3xl font-bold text-[#2c1a0e]">{{ $estadisticas['total_pedidos'] }}</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-[#d4b896]/20 p-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300
                        {{ $estadisticas['pendientes'] > 0 ? 'border-l-4 border-l-[#8b5e3c]' : '' }}">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background-color: rgba(139,94,60,0.1);">
                    <i class="fa-solid fa-clock text-sm" style="color: #8b5e3c;"></i>
                </div>
                <p class="text-[10px] tracking-wider text-[#8b5e3c] uppercase mb-1">Pendientes</p>
                <p class="text-3xl font-bold {{ $estadisticas['pendientes'] > 0 ? 'text-[#8b5e3c]' : 'text-[#2c1a0e]' }}">
                    {{ $estadisticas['pendientes'] }}
                </p>
                @if($estadisticas['pendientes'] > 0)
                    <p class="text-[10px] text-[#8b5e3c]/60 mt-1">Requieren atención</p>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-[#d4b896]/20 p-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background-color: rgba(167,201,87,0.15);">
                    <i class="fa-solid fa-calendar-day text-sm" style="color: #6a9c2a;"></i>
                </div>
                <p class="text-[10px] tracking-wider text-[#8b5e3c] uppercase mb-1">Hoy</p>
                <p class="text-3xl font-bold text-[#2c1a0e]">{{ $estadisticas['hoy'] }}</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-[#d4b896]/20 p-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300
                        {{ $estadisticas['stock_bajo'] > 0 ? 'border-l-4 border-l-red-400' : '' }}">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3 {{ $estadisticas['stock_bajo'] > 0 ? 'bg-red-50' : '' }}" style="{{ $estadisticas['stock_bajo'] == 0 ? 'background-color: rgba(56,102,65,0.1);' : '' }}">
                    <i class="fa-solid fa-box-open text-sm {{ $estadisticas['stock_bajo'] > 0 ? 'text-red-500' : '' }}" style="{{ $estadisticas['stock_bajo'] == 0 ? 'color: #386641;' : '' }}"></i>
                </div>
                <p class="text-[10px] tracking-wider text-[#8b5e3c] uppercase mb-1">Stock bajo</p>
                <p class="text-3xl font-bold {{ $estadisticas['stock_bajo'] > 0 ? 'text-red-500' : 'text-[#2c1a0e]' }}">
                    {{ $estadisticas['stock_bajo'] }}
                </p>
                @if($estadisticas['stock_bajo'] > 0)
                    <p class="text-[10px] text-red-400/80 mt-1">≤ 3 unidades</p>
                @endif
            </div>

            <div class="col-span-2 lg:col-span-1 rounded-2xl shadow-sm p-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300"
                 style="background: linear-gradient(135deg, #386641 0%, #2d5534 100%);">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background-color: rgba(255,255,255,0.15);">
                    <i class="fa-solid fa-chart-line text-sm text-white"></i>
                </div>
                <p class="text-[10px] tracking-wider uppercase mb-1" style="color: rgba(250,246,240,0.7);">Ingresos del mes</p>
                <p class="text-2xl font-bold text-white">${{ number_format($estadisticas['ingresos_mes'], 0, ',', '.') }}</p>
                <p class="text-[10px] mt-1" style="color: rgba(250,246,240,0.5);">Pedidos activos</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Últimos pedidos --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-[#d4b896]/20 overflow-hidden">
                <div class="px-6 py-4 border-b border-[#d4b896]/20 flex items-center justify-between">
                    <h2 class="text-base text-[#2c1a0e] flex items-center gap-2" style="font-family:'DM Serif Display',serif;">
                        <i class="fa-solid fa-clock-rotate-left text-sm text-[#8b5e3c]/50"></i>
                        Últimos pedidos
                    </h2>
                    <a href="{{ route('admin.pedidos') }}" class="text-[12px] text-[#386641] hover:text-[#2d5534] font-medium transition-colors flex items-center gap-1">
                        Ver todos <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </a>
                </div>
                <table class="w-full text-sm">
                    <tbody>
                        @forelse($ultimosPedidos as $pedido)
                            <tr class="border-b border-[#d4b896]/15 hover:bg-[#faf6f0]/70 transition-colors duration-150">
                                <td class="px-5 py-3.5">
                                    <p class="font-semibold text-[#2c1a0e] text-[12px]">{{ $pedido->numero_pedido }}</p>
                                    <p class="text-[10px] text-[#8b5e3c]/60">{{ $pedido->created_at->format('d/m H:i') }}</p>
                                </td>
                                <td class="px-4 py-3.5 text-[12px] text-[#2c1a0e]/70">{{ $pedido->nombre_cliente }}</td>
                                <td class="px-4 py-3.5 text-right text-[12px] font-semibold text-[#2c1a0e]">
                                    ${{ number_format($pedido->total, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3.5 text-right">
                                    <span class="inline-block px-2.5 py-1 text-[10px] font-semibold rounded-full text-white shadow-sm"
                                          style="background-color: {{ $pedido->colorEstado() }}">
                                        {{ $pedido->etiquetaEstado() }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('admin.detalle-pedido', $pedido->id) }}"
                                       class="text-[#386641] text-[11px] hover:text-[#2d5534] font-medium transition-colors flex items-center gap-1 justify-end">
                                        Ver <i class="fa-solid fa-chevron-right text-[8px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center">
                                    <i class="fa-solid fa-inbox text-3xl text-[#d4b896] mb-3 block"></i>
                                    <p class="text-[#8b5e3c]/60 text-sm">No hay pedidos aún.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Productos con stock bajo --}}
            <div class="bg-white rounded-2xl shadow-sm border border-[#d4b896]/20 overflow-hidden">
                <div class="px-6 py-4 border-b border-[#d4b896]/20 flex items-center justify-between">
                    <h2 class="text-base text-[#2c1a0e] flex items-center gap-2" style="font-family:'DM Serif Display',serif;">
                        <i class="fa-solid fa-triangle-exclamation text-sm text-[#8b5e3c]/50"></i>
                        Stock bajo
                    </h2>
                    <a href="{{ route('admin.productos') }}" class="text-[12px] text-[#386641] hover:text-[#2d5534] font-medium transition-colors flex items-center gap-1">
                        Gestionar <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </a>
                </div>
                @forelse($productosBajoStock as $producto)
                    <div class="px-5 py-3.5 border-b border-[#d4b896]/15 flex items-center justify-between hover:bg-[#faf6f0]/70 transition-colors duration-150">
                        <div>
                            <p class="text-[12px] font-medium text-[#2c1a0e]">{{ $producto->nombre }}</p>
                            <p class="text-[10px] text-[#8b5e3c]/60">{{ $producto->unidad }}</p>
                        </div>
                        <span class="text-sm font-bold px-2 py-0.5 rounded-lg {{ $producto->stock === 0 ? 'bg-red-50 text-red-600' : 'text-[#8b5e3c]' }}">
                            {{ $producto->stock }}
                        </span>
                    </div>
                @empty
                    <div class="px-5 py-12 text-center">
                        <i class="fa-solid fa-circle-check text-3xl mb-3 block" style="color: #a7c957;"></i>
                        <p class="text-[#8b5e3c]/60 text-sm">Todo el stock está en orden.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Gráfico de ventas últimos 7 días --}}
        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-[#d4b896]/20 overflow-hidden">
            <div class="px-6 py-4 border-b border-[#d4b896]/20 flex items-center justify-between">
                <h2 class="text-base text-[#2c1a0e] flex items-center gap-2" style="font-family:'DM Serif Display',serif;">
                    <i class="fa-solid fa-chart-line text-sm text-[#8b5e3c]/50"></i>
                    Actividad — últimos 7 días
                </h2>
            </div>
            <div class="p-6">
                <canvas id="graficoVentas" height="80"></canvas>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('graficoVentas').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($datosGrafico->pluck('fecha')) !!},
            datasets: [
                {
                    label: 'Pedidos',
                    data: {!! json_encode($datosGrafico->pluck('pedidos')) !!},
                    backgroundColor: 'rgba(56,102,65,0.15)',
                    borderColor: '#386641',
                    borderWidth: 2,
                    borderRadius: 6,
                    yAxisID: 'yPedidos',
                },
                {
                    label: 'Ingresos ($)',
                    data: {!! json_encode($datosGrafico->pluck('ingresos')) !!},
                    backgroundColor: 'rgba(167,201,87,0.15)',
                    borderColor: '#a7c957',
                    borderWidth: 2,
                    borderRadius: 6,
                    type: 'line',
                    yAxisID: 'yIngresos',
                    tension: 0.4,
                    pointBackgroundColor: '#a7c957',
                    pointRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    labels: { font: { family: 'Raleway', size: 11 }, color: '#8b5e3c' }
                }
            },
            scales: {
                yPedidos: {
                    type: 'linear',
                    position: 'left',
                    ticks: { font: { family: 'Raleway', size: 10 }, color: '#8b5e3c', stepSize: 1 },
                    grid: { color: 'rgba(212,184,150,0.15)' },
                },
                yIngresos: {
                    type: 'linear',
                    position: 'right',
                    ticks: { font: { family: 'Raleway', size: 10 }, color: '#8b5e3c' },
                    grid: { display: false },
                },
                x: {
                    ticks: { font: { family: 'Raleway', size: 10 }, color: '#8b5e3c' },
                    grid: { display: false },
                }
            }
        }
    });
</script>
@endpush
