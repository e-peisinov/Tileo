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

    public function abrirCrear(): void
    {
        $this->resetCampos();
        $this->editandoId = null;
        $this->mostrarModal = true;
    }

    public function abrirEditar(int $id): void
    {
        $cat = Categoria::findOrFail($id);
        $this->editandoId  = $id;
        $this->nombre      = $cat->nombre;
        $this->descripcion = $cat->descripcion ?? '';
        $this->activo      = $cat->activo;
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
        $this->resetCampos();
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
            ->layout('layouts.app', ['titulo' => 'Categorías — Admin Tileo']);
    }
}
