<?php

namespace App\Livewire\Admin;

use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Producto;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $estadisticas = [
            'total_pedidos'      => Pedido::count(),
            'pendientes'         => Pedido::where('estado', 'pendiente')->count(),
            'hoy'                => Pedido::whereDate('created_at', today())->count(),
            'stock_bajo'         => Producto::where('activo', true)->where('stock', '<=', 3)->count(),
            'ingresos_mes'       => Pedido::whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)
                                        ->whereNotIn('estado', ['rechazado', 'cancelado'])
                                        ->sum('total'),
        ];

        $ultimosPedidos = Pedido::latest()->limit(8)->get();

        $productosBajoStock = Producto::where('activo', true)
            ->where('stock', '<=', 3)
            ->orderBy('stock')
            ->limit(10)
            ->get();

        $productosVendidos = PedidoItem::selectRaw('nombre_producto, SUM(cantidad) as total_vendido, SUM(subtotal) as total_ingresos')
            ->whereHas('pedido', fn($q) => $q->whereNotIn('estado', ['rechazado', 'cancelado']))
            ->groupBy('nombre_producto')
            ->orderByDesc('total_vendido')
            ->limit(8)
            ->get();

        // Datos para el gráfico: últimos 7 días
        $datosGrafico = collect(range(6, 0))->map(function ($diasAtras) {
            $fecha = now()->subDays($diasAtras);
            return [
                'fecha'    => $fecha->format('d/m'),
                'pedidos'  => \App\Models\Pedido::whereDate('created_at', $fecha)->count(),
                'ingresos' => \App\Models\Pedido::whereDate('created_at', $fecha)
                                ->whereNotIn('estado', ['rechazado', 'cancelado'])
                                ->sum('total'),
            ];
        });

        return view('livewire.admin.dashboard', compact('estadisticas', 'ultimosPedidos', 'productosBajoStock', 'datosGrafico', 'productosVendidos'))
            ->layout('layouts.app', ['titulo' => 'Dashboard — Admin Tileo']);
    }
}
