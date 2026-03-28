<div>
    {{-- Encabezado --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <p class="text-[#8b5e3c]/60 tracking-[0.25em] uppercase text-[10px] font-semibold mb-1">Sistema</p>
            <h1 class="text-3xl text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">Reportes</h1>
        </div>
        <button wire:click="exportarCsv"
                class="flex items-center gap-2 border border-[#386641]/40 text-[#386641] px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#386641] hover:text-white transition-all duration-200">
            <i class="fa-solid fa-download text-xs"></i> Exportar CSV
        </button>
    </div>

    {{-- Alerta de fechas invertidas --}}
    @if($fechaDesde && $fechaHasta && $fechaDesde > $fechaHasta)
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
            <i class="fa-solid fa-triangle-exclamation mr-1"></i>
            La fecha "Desde" no puede ser mayor que "Hasta".
        </div>
    @endif

    {{-- Filtro de fechas --}}
    <div class="bg-white rounded-2xl border border-[#d4b896]/30 shadow-sm p-5 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <label class="text-xs text-[#8b5e3c] font-medium">Desde:</label>
                <input wire:model.live="fechaDesde" type="date"
                       class="border border-[#d4b896]/50 bg-[#faf6f0] rounded-lg px-3 py-1.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-xs text-[#8b5e3c] font-medium">Hasta:</label>
                <input wire:model.live="fechaHasta" type="date"
                       class="border border-[#d4b896]/50 bg-[#faf6f0] rounded-lg px-3 py-1.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
            </div>
            <div class="flex gap-2 ml-auto">
                <button @click="$wire.set('fechaDesde', '{{ now()->format('Y-m-d') }}'); $wire.set('fechaHasta', '{{ now()->format('Y-m-d') }}');"
                        class="text-xs text-[#8b5e3c] border border-[#d4b896]/40 px-3 py-1.5 rounded-lg hover:bg-[#f0e9de] transition-colors">
                    Hoy
                </button>
                <button wire:click="$set('fechaDesde', '{{ now()->startOfMonth()->format('Y-m-d') }}')"
                        class="text-xs text-[#8b5e3c] border border-[#d4b896]/40 px-3 py-1.5 rounded-lg hover:bg-[#f0e9de] transition-colors">
                    Este mes
                </button>
                <button wire:click="$set('fechaDesde', '{{ now()->startOfYear()->format('Y-m-d') }}')"
                        class="text-xs text-[#8b5e3c] border border-[#d4b896]/40 px-3 py-1.5 rounded-lg hover:bg-[#f0e9de] transition-colors">
                    Este año
                </button>
            </div>
        </div>
    </div>

    {{-- Tarjetas resumen --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-[#d4b896]/30 shadow-sm p-5">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background-color: rgba(56,102,65,0.1);">
                <i class="fa-solid fa-dollar-sign text-sm" style="color: #386641;"></i>
            </div>
            <p class="text-[10px] tracking-wider text-[#8b5e3c] uppercase mb-1">Ingresos</p>
            <p class="text-3xl font-bold text-[#2c1a0e]">${{ number_format($totalIngresos, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-[#d4b896]/30 shadow-sm p-5">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background-color: rgba(139,94,60,0.1);">
                <i class="fa-solid fa-receipt text-sm" style="color: #8b5e3c;"></i>
            </div>
            <p class="text-[10px] tracking-wider text-[#8b5e3c] uppercase mb-1">Pedidos</p>
            <p class="text-3xl font-bold text-[#2c1a0e]">{{ $totalPedidos }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-[#d4b896]/30 shadow-sm p-5">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background-color: rgba(167,201,87,0.15);">
                <i class="fa-solid fa-ticket text-sm" style="color: #a7c957;"></i>
            </div>
            <p class="text-[10px] tracking-wider text-[#8b5e3c] uppercase mb-1">Ticket promedio</p>
            <p class="text-3xl font-bold text-[#2c1a0e]">${{ number_format($ticketPromedio, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Ventas por categoría --}}
        <div class="bg-white rounded-2xl border border-[#d4b896]/30 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-[#d4b896]/20" style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                <h3 class="text-base text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">
                    <i class="fa-solid fa-tags text-sm text-[#8b5e3c]/50 mr-2"></i>Ventas por categoría
                </h3>
            </div>
            <div class="p-5">
                @if($porCategoria->isEmpty())
                    <p class="text-sm text-[#8b5e3c]/60 text-center py-8">Sin datos en el período seleccionado.</p>
                @else
                    <div class="space-y-3">
                        @foreach($porCategoria as $cat)
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center gap-2 flex-1 min-w-0">
                                    <div class="w-2 h-2 rounded-full bg-[#386641] flex-shrink-0"></div>
                                    <span class="text-[#2c1a0e] truncate">{{ $cat->categoria }}</span>
                                </div>
                                <div class="flex items-center gap-4 flex-shrink-0 ml-3">
                                    <span class="text-[#8b5e3c]/60 text-xs">{{ $cat->total_unidades }} u.</span>
                                    <span class="font-semibold text-[#386641]">${{ number_format($cat->total_ingresos, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($totalIngresos > 0)
                        <div class="mt-5 pt-5 border-t border-[#d4b896]/20">
                            <canvas id="graficoCategoria" height="180"></canvas>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        {{-- Top productos --}}
        <div class="bg-white rounded-2xl border border-[#d4b896]/30 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-[#d4b896]/20" style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                <h3 class="text-base text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">
                    <i class="fa-solid fa-trophy text-sm text-[#8b5e3c]/50 mr-2"></i>Top 10 productos
                </h3>
            </div>
            <div class="p-5">
                @if($topProductos->isEmpty())
                    <p class="text-sm text-[#8b5e3c]/60 text-center py-8">Sin datos en el período seleccionado.</p>
                @else
                    <div class="space-y-2.5">
                        @foreach($topProductos as $i => $prod)
                            <div class="flex items-center gap-3 text-sm">
                                <span class="w-6 h-6 rounded-full text-[10px] font-bold flex items-center justify-center flex-shrink-0
                                             {{ $i === 0 ? 'bg-amber-100 text-amber-700' : ($i === 1 ? 'bg-[#d4b896]/30 text-[#8b5e3c]' : ($i === 2 ? 'bg-[#f0e9de] text-[#8b5e3c]' : 'bg-[#faf6f0] text-[#8b5e3c]/60')) }}">
                                    {{ $i + 1 }}
                                </span>
                                <span class="flex-1 text-[#2c1a0e] truncate">{{ $prod->nombre_producto }}</span>
                                <span class="text-[#8b5e3c]/60 text-xs flex-shrink-0">{{ $prod->total_vendido }} u.</span>
                                <span class="font-semibold text-[#386641] flex-shrink-0">${{ number_format($prod->total_ingresos, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('livewire:navigated', initChart);
document.addEventListener('DOMContentLoaded', initChart);

function initChart() {
    const canvas = document.getElementById('graficoCategoria');
    if (!canvas) return;
    if (canvas._chartInstance) canvas._chartInstance.destroy();

    const labels = @json($porCategoria->pluck('categoria'));
    const data   = @json($porCategoria->pluck('total_ingresos'));
    const colors = ['#386641','#8b5e3c','#a7c957','#d4b896','#2c1a0e','#6b9c74','#b8874a'];

    canvas._chartInstance = new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data,
                backgroundColor: colors.slice(0, labels.length),
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 12 } }
            }
        }
    });
}
</script>
@endpush
