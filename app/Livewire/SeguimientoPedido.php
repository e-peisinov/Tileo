<?php

namespace App\Livewire;

use App\Models\Pedido;
use Livewire\Component;

class SeguimientoPedido extends Component
{
    public string $numeroPedido = '';
    public ?Pedido $pedido = null;
    public bool $buscado = false;

    public function buscar(): void
    {
        $this->validate([
            'numeroPedido' => 'required|min:3',
        ], [
            'numeroPedido.required' => 'Ingresá tu número de pedido.',
            'numeroPedido.min'      => 'El número de pedido debe tener al menos 3 caracteres.',
        ]);

        $this->pedido = Pedido::with(['items', 'historial'])
            ->where('numero_pedido', strtoupper(trim($this->numeroPedido)))
            ->first();

        $this->buscado = true;
    }

    public function render()
    {
        return view('livewire.seguimiento-pedido')
            ->layout('layouts.app', ['titulo' => 'Seguimiento de Pedido — Tileo']);
    }
}
