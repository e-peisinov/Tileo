<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class GestionUsuarios extends Component
{
    public bool $mostrarModal = false;
    public ?int $editandoId = null;
    public bool $guardado = false;
    public string $errorEliminar = '';

    public string $nombre = '';
    public string $email = '';
    public string $password = '';
    public bool $es_admin = false;

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
        $usuario = User::findOrFail($id);
        $this->editandoId = $id;
        $this->nombre     = $usuario->name;
        $this->email      = $usuario->email;
        $this->password   = '';
        $this->es_admin   = $usuario->es_admin;
        $this->guardado   = false;
        $this->mostrarModal = true;
    }

    public function guardar(): void
    {
        $this->validate([
            'nombre'   => 'required|min:2|max:100',
            'email'    => 'required|email|max:150|unique:users,email' . ($this->editandoId ? ",{$this->editandoId}" : ''),
            'password' => $this->editandoId ? 'nullable|min:6' : 'required|min:6',
        ]);

        $datos = [
            'name'     => $this->nombre,
            'email'    => $this->email,
            'es_admin' => $this->es_admin,
        ];

        if ($this->password) {
            $datos['password'] = Hash::make($this->password);
        }

        if ($this->editandoId) {
            User::findOrFail($this->editandoId)->update($datos);
        } else {
            User::create($datos);
        }

        $this->mostrarModal = false;
        $this->guardado = true;
        $this->resetCampos();
    }

    public function toggleAdmin(int $id): void
    {
        if ($id === auth()->id()) {
            return; // No puede quitarse el admin a sí mismo
        }
        $usuario = User::findOrFail($id);
        $usuario->update(['es_admin' => ! $usuario->es_admin]);
    }

    public function pedirEliminar(int $id): void
    {
        if ($id === auth()->id()) {
            $this->errorEliminar = 'No podés eliminar tu propio usuario.';
            return;
        }
        $usuario = User::findOrFail($id);
        $this->idParaEliminar      = $id;
        $this->nombreParaEliminar  = $usuario->name;
        $this->mostrarConfirmarEliminar = true;
    }

    public function confirmarEliminar(): void
    {
        if ($this->idParaEliminar) {
            if ($this->idParaEliminar === auth()->id()) {
                $this->errorEliminar = 'No podés eliminar tu propio usuario.';
                $this->mostrarConfirmarEliminar = false;
                $this->idParaEliminar = null;
                $this->nombreParaEliminar = '';
                return;
            }
            User::findOrFail($this->idParaEliminar)->delete();
        }
        $this->mostrarConfirmarEliminar = false;
        $this->idParaEliminar = null;
        $this->nombreParaEliminar = '';
        $this->errorEliminar = '';
    }

    public function cancelarEliminar(): void
    {
        $this->mostrarConfirmarEliminar = false;
        $this->idParaEliminar = null;
        $this->nombreParaEliminar = '';
    }

    private function resetCampos(): void
    {
        $this->nombre = $this->email = $this->password = '';
        $this->es_admin = false;
        $this->resetValidation();
    }

    public function render()
    {
        $usuarios = User::orderBy('name')->get();

        return view('livewire.admin.gestion-usuarios', compact('usuarios'))
            ->layout('layouts.admin', ['titulo' => 'Usuarios — Admin Tileo']);
    }
}
