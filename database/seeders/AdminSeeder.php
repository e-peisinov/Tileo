<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Administrador Tileo',
            'email'    => 'admin@tileo.com',
            'password' => Hash::make('tileo2024'),
            'es_admin' => true,
        ]);
    }
}
