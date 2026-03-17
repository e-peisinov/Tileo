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

    public function updatingFiltroEstado(): void { $this->resetPage(); }
    public function updatingBusqueda(): void { $this->resetPage(); }

    public function render()
    {
        $pedidos = Pedido::query()
            ->when($this->filtroEstado, fn($q) => $q->where('estado', $this->filtroEstado))
            ->when($this->busqueda, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('numero_pedido', 'like', "%{$this->busqueda}%")
                        ->orWhere('nombre_cliente', 'like', "%{$this->busqueda}%")
                        ->orWhere('email_cliente', 'like', "%{$this->busqueda}%");
                });
            })
            ->latest()
            ->paginate(20);

        return view('livewire.admin.gestion-pedidos', compact('pedidos'))
            ->layout('layouts.app', ['titulo' => 'Pedidos — Admin Tileo']);
    }
}
