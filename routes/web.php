<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\Catalogo;
use App\Livewire\Nosotros;
use App\Livewire\Contacto;
use App\Livewire\SeguimientoPedido;
use App\Livewire\DetalleProducto;
use App\Livewire\Preguntas;
use App\Livewire\Carrito;
use App\Livewire\Checkout;
use App\Livewire\ConfirmacionPedido;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\GestionPedidos;
use App\Livewire\Admin\DetallePedido;
use App\Livewire\Admin\GestionProductos;
use App\Livewire\Admin\GestionCategorias;
use App\Livewire\Admin\GestionUsuarios;
use App\Livewire\Admin\GestionConfiguracion;
use App\Livewire\Admin\GestionClientes;
use App\Livewire\Admin\GestionSuscriptores;
use App\Livewire\Admin\GestionResenas;
use App\Livewire\Admin\GestionBanners;
use App\Livewire\Admin\GestionContenidos;
use App\Livewire\Admin\GestionCodigos;
use App\Livewire\Admin\Reportes;
use App\Livewire\Terminos;
use App\Livewire\Privacidad;
use App\Livewire\ConfiguradorMadera;
use App\Livewire\Admin\GestionMaderas;

// Ruta de logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// Rutas públicas
Route::get('/', Dashboard::class)->name('inicio');
Route::get('/dashboard', Dashboard::class)->name('dashboard');
Route::get('/catalogo', Catalogo::class)->name('catalogo');
Route::get('/nosotros', Nosotros::class)->name('nosotros');
Route::get('/contacto', Contacto::class)->name('contacto');
Route::get('/carrito', Carrito::class)->name('carrito');
Route::get('/checkout', Checkout::class)->name('checkout');
Route::get('/pedido/{numero}', ConfirmacionPedido::class)->name('confirmacion-pedido');
Route::get('/seguimiento', SeguimientoPedido::class)->name('seguimiento-pedido');
Route::get('/producto/{producto}', DetalleProducto::class)->name('detalle-producto');
Route::get('/preguntas', Preguntas::class)->name('preguntas');
Route::get('/terminos', Terminos::class)->name('terminos');
Route::get('/privacidad', Privacidad::class)->name('privacidad');
Route::get('/configurar-madera/{madera}', ConfiguradorMadera::class)->name('configurar-madera');

// Rutas de perfil (auth)
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Rutas de administración
Route::middleware(['auth', 'es_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboard::class)->name('dashboard');
    Route::get('/pedidos', GestionPedidos::class)->name('pedidos');
    Route::get('/pedidos/{pedido}', DetallePedido::class)->name('detalle-pedido');
    Route::get('/productos', GestionProductos::class)->name('productos');
    Route::get('/categorias', GestionCategorias::class)->name('categorias');
    Route::get('/usuarios', GestionUsuarios::class)->name('usuarios');
    Route::get('/configuracion', GestionConfiguracion::class)->name('configuracion');
    Route::get('/clientes', GestionClientes::class)->name('clientes');
    Route::get('/suscriptores', GestionSuscriptores::class)->name('suscriptores');
    Route::get('/resenas', GestionResenas::class)->name('resenas');
    Route::get('/banners', GestionBanners::class)->name('banners');
    Route::get('/contenidos', GestionContenidos::class)->name('contenidos');
    Route::get('/codigos-descuento', GestionCodigos::class)->name('codigos-descuento');
    Route::get('/reportes', Reportes::class)->name('reportes');
    Route::get('/maderas', GestionMaderas::class)->name('maderas');
});

require __DIR__.'/auth.php';
