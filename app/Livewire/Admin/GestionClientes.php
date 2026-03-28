<?php

namespace App\Livewire\Admin;

use App\Models\Pedido;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class GestionClientes extends Component
{
    use WithPagination;

    public string $busqueda = '';

    public function updatingBusqueda(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $clientes = Pedido::select('email_cliente')
            ->selectRaw('MAX(nombre_cliente) as nombre_cliente')
            ->selectRaw('COUNT(*) as total_pedidos')
            ->selectRaw("SUM(CASE WHEN estado NOT IN ('rechazado', 'cancelado') THEN total ELSE 0 END) as total_gastado")
            ->selectRaw('MAX(created_at) as ultimo_pedido')
            ->when($this->busqueda, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nombre_cliente', 'like', "%{$this->busqueda}%")
                        ->orWhere('email_cliente', 'like', "%{$this->busqueda}%");
                });
            })
            ->groupBy('email_cliente')
            ->orderByDesc('ultimo_pedido')
            ->paginate(20);

        return view('livewire.admin.gestion-clientes', compact('clientes'))
            ->layout('layouts.admin', ['titulo' => 'Clientes — Admin Tileo']);
    }
}
