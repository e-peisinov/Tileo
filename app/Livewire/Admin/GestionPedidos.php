<?php

namespace App\Livewire\Admin;

use App\Models\Pedido;
use Livewire\Component;
use Livewire\WithPagination;

class GestionPedidos extends Component
{
    use WithPagination;

    public string $filtroEstado = '';
    public string $busqueda = '';
    public string $filtroFechaDesde = '';
    public string $filtroFechaHasta = '';

    public function updatingFiltroEstado(): void { $this->resetPage(); }
    public function updatingBusqueda(): void { $this->resetPage(); }
    public function updatingFiltroFechaDesde(): void { $this->resetPage(); }
    public function updatingFiltroFechaHasta(): void { $this->resetPage(); }

    private function queryFiltrada()
    {
        return Pedido::query()
            ->when($this->filtroEstado, fn($q) => $q->where('estado', $this->filtroEstado))
            ->when($this->busqueda, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('numero_pedido', 'like', "%{$this->busqueda}%")
                        ->orWhere('nombre_cliente', 'like', "%{$this->busqueda}%")
                        ->orWhere('email_cliente', 'like', "%{$this->busqueda}%");
                });
            })
            ->when($this->filtroFechaDesde, fn($q) => $q->whereDate('created_at', '>=', $this->filtroFechaDesde))
            ->when($this->filtroFechaHasta, fn($q) => $q->whereDate('created_at', '<=', $this->filtroFechaHasta))
            ->latest();
    }

    public function exportarCsv(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $pedidos = $this->queryFiltrada()->get();

        return response()->streamDownload(function () use ($pedidos) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF"); // BOM para Excel UTF-8
            fputcsv($handle, [
                'Número', 'Fecha', 'Cliente', 'Email', 'Teléfono',
                'Entrega', 'Dirección', 'Pago', 'Estado',
                'Subtotal', 'Envío', 'Total', 'Notas cliente',
            ], ';');

            foreach ($pedidos as $pedido) {
                fputcsv($handle, [
                    $pedido->numero_pedido,
                    $pedido->created_at->format('d/m/Y H:i'),
                    $pedido->nombre_cliente,
                    $pedido->email_cliente,
                    $pedido->telefono_cliente,
                    $pedido->metodo_entrega === 'envio' ? 'Envío' : 'Retiro',
                    $pedido->direccion_envio ?? '',
                    $pedido->metodo_pago === 'transferencia' ? 'Transferencia' : 'Efectivo',
                    $pedido->etiquetaEstado(),
                    number_format($pedido->subtotal, 2, ',', '.'),
                    $pedido->costo_envio !== null ? number_format($pedido->costo_envio, 2, ',', '.') : 'A confirmar',
                    number_format($pedido->total, 2, ',', '.'),
                    $pedido->notas_cliente ?? '',
                ], ';');
            }

            fclose($handle);
        }, 'pedidos-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function render()
    {
        $pedidos = $this->queryFiltrada()->paginate(20);

        return view('livewire.admin.gestion-pedidos', compact('pedidos'))
            ->layout('layouts.app', ['titulo' => 'Pedidos — Admin Tileo']);
    }
}
