<?php

namespace App\Livewire\Admin;

use App\Models\Madera;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class GestionMaderas extends Component
{
    use WithPagination, WithFileUploads;

    public bool $mostrarModal = false;
    public ?int $editandoId = null;
    public bool $mostrarConfirmarEliminar = false;
    public ?int $idParaEliminar = null;
    public string $nombreParaEliminar = '';

    public string $nombre = '';
    public string $descripcion = '';
    public string $capacidad = '6';
    public string $precio = '';
    public string $imagen = '';
    public bool $activo = true;
    public $imagenArchivo = null;

    public function abrirCrear(): void
    {
        $this->resetCampos();
        $this->editandoId = null;
        $this->mostrarModal = true;
    }

    public function abrirEditar(int $id): void
    {
        $madera = Madera::findOrFail($id);
        $this->editandoId  = $id;
        $this->nombre      = $madera->nombre;
        $this->descripcion = $madera->descripcion ?? '';
        $this->capacidad   = (string) $madera->capacidad;
        $this->precio      = (string) $madera->precio;
        $this->imagen      = $madera->imagen ?? '';
        $this->activo      = $madera->activo;
        $this->mostrarModal = true;
    }

    public function guardar(): void
    {
        $this->validate([
            'nombre'        => 'required|min:2|max:120',
            'descripcion'   => 'nullable|max:500',
            'capacidad'     => 'required|in:6,12',
            'precio'        => 'required|numeric|min:0',
            'activo'        => 'boolean',
            'imagenArchivo' => 'nullable|image|max:2048',
        ]);

        if ($this->imagenArchivo) {
            $extension     = $this->imagenArchivo->getClientOriginalExtension();
            $nombreArchivo = \Str::slug($this->nombre) . '-' . uniqid() . '.' . $extension;
            $destino       = public_path('imagenes') . '/' . $nombreArchivo;

            if (! copy($this->imagenArchivo->getRealPath(), $destino)) {
                $this->addError('imagenArchivo', 'No se pudo guardar la imagen.');
                return;
            }

            $this->imagen = $nombreArchivo;
        }

        $datos = [
            'nombre'      => $this->nombre,
            'descripcion' => $this->descripcion ?: null,
            'capacidad'   => (int) $this->capacidad,
            'precio'      => $this->precio,
            'imagen'      => $this->imagen ?: null,
            'activo'      => $this->activo,
        ];

        $this->editandoId
            ? Madera::findOrFail($this->editandoId)->update($datos)
            : Madera::create($datos);

        $this->mostrarModal = false;
        $this->resetCampos();
    }

    public function toggleActivo(int $id): void
    {
        $madera = Madera::findOrFail($id);
        $madera->update(['activo' => ! $madera->activo]);
    }

    public function confirmarEliminar(int $id): void
    {
        $madera = Madera::findOrFail($id);
        $this->idParaEliminar     = $id;
        $this->nombreParaEliminar = $madera->nombre;
        $this->mostrarConfirmarEliminar = true;
    }

    public function eliminar(): void
    {
        if ($this->idParaEliminar) {
            Madera::findOrFail($this->idParaEliminar)->delete();
        }
        $this->mostrarConfirmarEliminar = false;
        $this->idParaEliminar = null;
    }

    public function cancelarEliminar(): void
    {
        $this->mostrarConfirmarEliminar = false;
        $this->idParaEliminar = null;
    }

    private function resetCampos(): void
    {
        $this->nombre       = '';
        $this->descripcion  = '';
        $this->capacidad    = '6';
        $this->precio       = '';
        $this->imagen       = '';
        $this->activo       = true;
        $this->imagenArchivo = null;
        $this->resetValidation();
    }

    public function render()
    {
        $maderas = Madera::latest()->paginate(20);

        return view('livewire.admin.gestion-maderas', compact('maderas'))
            ->layout('layouts.admin', ['titulo' => 'Maderas — Admin Tileo']);
    }
}
