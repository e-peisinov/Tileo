<?php

namespace App\Livewire\Admin;

use App\Models\Pedido;
use App\Models\PedidoHistorialEstado;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class GestionPedidos extends Component
{
    use WithPagination;

    public string $filtroEstado = '';
    public string $busqueda = '';
    public string $filtroFechaDesde = '';
    public string $filtroFechaHasta = '';
    public string $filtroMontoDesde = '';
    public string $filtroMontoHasta = '';
    public array $seleccionados = [];
    public string $accionMasiva = '';

    public function updatingFiltroEstado(): void { $this->resetPage(); }
    public function updatingBusqueda(): void { $this->resetPage(); }
    public function updatingFiltroFechaDesde(): void { $this->resetPage(); }
    public function updatingFiltroFechaHasta(): void { $this->resetPage(); }
    public function updatingFiltroMontoDesde(): void { $this->resetPage(); }
    public function updatingFiltroMontoHasta(): void { $this->resetPage(); }

    public function aplicarAccionMasiva(): void
    {
        $estadosPermitidos = ['pendiente', 'confirmado', 'preparando', 'enviado', 'listo_retiro', 'entregado', 'rechazado', 'cancelado'];

        if (empty($this->seleccionados) || empty($this->accionMasiva) || ! in_array($this->accionMasiva, $estadosPermitidos)) {
            return;
        }

        $cancelados = ['rechazado', 'cancelado'];
        $accionNueva = $this->accionMasiva;

        foreach ($this->seleccionados as $id) {
            $pedido = Pedido::find($id);
            if (! $pedido) continue;

            $estadoAnterior    = $pedido->estado;

            if ($estadoAnterior === $accionNueva) continue;

            $entrandoCancelado = in_array($accionNueva, $cancelados) && ! in_array($estadoAnterior, $cancelados);
            $saliendoCancelado = ! in_array($accionNueva, $cancelados) && in_array($estadoAnterior, $cancelados);

            DB::transaction(function () use ($pedido, $accionNueva, $estadoAnterior, $entrandoCancelado, $saliendoCancelado) {
                if ($entrandoCancelado || $saliendoCancelado) {
                    foreach ($pedido->items as $item) {
                        if ($item->tipo === 'madera' && $item->condimentos) {
                            foreach ($item->condimentos as $condimento) {
                                if (empty($condimento['producto_id'])) continue;
                                $producto = Producto::lockForUpdate()->find($condimento['producto_id']);
                                if (! $producto) continue;
                                if ($entrandoCancelado) {
                                    $producto->increment('stock', $condimento['cantidad']);
                                } else {
                                    if ($producto->stock < $condimento['cantidad']) {
                                        \Log::warning("Stock insuficiente al reactivar pedido {$pedido->numero_pedido}: producto #{$producto->id} tiene {$producto->stock}, se necesitan {$condimento['cantidad']}.");
                                    }
                                    $producto->decrement('stock', $condimento['cantidad']);
                                }
                            }
                        } elseif ($item->producto_id) {
                            $producto = Producto::lockForUpdate()->find($item->producto_id);
                            if (! $producto) continue;
                            if ($entrandoCancelado) {
                                $producto->increment('stock', $item->cantidad);
                            } else {
                                if ($producto->stock < $item->cantidad) {
                                    \Log::warning("Stock insuficiente al reactivar pedido {$pedido->numero_pedido}: producto #{$producto->id} tiene {$producto->stock}, se necesitan {$item->cantidad}.");
                                }
                                $producto->decrement('stock', $item->cantidad);
                            }
                        }
                    }
                }

                $pedido->update(['estado' => $accionNueva]);

                PedidoHistorialEstado::create([
                    'pedido_id'       => $pedido->id,
                    'estado_anterior' => $estadoAnterior,
                    'estado_nuevo'    => $accionNueva,
                    'notas'           => null,
                ]);
            });

        }

        $this->seleccionados = [];
        $this->accionMasiva  = '';
    }

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
            ->when($this->filtroMontoDesde !== '', fn($q) => $q->where('total', '>=', $this->filtroMontoDesde))
            ->when($this->filtroMontoHasta !== '', fn($q) => $q->where('total', '<=', $this->filtroMontoHasta))
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
            ->layout('layouts.admin', ['titulo' => 'Pedidos — Admin Tileo']);
    }
}
