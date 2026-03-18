<?php

namespace App\Livewire\Admin;

use App\Models\Categoria;
use App\Models\Producto;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class GestionProductos extends Component
{
    use WithPagination, WithFileUploads;

    public bool $mostrarModal = false;
    public ?int $editandoId = null;
    public bool $guardado = false;

    public string $nombre = '';
    public string $descripcion = '';
    public string $precio = '';
    public string $stock = '';
    public string $unidad = 'frasco';
    public string $imagen = '';
    public int $categoria_id = 0;
    public bool $activo = true;
    public bool $destacado = false;

    public $imagenArchivo = null;

    public string $busqueda = '';

    public function updatingBusqueda(): void { $this->resetPage(); }

    public function abrirCrear(): void
    {
        $this->resetCampos();
        $this->editandoId = null;
        $this->guardado = false;
        $this->mostrarModal = true;
    }

    public function abrirEditar(int $id): void
    {
        $producto = Producto::findOrFail($id);
        $this->editandoId   = $id;
        $this->guardado = false;
        $this->nombre       = $producto->nombre;
        $this->descripcion  = $producto->descripcion ?? '';
        $this->precio       = (string) $producto->precio;
        $this->stock        = (string) $producto->stock;
        $this->unidad       = $producto->unidad;
        $this->imagen       = $producto->imagen ?? '';
        $this->categoria_id = $producto->categoria_id;
        $this->activo       = $producto->activo;
        $this->destacado    = $producto->destacado;
        $this->mostrarModal = true;
    }

    public function guardar(): void
    {
        $this->validate([
            'nombre'        => 'required|min:2|max:120',
            'descripcion'   => 'nullable|max:1000',
            'precio'        => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'unidad'        => 'required|max:30',
            'imagen'        => 'nullable|max:255',
            'categoria_id'  => 'required|exists:categorias,id',
            'imagenArchivo' => 'nullable|image|max:2048',
            'destacado'     => 'boolean',
        ]);

        if ($this->imagenArchivo) {
            $extension     = $this->imagenArchivo->getClientOriginalExtension();
            $nombreArchivo = \Str::slug($this->nombre) . '-' . uniqid() . '.' . $extension;
            $destino       = public_path('imagenes') . '/' . $nombreArchivo;

            if (! copy($this->imagenArchivo->getRealPath(), $destino)) {
                $this->addError('imagenArchivo', 'No se pudo guardar la imagen. Verificá los permisos del directorio public/imagenes/.');
                return;
            }

            $this->imagen = $nombreArchivo;
        }

        $datos = [
            'nombre'       => $this->nombre,
            'descripcion'  => $this->descripcion ?: null,
            'precio'       => $this->precio,
            'stock'        => $this->stock,
            'unidad'       => $this->unidad,
            'imagen'       => $this->imagen ?: null,
            'categoria_id' => $this->categoria_id,
            'activo'       => $this->activo,
            'destacado'    => $this->destacado,
        ];

        if ($this->editandoId) {
            Producto::findOrFail($this->editandoId)->update($datos);
        } else {
            Producto::create($datos);
        }

        $this->mostrarModal = false;
        $this->guardado = true;
        $this->resetCampos();
    }

    public function eliminar(int $id): void
    {
        Producto::findOrFail($id)->delete();
    }

    public function toggleActivo(int $id): void
    {
        $producto = Producto::findOrFail($id);
        $producto->update(['activo' => ! $producto->activo]);
    }

    private function resetCampos(): void
    {
        $this->nombre = $this->descripcion = $this->precio = $this->stock = $this->imagen = '';
        $this->unidad = 'frasco';
        $this->categoria_id = 0;
        $this->activo = true;
        $this->destacado = false;
        $this->imagenArchivo = null;
        $this->resetValidation();
    }

    public function render()
    {
        $productos = Producto::with('categoria')
            ->when($this->busqueda, fn($q) => $q->where('nombre', 'like', "%{$this->busqueda}%"))
            ->latest()
            ->paginate(20);

        $categorias = Categoria::orderBy('nombre')->get();

        return view('livewire.admin.gestion-productos', compact('productos', 'categorias'))
            ->layout('layouts.app', ['titulo' => 'Productos — Admin Tileo']);
    }
}
