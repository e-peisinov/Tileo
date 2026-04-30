<?php

namespace App\Livewire\Admin;

use App\Models\Categoria;
use App\Models\ImagenProducto;
use App\Models\Producto;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class GestionProductos extends Component
{
    use WithPagination, WithFileUploads;

    public bool $mostrarModal = false;
    public ?int $editandoId = null;
    public bool $guardado = false;

    // Confirmación de eliminación
    public bool $mostrarConfirmarEliminar = false;
    public ?int $idParaEliminar = null;
    public string $nombreParaEliminar = '';

    public string $nombre = '';
    public string $descripcion = '';
    public string $precio = '';
    public string $stock = '';
    public string $unidad = 'frasco';
    public string $imagen = '';
    public array $categoriasSeleccionadas = [];
    public bool $activo = true;
    public bool $destacado = false;

    public $imagenArchivo = null;

    // Galería de imágenes adicionales
    public array $galeriaExistente = []; // [{id, archivo, url}] — imágenes ya guardadas
    public $galeriaArchivo0 = null;
    public $galeriaArchivo1 = null;
    public $galeriaArchivo2 = null;
    public $galeriaArchivo3 = null;

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
        $this->categoriasSeleccionadas = $producto->categorias->pluck('id')->toArray();
        $this->activo       = $producto->activo;
        $this->destacado    = $producto->destacado;
        $this->galeriaExistente = $producto->imagenesGaleria()
            ->orderBy('orden')
            ->get()
            ->map(fn($img) => ['id' => $img->id, 'archivo' => $img->archivo, 'url' => $img->url])
            ->toArray();
        $this->mostrarModal = true;
    }

    public function guardar(): void
    {
        $this->validate([
            'nombre'         => 'required|min:2|max:120',
            'descripcion'    => 'nullable|max:1000',
            'precio'         => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'unidad'         => 'required|max:30',
            'imagen'         => 'nullable|max:255',
            'categoriasSeleccionadas'   => ['required', 'array', 'min:1'],
            'categoriasSeleccionadas.*' => [Rule::exists('categorias', 'id')->where('activo', true)],
            'imagenArchivo'  => 'nullable|image|max:2048',
            'galeriaArchivo0' => 'nullable|image|max:2048',
            'galeriaArchivo1' => 'nullable|image|max:2048',
            'galeriaArchivo2' => 'nullable|image|max:2048',
            'galeriaArchivo3' => 'nullable|image|max:2048',
            'destacado'      => 'boolean',
        ]);

        if ($this->imagenArchivo) {
            $mimePermitidos = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/gif'  => 'gif',
                'image/webp' => 'webp',
            ];
            $mime = $this->imagenArchivo->getMimeType();
            if (! isset($mimePermitidos[$mime])) {
                $this->addError('imagenArchivo', 'Solo se permiten imágenes JPG, PNG, GIF o WEBP.');
                return;
            }
            $extension = $mimePermitidos[$mime];
            $nombreArchivo = \Str::slug($this->nombre) . '-' . uniqid() . '.' . $extension;
            $destino       = public_path('imagenes') . '/' . $nombreArchivo;

            if (! copy($this->imagenArchivo->getRealPath(), $destino)) {
                $this->addError('imagenArchivo', 'No se pudo guardar la imagen. Verificá los permisos del directorio public/imagenes/.');
                return;
            }

            $this->imagen = $nombreArchivo;
        }

        $datos = [
            'nombre'      => $this->nombre,
            'descripcion' => $this->descripcion ?: null,
            'precio'      => $this->precio,
            'stock'       => $this->stock,
            'unidad'      => $this->unidad,
            'imagen'      => $this->imagen ?: null,
            'activo'      => $this->activo,
            'destacado'   => $this->destacado,
        ];

        if ($this->editandoId) {
            $producto = Producto::findOrFail($this->editandoId);
            $producto->update($datos);
        } else {
            $producto = Producto::create($datos);
        }

        $producto->categorias()->sync($this->categoriasSeleccionadas);

        // Guardar imágenes de galería adicionales
        $galeriaSlots = ['galeriaArchivo0', 'galeriaArchivo1', 'galeriaArchivo2', 'galeriaArchivo3'];
        $ordenActual  = $producto->imagenesGaleria()->max('orden') ?? 0;

        $mimePermitidosGaleria = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/webp' => 'webp',
        ];

        foreach ($galeriaSlots as $slot) {
            if ($this->$slot) {
                $mime = $this->$slot->getMimeType();
                if (! isset($mimePermitidosGaleria[$mime])) continue;
                $ext     = $mimePermitidosGaleria[$mime];
                $archivo  = \Str::slug($producto->nombre) . '-galeria-' . uniqid() . '.' . $ext;
                $destino  = public_path('imagenes') . '/' . $archivo;

                if (copy($this->$slot->getRealPath(), $destino)) {
                    $ordenActual++;
                    ImagenProducto::create([
                        'producto_id' => $producto->id,
                        'archivo'     => $archivo,
                        'orden'       => $ordenActual,
                    ]);
                }
            }
        }

        $this->mostrarModal = false;
        $this->guardado = true;
        $this->resetCampos();
    }

    public function eliminarImagenGaleria(int $id): void
    {
        $img = ImagenProducto::findOrFail($id);
        $ruta = public_path('imagenes') . '/' . $img->archivo;
        if (file_exists($ruta)) {
            unlink($ruta);
        }
        $img->delete();

        // Refrescar lista si sigue abierto el modal
        if ($this->editandoId) {
            $producto = Producto::findOrFail($this->editandoId);
            $this->galeriaExistente = $producto->imagenesGaleria()
                ->orderBy('orden')
                ->get()
                ->map(fn($img) => ['id' => $img->id, 'archivo' => $img->archivo, 'url' => $img->url])
                ->toArray();
        }
    }

    public function pedirEliminar(int $id): void
    {
        $producto = Producto::findOrFail($id);
        $this->idParaEliminar     = $id;
        $this->nombreParaEliminar = $producto->nombre;
        $this->mostrarConfirmarEliminar = true;
    }

    public function confirmarEliminar(): void
    {
        if ($this->idParaEliminar) {
            Producto::findOrFail($this->idParaEliminar)->delete();
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

    public function toggleActivo(int $id): void
    {
        $producto = Producto::findOrFail($id);
        $producto->update(['activo' => ! $producto->activo]);
    }

    private function resetCampos(): void
    {
        $this->nombre = $this->descripcion = $this->precio = $this->stock = $this->imagen = '';
        $this->unidad = 'frasco';
        $this->categoriasSeleccionadas = [];
        $this->activo = true;
        $this->destacado = false;
        $this->imagenArchivo = null;
        $this->galeriaExistente = [];
        $this->galeriaArchivo0 = null;
        $this->galeriaArchivo1 = null;
        $this->galeriaArchivo2 = null;
        $this->galeriaArchivo3 = null;
        $this->resetValidation();
    }

    public function render()
    {
        $productos = Producto::with('categorias')
            ->when($this->busqueda, fn($q) => $q->where('nombre', 'like', "%{$this->busqueda}%"))
            ->latest()
            ->paginate(20);

        $categorias = Categoria::orderBy('nombre')->get();

        return view('livewire.admin.gestion-productos', compact('productos', 'categorias'))
            ->layout('layouts.admin', ['titulo' => 'Productos — Admin Tileo']);
    }
}
