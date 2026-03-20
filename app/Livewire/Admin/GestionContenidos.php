<?php

namespace App\Livewire\Admin;

use App\Models\Contenido;
use Livewire\Component;

class GestionContenidos extends Component
{
    public ?int $editandoId = null;
    public string $cuerpo = '';
    public string $tipo = '';
    public string $etiqueta = '';
    public array $faqs = [];

    public function abrirEditar(int $id): void
    {
        $contenido = Contenido::findOrFail($id);
        $this->editandoId = $id;
        $this->tipo       = $contenido->tipo;
        $this->etiqueta   = $contenido->etiqueta ?? '';

        if ($contenido->tipo === 'json_faq') {
            $this->faqs  = json_decode($contenido->cuerpo ?? '[]', true) ?: [];
            $this->cuerpo = '';
        } else {
            $this->cuerpo = $contenido->cuerpo ?? '';
            $this->faqs   = [];
        }
    }

    public function agregarFaq(): void
    {
        $this->faqs[] = ['pregunta' => '', 'respuesta' => ''];
    }

    public function eliminarFaq(int $index): void
    {
        array_splice($this->faqs, $index, 1);
    }

    public function guardar(): void
    {
        if ($this->tipo === 'json_faq') {
            $cuerpoGuardar = json_encode($this->faqs, JSON_UNESCAPED_UNICODE);
        } else {
            $this->validate(['cuerpo' => 'nullable']);
            $cuerpoGuardar = $this->cuerpo;
        }

        Contenido::findOrFail($this->editandoId)->update(['cuerpo' => $cuerpoGuardar]);

        $this->editandoId = null;
        $this->cuerpo     = '';
        $this->faqs       = [];
        $this->tipo       = '';
        $this->etiqueta   = '';
    }

    public function cancelar(): void
    {
        $this->editandoId = null;
        $this->cuerpo     = '';
        $this->faqs       = [];
        $this->tipo       = '';
        $this->etiqueta   = '';
    }

    public function render()
    {
        $contenidos = Contenido::orderBy('etiqueta')->get();

        return view('livewire.admin.gestion-contenidos', compact('contenidos'))
            ->layout('layouts.admin', ['titulo' => 'Contenido — Admin Tileo']);
    }
}
