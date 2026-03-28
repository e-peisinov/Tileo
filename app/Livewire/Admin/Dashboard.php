<?php

namespace App\Livewire\Admin;

use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Producto;
use Livewire\Component;

class Dashboard extends Component
{
    public string $fechaDesde = '';
    public string $fechaHasta = '';

    private function pedidosConFiltro()
    {
        return Pedido::query()
            ->when($this->fechaDesde, fn($q) => $q->whereDate('created_at', '>=', $this->fechaDesde))
            ->when($this->fechaHasta, fn($q) => $q->whereDate('created_at', '<=', $this->fechaHasta));
    }

    public function render()
    {
        $base = $this->pedidosConFiltro();

        $estadisticas = [
            'total_pedidos' => (clone $base)->count(),
            'pendientes'    => (clone $base)->where('estado', 'pendiente')->count(),
            'hoy'           => Pedido::whereDate('created_at', today())->count(),
            'stock_bajo'    => Producto::where('activo', true)->where('stock', '<=', config('tileo.stock_bajo_umbral'))->count(),
            'ingresos_mes'  => (clone $base)->whereNotIn('estado', ['rechazado', 'cancelado'])->sum('total'),
        ];

        $ultimosPedidos = (clone $base)->latest()->limit(8)->get();

        $productosBajoStock = Producto::where('activo', true)
            ->where('stock', '<=', config('tileo.stock_bajo_umbral'))
            ->orderBy('stock')
            ->limit(10)
            ->get();

        $productosVendidos = PedidoItem::selectRaw('nombre_producto, SUM(cantidad) as total_vendido, SUM(subtotal) as total_ingresos')
            ->whereHas('pedido', function ($q) {
                $q->whereNotIn('estado', ['rechazado', 'cancelado'])
                  ->when($this->fechaDesde, fn($s) => $s->whereDate('created_at', '>=', $this->fechaDesde))
                  ->when($this->fechaHasta, fn($s) => $s->whereDate('created_at', '<=', $this->fechaHasta));
            })
            ->groupBy('nombre_producto')
            ->orderByDesc('total_vendido')
            ->limit(8)
            ->get();

        // Gráfico: si hay filtro de fechas, muestra ese rango (máx. 30 puntos); si no, últimos 7 días
        if ($this->fechaDesde && $this->fechaHasta) {
            $inicio = \Carbon\Carbon::parse($this->fechaDesde)->startOfDay();
            $fin    = \Carbon\Carbon::parse($this->fechaHasta)->endOfDay();
            $dias   = collect();
            for ($d = $inicio->copy(); $d->lte($fin); $d->addDay()) {
                $dias->push($d->copy()->startOfDay());
            }
            if ($dias->count() > 30) {
                $dias = collect(range(0, 29))->map(fn($i) => $inicio->copy()->addDays((int) round($i * ($dias->count() - 1) / 29)));
            }
        } else {
            $dias = collect(range(6, 0))->map(fn($diasAtras) => now()->subDays($diasAtras)->startOfDay());
        }

        // Una sola query agrupada por fecha reemplaza las N queries del loop
        $rangoInicio = $dias->first()->toDateString();
        $rangoFin    = $dias->last()->toDateString();

        $pedidosPorDia = Pedido::selectRaw('DATE(created_at) as dia, COUNT(*) as total_pedidos')
            ->whereDate('created_at', '>=', $rangoInicio)
            ->whereDate('created_at', '<=', $rangoFin)
            ->groupBy('dia')
            ->pluck('total_pedidos', 'dia');

        $ingresosPorDia = Pedido::selectRaw('DATE(created_at) as dia, SUM(total) as total_ingresos')
            ->whereDate('created_at', '>=', $rangoInicio)
            ->whereDate('created_at', '<=', $rangoFin)
            ->whereNotIn('estado', ['rechazado', 'cancelado'])
            ->groupBy('dia')
            ->pluck('total_ingresos', 'dia');

        $datosGrafico = $dias->map(fn($fecha) => [
            'fecha'    => $fecha->format('d/m'),
            'pedidos'  => $pedidosPorDia[$fecha->toDateString()] ?? 0,
            'ingresos' => $ingresosPorDia[$fecha->toDateString()] ?? 0,
        ]);

        return view('livewire.admin.dashboard', compact('estadisticas', 'ultimosPedidos', 'productosBajoStock', 'datosGrafico', 'productosVendidos'))
            ->layout('layouts.admin', ['titulo' => 'Dashboard — Admin Tileo']);
    }
}
