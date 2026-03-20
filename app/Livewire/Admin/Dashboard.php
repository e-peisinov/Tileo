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

    public function updatingFechaDesde(): void {}
    public function updatingFechaHasta(): void {}

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
            'stock_bajo'    => Producto::where('activo', true)->where('stock', '<=', 3)->count(),
            'ingresos_mes'  => (clone $base)->whereNotIn('estado', ['rechazado', 'cancelado'])->sum('total'),
        ];

        $ultimosPedidos = (clone $base)->latest()->limit(8)->get();

        $productosBajoStock = Producto::where('activo', true)
            ->where('stock', '<=', 3)
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

        // Gráfico: si hay filtro de fechas, muestra ese rango; si no, últimos 7 días
        if ($this->fechaDesde && $this->fechaHasta) {
            $inicio = \Carbon\Carbon::parse($this->fechaDesde);
            $fin    = \Carbon\Carbon::parse($this->fechaHasta);
            $dias   = collect();
            for ($d = $inicio->copy(); $d->lte($fin); $d->addDay()) {
                $dias->push($d->copy());
            }
            // Si el rango es muy grande, tomamos máximo 30 puntos
            if ($dias->count() > 30) {
                $dias = collect(range(0, 29))->map(fn($i) => $inicio->copy()->addDays((int) round($i * ($dias->count() - 1) / 29)));
            }
            $datosGrafico = $dias->map(function ($fecha) {
                return [
                    'fecha'    => $fecha->format('d/m'),
                    'pedidos'  => Pedido::whereDate('created_at', $fecha)->count(),
                    'ingresos' => Pedido::whereDate('created_at', $fecha)
                                    ->whereNotIn('estado', ['rechazado', 'cancelado'])
                                    ->sum('total'),
                ];
            });
        } else {
            $datosGrafico = collect(range(6, 0))->map(function ($diasAtras) {
                $fecha = now()->subDays($diasAtras);
                return [
                    'fecha'    => $fecha->format('d/m'),
                    'pedidos'  => Pedido::whereDate('created_at', $fecha)->count(),
                    'ingresos' => Pedido::whereDate('created_at', $fecha)
                                    ->whereNotIn('estado', ['rechazado', 'cancelado'])
                                    ->sum('total'),
                ];
            });
        }

        return view('livewire.admin.dashboard', compact('estadisticas', 'ultimosPedidos', 'productosBajoStock', 'datosGrafico', 'productosVendidos'))
            ->layout('layouts.admin', ['titulo' => 'Dashboard — Admin Tileo']);
    }
}
