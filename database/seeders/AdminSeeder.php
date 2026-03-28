<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $usuario = User::firstOrCreate(
            ['email' => 'admin@tileo.com'],
            [
                'name'     => 'Administrador Tileo',
                'password' => Hash::make('tileo2024'),
            ]
        );

        if (! $usuario->es_admin) {
            $usuario->es_admin = true;
            $usuario->save();
        }
    }
}
