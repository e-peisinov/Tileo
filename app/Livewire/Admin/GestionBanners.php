<?php

namespace App\Livewire\Admin;

use App\Models\Banner;
use Livewire\Component;
use Livewire\WithFileUploads;

class GestionBanners extends Component
{
    use WithFileUploads;

    public bool $mostrarModal = false;
    public ?int $editandoId = null;

    // Campos del formulario
    public string $titulo = '';
    public string $subtitulo = '';
    public string $imagen = '';
    public $imagenArchivo = null;
    public string $urlDestino = '';
    public string $textoBoton = '';
    public string $colorFondo = '';
    public bool $activo = true;
    public int $orden = 0;
    public string $mostrarDesde = '';
    public string $mostrarHasta = '';

    public function abrirCrear(): void
    {
        $this->reset([
            'titulo', 'subtitulo', 'imagen', 'imagenArchivo', 'urlDestino',
            'textoBoton', 'colorFondo', 'mostrarDesde', 'mostrarHasta',
        ]);
        $this->activo = true;
        $this->orden  = 0;
        $this->editandoId = null;
        $this->mostrarModal = true;
    }

    public function abrirEditar(int $id): void
    {
        $b = Banner::findOrFail($id);
        $this->editandoId   = $id;
        $this->titulo       = $b->titulo ?? '';
        $this->subtitulo    = $b->subtitulo ?? '';
        $this->imagen       = $b->imagen ?? '';
        $this->imagenArchivo = null;
        $this->urlDestino   = $b->url_destino ?? '';
        $this->textoBoton   = $b->texto_boton ?? '';
        $this->colorFondo   = $b->color_fondo ?? '';
        $this->activo       = $b->activo;
        $this->orden        = $b->orden;
        $this->mostrarDesde = $b->mostrar_desde?->format('Y-m-d') ?? '';
        $this->mostrarHasta = $b->mostrar_hasta?->format('Y-m-d') ?? '';
        $this->mostrarModal = true;
    }

    public function guardar(): void
    {
        $this->validate([
            'titulo'        => 'required|min:1|max:200',
            'subtitulo'     => 'nullable|max:300',
            'imagen'        => 'nullable|max:500',
            'imagenArchivo' => 'nullable|image|max:4096',
            'urlDestino'    => 'nullable|url|max:500',
            'textoBoton'    => 'nullable|max:100',
            'colorFondo'    => 'nullable|max:20',
            'orden'         => 'integer|min:0',
            'mostrarDesde'  => 'nullable|date',
            'mostrarHasta'  => 'nullable|date|after_or_equal:mostrarDesde',
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
            $extension     = $mimePermitidos[$mime];
            $nombreArchivo = 'banner-' . uniqid() . '.' . $extension;
            $destino       = public_path('imagenes/banners') . '/' . $nombreArchivo;

            if (! copy($this->imagenArchivo->getRealPath(), $destino)) {
                $this->addError('imagenArchivo', 'No se pudo guardar la imagen. Verificá los permisos del directorio.');
                return;
            }

            $this->imagen = 'banners/' . $nombreArchivo;
        }

        $datos = [
            'titulo'        => $this->titulo,
            'subtitulo'     => $this->subtitulo ?: null,
            'imagen'        => $this->imagen ?: null,
            'url_destino'   => $this->urlDestino ?: null,
            'texto_boton'   => $this->textoBoton ?: null,
            'color_fondo'   => $this->colorFondo ?: null,
            'activo'        => $this->activo,
            'orden'         => $this->orden,
            'mostrar_desde' => $this->mostrarDesde ?: null,
            'mostrar_hasta' => $this->mostrarHasta ?: null,
        ];

        $this->editandoId
            ? Banner::findOrFail($this->editandoId)->update($datos)
            : Banner::create($datos);

        $this->mostrarModal = false;
    }

    public function toggleActivo(int $id): void
    {
        $b = Banner::findOrFail($id);
        $b->update(['activo' => !$b->activo]);
    }

    public function eliminar(int $id): void
    {
        Banner::findOrFail($id)->delete();
    }

    public function render()
    {
        $banners = Banner::orderBy('orden')->orderByDesc('created_at')->get();

        return view('livewire.admin.gestion-banners', compact('banners'))
            ->layout('layouts.admin', ['titulo' => 'Banners — Admin Tileo']);
    }
}
