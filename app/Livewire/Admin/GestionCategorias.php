<?php

namespace App\Livewire\Admin;

use App\Models\Categoria;
use Livewire\Component;

class GestionCategorias extends Component
{
    public bool $mostrarModal = false;
    public ?int $editandoId = null;

    public string $nombre = '';
    public string $descripcion = '';
    public bool $activo = true;

    public bool $guardado = false;
    public string $errorEliminar = '';

    // Confirmación de eliminación
    public bool $mostrarConfirmarEliminar = false;
    public ?int $idParaEliminar = null;
    public string $nombreParaEliminar = '';

    public function abrirCrear(): void
    {
        $this->resetCampos();
        $this->editandoId = null;
        $this->guardado = false;
        $this->mostrarModal = true;
    }

    public function abrirEditar(int $id): void
    {
        $cat = Categoria::findOrFail($id);
        $this->editandoId  = $id;
        $this->nombre      = $cat->nombre;
        $this->descripcion = $cat->descripcion ?? '';
        $this->activo      = $cat->activo;
        $this->guardado = false;
        $this->mostrarModal = true;
    }

    public function guardar(): void
    {
        $this->validate([
            'nombre'      => 'required|min:2|max:80',
            'descripcion' => 'nullable|max:500',
        ]);

        $datos = [
            'nombre'      => $this->nombre,
            'descripcion' => $this->descripcion ?: null,
            'activo'      => $this->activo,
        ];

        if ($this->editandoId) {
            Categoria::findOrFail($this->editandoId)->update($datos);
        } else {
            Categoria::create($datos);
        }

        $this->mostrarModal = false;
        $this->guardado = true;
        $this->resetCampos();
    }

    public function pedirEliminar(int $id): void
    {
        $cat = Categoria::withCount('productos')->findOrFail($id);

        if ($cat->productos_count > 0) {
            $this->errorEliminar = "No se puede eliminar \"{$cat->nombre}\": tiene {$cat->productos_count} producto(s) asociado(s).";
            return;
        }

        $this->idParaEliminar     = $id;
        $this->nombreParaEliminar = $cat->nombre;
        $this->mostrarConfirmarEliminar = true;
        $this->errorEliminar = '';
    }

    public function confirmarEliminar(): void
    {
        if ($this->idParaEliminar) {
            Categoria::findOrFail($this->idParaEliminar)->delete();
        }
        $this->mostrarConfirmarEliminar = false;
        $this->idParaEliminar = null;
        $this->nombreParaEliminar = '';
    }

    public function cancelarEliminar(): void
    {
        $this->mostrarConfirmarEliminar = false;
        $this->idParaEliminar = null;
        $this->nombreParaEliminar = '';
    }

    private function resetCampos(): void
    {
        $this->nombre = $this->descripcion = '';
        $this->activo = true;
        $this->resetValidation();
    }

    public function render()
    {
        $categorias = Categoria::withCount('productos')->orderBy('nombre')->get();

        return view('livewire.admin.gestion-categorias', compact('categorias'))
            ->layout('layouts.admin', ['titulo' => 'Categorías — Admin Tileo']);
    }
}
