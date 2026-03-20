<?php
namespace App\Livewire;
use App\Models\Pedido;
use App\Models\Resena;
use Livewire\Component;

class FormularioResena extends Component
{
    public int $productoId;
    public string $numeroPedido = '';
    public int $calificacion = 0;
    public string $comentario = '';
    public string $nombreCliente = '';
    public string $emailCliente = '';
    public bool $enviado = false;
    public string $errorPedido = '';
    public ?int $pedidoVerificado = null;

    public function mount(int $productoId): void
    {
        $this->productoId = $productoId;
    }

    public function verificarPedido(): void
    {
        $this->errorPedido = '';
        $pedido = Pedido::where('numero_pedido', strtoupper(trim($this->numeroPedido)))
            ->whereHas('items', fn($q) => $q->where('producto_id', $this->productoId))
            ->first();
        if (!$pedido) {
            $this->errorPedido = 'No encontramos un pedido con ese número que incluya este producto.';
            return;
        }
        // Verificar si ya dejó una reseña
        $yaReseno = Resena::where('producto_id', $this->productoId)
            ->where('pedido_id', $pedido->id)
            ->exists();
        if ($yaReseno) {
            $this->errorPedido = 'Ya dejaste una reseña para este producto.';
            return;
        }
        $this->pedidoVerificado = $pedido->id;
        $this->emailCliente = $pedido->email_cliente;
        $this->nombreCliente = $pedido->nombre_cliente;
    }

    public function enviar(): void
    {
        $this->validate([
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario'   => 'nullable|max:1000',
            'nombreCliente' => 'required|max:100',
        ]);
        if (!$this->pedidoVerificado) {
            $this->errorPedido = 'Primero verificá tu número de pedido.';
            return;
        }
        Resena::create([
            'producto_id'    => $this->productoId,
            'pedido_id'      => $this->pedidoVerificado,
            'calificacion'   => $this->calificacion,
            'comentario'     => $this->comentario ?: null,
            'nombre_cliente' => $this->nombreCliente,
            'email_cliente'  => $this->emailCliente,
            'aprobada'       => false,
        ]);
        $this->enviado = true;
    }

    public function render()
    {
        return view('livewire.formulario-resena', ['productoId' => $this->productoId]);
    }
}
