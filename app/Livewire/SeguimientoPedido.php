<?php

namespace App\Livewire;

use App\Models\Pedido;
use Illuminate\Support\Collection;
use Livewire\Component;

class SeguimientoPedido extends Component
{
    public string $numeroPedido = '';
    public string $emailPedido = '';
    public string $modoBusqueda = 'numero'; // numero | email
    public ?Pedido $pedido = null;
    public Collection $pedidos;
    public bool $buscado = false;

    public function mount(): void
    {
        $this->pedidos = collect();
    }

    public function buscar(): void
    {
        $this->pedido  = null;
        $this->pedidos = collect();

        if ($this->modoBusqueda === 'numero') {
            $this->validate([
                'numeroPedido' => 'required|min:3',
            ], [
                'numeroPedido.required' => 'Ingresá tu número de pedido.',
                'numeroPedido.min'      => 'El número de pedido debe tener al menos 3 caracteres.',
            ]);

            $this->pedido = Pedido::with(['items', 'historial'])
                ->where('numero_pedido', strtoupper(trim($this->numeroPedido)))
                ->first();
        } else {
            $this->validate([
                'emailPedido' => 'required|email',
            ], [
                'emailPedido.required' => 'Ingresá tu email.',
                'emailPedido.email'    => 'El email no es válido.',
            ]);

            $this->pedidos = Pedido::with(['items', 'historial'])
                ->where('email_cliente', strtolower(trim($this->emailPedido)))
                ->latest()
                ->get();

            // Para compatibilidad con la vista (primer pedido como "principal")
            $this->pedido = $this->pedidos->first();
        }

        $this->buscado = true;
    }

    public function cambiarModo(string $modo): void
    {
        $this->modoBusqueda = $modo;
        $this->pedido       = null;
        $this->pedidos      = collect();
        $this->buscado      = false;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.seguimiento-pedido')
            ->layout('layouts.app', ['titulo' => 'Seguimiento de Pedido — Tileo']);
    }
}
