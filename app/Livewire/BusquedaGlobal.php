<?php
namespace App\Livewire;
use App\Models\Producto;
use Livewire\Component;

class BusquedaGlobal extends Component
{
    public string $termino = '';
    public bool $abierta = false;

    public function updatedTermino(): void
    {
        $this->abierta = strlen($this->termino) >= 2;
    }

    public function cerrar(): void
    {
        $this->termino = '';
        $this->abierta = false;
    }

    public function render()
    {
        $resultados = [];
        if (strlen($this->termino) >= 2) {
            $resultados = Producto::where('activo', true)
                ->where(function($q) {
                    $q->where('nombre', 'like', "%{$this->termino}%")
                      ->orWhere('descripcion', 'like', "%{$this->termino}%");
                })
                ->limit(6)
                ->get();
        }
        return view('livewire.busqueda-global', compact('resultados'));
    }
}
