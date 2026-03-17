<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\Catalogo;
use App\Livewire\Nosotros;
use App\Livewire\Contacto;

Route::get('/', Dashboard::class)->name('dashboard');
Route::get('/dashboard', Dashboard::class)->name('dashboard');
Route::get('/catalogo', Catalogo::class)->name('catalogo');
Route::get('/nosotros', Nosotros::class)->name('nosotros');
Route::get('/contacto', Contacto::class)->name('contacto');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
