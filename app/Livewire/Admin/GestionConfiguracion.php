<?php

namespace App\Livewire\Admin;

use App\Models\Configuracion;
use Livewire\Component;

class GestionConfiguracion extends Component
{
    public array $valores = [];
    public bool $guardado = false;

    public function mount(): void
    {
        $this->cargarValores();
    }

    private function cargarValores(): void
    {
        foreach (Configuracion::orderBy('id')->get() as $config) {
            $this->valores[$config->clave] = $config->valor ?? '';
        }
    }

    public function guardar(): void
    {
        $this->validate([
            'valores.tiempo_entrega'     => 'nullable|max:100',
            'valores.mensaje_vacaciones' => 'nullable|max:500',
            'valores.cbu'                => 'nullable|max:22',
            'valores.alias_cbu'          => 'nullable|max:50',
            'valores.titular_cuenta'     => 'nullable|max:100',
        ]);

        foreach ($this->valores as $clave => $valor) {
            Configuracion::updateOrCreate(
                ['clave' => $clave],
                ['valor' => $valor]
            );
        }

        $this->guardado = true;
    }

    public function render()
    {
        $configuraciones = Configuracion::orderBy('id')->get();

        return view('livewire.admin.gestion-configuracion', compact('configuraciones'))
            ->layout('layouts.admin', ['titulo' => 'Configuración — Admin Tileo']);
    }
}
