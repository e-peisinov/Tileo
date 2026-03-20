<?php

namespace App\Livewire\Admin;

use App\Models\Suscriptor;
use Livewire\Component;
use Livewire\WithPagination;

class GestionSuscriptores extends Component
{
    use WithPagination;

    public string $busqueda = '';
    public bool $soloActivos = false;

    public function updatingBusqueda(): void
    {
        $this->resetPage();
    }

    public function toggleActivo(int $id): void
    {
        $s = Suscriptor::findOrFail($id);
        $s->update(['activo' => !$s->activo]);
    }

    public function eliminar(int $id): void
    {
        Suscriptor::findOrFail($id)->delete();
    }

    public function exportarCsv(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $suscriptores = Suscriptor::where('activo', true)->orderBy('created_at')->get();

        return response()->streamDownload(function () use ($suscriptores) {
            $h = fopen('php://output', 'w');
            fputs($h, "\xEF\xBB\xBF");
            fputcsv($h, ['Email', 'Nombre', 'Origen', 'Fecha suscripción'], ';');
            foreach ($suscriptores as $s) {
                fputcsv($h, [
                    $s->email,
                    $s->nombre ?? '',
                    $s->origen ?? '',
                    $s->created_at->format('d/m/Y'),
                ], ';');
            }
            fclose($h);
        }, 'suscriptores-' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function render()
    {
        $suscriptores = Suscriptor::query()
            ->when($this->busqueda, fn ($q) => $q->where('email', 'like', "%{$this->busqueda}%"))
            ->when($this->soloActivos, fn ($q) => $q->where('activo', true))
            ->latest()
            ->paginate(25);

        $totalActivos = Suscriptor::where('activo', true)->count();
        $totalTotal   = Suscriptor::count();

        return view('livewire.admin.gestion-suscriptores', compact('suscriptores', 'totalActivos', 'totalTotal'))
            ->layout('layouts.admin', ['titulo' => 'Suscriptores — Admin Tileo']);
    }
}
