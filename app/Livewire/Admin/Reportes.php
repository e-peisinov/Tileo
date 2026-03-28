<?php

namespace App\Livewire\Admin;

use App\Models\Pedido;
use App\Models\PedidoItem;
use Livewire\Component;

class Reportes extends Component
{
    public string $fechaDesde = '';
    public string $fechaHasta = '';

    public function mount(): void
    {
        $this->fechaDesde = now()->startOfMonth()->format('Y-m-d');
        $this->fechaHasta = now()->format('Y-m-d');
    }

    private function pedidosBase()
    {
        return Pedido::whereNotIn('estado', ['rechazado', 'cancelado'])
            ->when($this->fechaDesde, fn ($q) => $q->whereDate('created_at', '>=', $this->fechaDesde))
            ->when($this->fechaHasta, fn ($q) => $q->whereDate('created_at', '<=', $this->fechaHasta));
    }

    public function exportarCsv(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $items = PedidoItem::with(['pedido', 'producto.categoria'])
            ->whereHas('pedido', fn ($q) => $q
                ->whereNotIn('estado', ['rechazado', 'cancelado'])
                ->when($this->fechaDesde, fn ($s) => $s->whereDate('created_at', '>=', $this->fechaDesde))
                ->when($this->fechaHasta, fn ($s) => $s->whereDate('created_at', '<=', $this->fechaHasta)))
            ->get();

        return response()->streamDownload(function () use ($items) {
            $h = fopen('php://output', 'w');
            fputs($h, "\xEF\xBB\xBF");
            fputcsv($h, ['Fecha', 'Pedido', 'Producto', 'Categoría', 'Cantidad', 'Subtotal'], ';');
            foreach ($items as $item) {
                fputcsv($h, [
                    $item->pedido->created_at->format('d/m/Y'),
                    $item->pedido->numero_pedido,
                    $item->nombre_producto,
                    $item->producto?->categoria?->nombre ?? '',
                    $item->cantidad,
                    number_format($item->subtotal, 2, ',', '.'),
                ], ';');
            }
            fclose($h);
        }, 'reporte-' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function render()
    {
        $pedidosQuery   = $this->pedidosBase();
        $totalIngresos  = (clone $pedidosQuery)->sum('total');
        $totalPedidos   = (clone $pedidosQuery)->count();
        $ticketPromedio = $totalPedidos > 0 ? $totalIngresos / $totalPedidos : 0;

        $porCategoria = PedidoItem::select('productos.categoria_id')
            ->selectRaw('categorias.nombre as categoria')
            ->selectRaw('SUM(pedido_items.cantidad) as total_unidades')
            ->selectRaw('SUM(pedido_items.subtotal) as total_ingresos')
            ->join('productos', 'pedido_items.producto_id', '=', 'productos.id')
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->whereHas('pedido', fn ($q) => $q
                ->whereNotIn('estado', ['rechazado', 'cancelado'])
                ->when($this->fechaDesde, fn ($s) => $s->whereDate('created_at', '>=', $this->fechaDesde))
                ->when($this->fechaHasta, fn ($s) => $s->whereDate('created_at', '<=', $this->fechaHasta)))
            ->groupBy('productos.categoria_id', 'categorias.nombre')
            ->orderByDesc('total_ingresos')
            ->get();

        // Incluir maderas (tienen producto_id = null, no pertenecen a categoría)
        $maderasRow = PedidoItem::where('tipo', 'madera')
            ->selectRaw('SUM(cantidad) as total_unidades, SUM(subtotal) as total_ingresos')
            ->whereHas('pedido', fn ($q) => $q
                ->whereNotIn('estado', ['rechazado', 'cancelado'])
                ->when($this->fechaDesde, fn ($s) => $s->whereDate('created_at', '>=', $this->fechaDesde))
                ->when($this->fechaHasta, fn ($s) => $s->whereDate('created_at', '<=', $this->fechaHasta)))
            ->first();

        if ($maderasRow && $maderasRow->total_ingresos > 0) {
            $porCategoria->push((object) [
                'categoria'      => 'Maderas',
                'total_unidades' => $maderasRow->total_unidades,
                'total_ingresos' => $maderasRow->total_ingresos,
            ]);
            $porCategoria = $porCategoria->sortByDesc('total_ingresos')->values();
        }

        $topProductos = PedidoItem::selectRaw('nombre_producto, SUM(cantidad) as total_vendido, SUM(subtotal) as total_ingresos')
            ->whereHas('pedido', fn ($q) => $q
                ->whereNotIn('estado', ['rechazado', 'cancelado'])
                ->when($this->fechaDesde, fn ($s) => $s->whereDate('created_at', '>=', $this->fechaDesde))
                ->when($this->fechaHasta, fn ($s) => $s->whereDate('created_at', '<=', $this->fechaHasta)))
            ->groupBy('nombre_producto')
            ->orderByDesc('total_vendido')
            ->limit(10)
            ->get();

        return view('livewire.admin.reportes', compact(
            'totalIngresos', 'totalPedidos', 'ticketPromedio',
            'porCategoria', 'topProductos'
        ))->layout('layouts.admin', ['titulo' => 'Reportes — Admin Tileo']);
    }
}
