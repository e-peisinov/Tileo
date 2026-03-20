<?php
namespace App\Livewire;
use App\Models\AvisoStock;
use App\Models\Producto;
use Livewire\Component;
use Livewire\Attributes\Validate;

class NotificarStock extends Component
{
    public int $productoId;
    #[Validate('required|email|max:150')]
    public string $emailAviso = '';
    public bool $registrado = false;
    public bool $yaRegistrado = false;

    public function mount(int $productoId): void
    {
        $this->productoId = $productoId;
    }

    public function registrar(): void
    {
        $this->validate();
        $producto = Producto::findOrFail($this->productoId);
        if ($producto->stock > 0) {
            $this->addError('emailAviso', 'El producto ya tiene stock disponible.');
            return;
        }
        $existe = AvisoStock::where('producto_id', $this->productoId)
            ->where('email', strtolower($this->emailAviso))
            ->where('enviado', false)
            ->exists();
        if ($existe) {
            $this->yaRegistrado = true;
        } else {
            AvisoStock::create([
                'producto_id' => $this->productoId,
                'email'       => strtolower($this->emailAviso),
                'enviado'     => false,
            ]);
            $this->registrado = true;
        }
        $this->emailAviso = '';
    }

    public function render()
    {
        return view('livewire.notificar-stock', ['productoId' => $this->productoId]);
    }
}
