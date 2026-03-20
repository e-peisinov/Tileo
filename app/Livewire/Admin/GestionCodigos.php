<?php

namespace App\Livewire\Admin;

use App\Models\CodigoDescuento;
use Livewire\Component;

class GestionCodigos extends Component
{
    public bool $mostrarModal = false;
    public ?int $editandoId = null;

    // Campos del formulario
    public string $codigo = '';
    public string $tipo = 'porcentaje';
    public string $valor = '';
    public string $minimoCompra = '';
    public string $expiraEn = '';
    public string $usosMaximos = '';
    public bool $activo = true;
    public bool $soloUnUsoPorEmail = false;

    public function abrirCrear(): void
    {
        $this->reset(['codigo', 'valor', 'minimoCompra', 'expiraEn', 'usosMaximos']);
        $this->tipo            = 'porcentaje';
        $this->activo          = true;
        $this->soloUnUsoPorEmail = false;
        $this->editandoId      = null;
        $this->mostrarModal    = true;
    }

    public function abrirEditar(int $id): void
    {
        $c = CodigoDescuento::findOrFail($id);
        $this->editandoId        = $id;
        $this->codigo            = $c->codigo;
        $this->tipo              = $c->tipo;
        $this->valor             = (string) $c->valor;
        $this->minimoCompra      = $c->minimo_compra ? (string) $c->minimo_compra : '';
        $this->expiraEn          = $c->expira_en?->format('Y-m-d') ?? '';
        $this->usosMaximos       = $c->usos_maximos ? (string) $c->usos_maximos : '';
        $this->activo            = $c->activo;
        $this->soloUnUsoPorEmail = $c->solo_un_uso_por_email;
        $this->mostrarModal      = true;
    }

    public function guardar(): void
    {
        $this->validate([
            'codigo'       => 'required|max:50',
            'tipo'         => 'required|in:porcentaje,monto_fijo',
            'valor'        => 'required|numeric|min:0',
            'minimoCompra' => 'nullable|numeric|min:0',
            'expiraEn'     => 'nullable|date',
            'usosMaximos'  => 'nullable|integer|min:1',
        ]);

        $query = CodigoDescuento::where('codigo', strtoupper($this->codigo));
        if ($this->editandoId) {
            $query->where('id', '!=', $this->editandoId);
        }
        if ($query->exists()) {
            $this->addError('codigo', 'Este código ya existe.');
            return;
        }

        $datos = [
            'codigo'                => strtoupper($this->codigo),
            'tipo'                  => $this->tipo,
            'valor'                 => $this->valor,
            'minimo_compra'         => $this->minimoCompra ?: null,
            'expira_en'             => $this->expiraEn ?: null,
            'usos_maximos'          => $this->usosMaximos ?: null,
            'activo'                => $this->activo,
            'solo_un_uso_por_email' => $this->soloUnUsoPorEmail,
        ];

        $this->editandoId
            ? CodigoDescuento::findOrFail($this->editandoId)->update($datos)
            : CodigoDescuento::create($datos);

        $this->mostrarModal = false;
    }

    public function toggleActivo(int $id): void
    {
        $c = CodigoDescuento::findOrFail($id);
        $c->update(['activo' => !$c->activo]);
    }

    public function eliminar(int $id): void
    {
        CodigoDescuento::findOrFail($id)->delete();
    }

    public function render()
    {
        $codigos = CodigoDescuento::withCount('usos')->latest()->get();

        return view('livewire.admin.gestion-codigos', compact('codigos'))
            ->layout('layouts.admin', ['titulo' => 'Códigos de descuento — Admin Tileo']);
    }
}
