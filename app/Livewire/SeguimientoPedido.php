<?php

namespace App\Livewire;

use App\Models\Pedido;
use Livewire\Component;

class SeguimientoPedido extends Component
{
    public string $numeroPedido = '';
    public string $emailPedido = '';
    public string $modoBusqueda = 'numero'; // numero | email
    public ?Pedido $pedido = null;
    public bool $buscado = false;

    public function buscar(): void
    {
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

            // Si hay múltiples pedidos con ese email, mostramos el más reciente
            $this->pedido = Pedido::with(['items', 'historial'])
                ->where('email_cliente', strtolower(trim($this->emailPedido)))
                ->latest()
                ->first();
        }

        $this->buscado = true;
    }

    public function cambiarModo(string $modo): void
    {
        $this->modoBusqueda = $modo;
        $this->pedido = null;
        $this->buscado = false;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.seguimiento-pedido')
            ->layout('layouts.app', ['titulo' => 'Seguimiento de Pedido — Tileo']);
    }
}
