<?php

namespace App\Livewire\Admin;

use App\Models\Resena;
use Livewire\Component;
use Livewire\WithPagination;

class GestionResenas extends Component
{
    use WithPagination;

    public string $filtroAprobada = '';

    public function updatingFiltroAprobada(): void
    {
        $this->resetPage();
    }

    public function aprobar(int $id): void
    {
        Resena::findOrFail($id)->update(['aprobada' => true]);
    }

    public function rechazar(int $id): void
    {
        Resena::findOrFail($id)->update(['aprobada' => false]);
    }

    public function eliminar(int $id): void
    {
        Resena::findOrFail($id)->delete();
    }

    public function render()
    {
        $resenas = Resena::with('producto')
            ->when($this->filtroAprobada !== '', fn ($q) => $q->where('aprobada', $this->filtroAprobada === '1'))
            ->latest()
            ->paginate(20);

        $pendientes = Resena::where('aprobada', false)->count();

        return view('livewire.admin.gestion-resenas', compact('resenas', 'pendientes'))
            ->layout('layouts.admin', ['titulo' => 'Reseñas — Admin Tileo']);
    }
}
