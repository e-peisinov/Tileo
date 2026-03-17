# CLAUDE.md — Proyecto Tileo

## Descripción del proyecto
**Tileo** es una página web para un emprendimiento de hierbas, especias y condimentos artesanales ubicado en Mercedes, Buenos Aires. El sitio presenta los productos con énfasis en sabor, aroma y presentación artesanal.

## Stack tecnológico
- **Backend:** Laravel 12
- **Frontend reactivo:** Livewire 3
- **Estilos:** Tailwind CSS v3
- **JS interactivo:** Alpine.js
- **Base de datos:** MySQL (XAMPP local)
- **Entorno local:** XAMPP (PHP 8.2, Apache)

## Convención de código
- Todo el código se escribe **en español**: variables, métodos, propiedades, clases, componentes Livewire, migraciones, rutas nombradas, vistas.
- Nombres de archivos de vistas y componentes Livewire también en español (kebab-case).
- Excepciones: palabras clave del lenguaje, nombres de paquetes, configuración de frameworks.

**Ejemplos:**
```php
// Modelo
class Producto extends Model { ... }

// Componente Livewire
class ListaProductos extends Component { ... }
// archivo: app/Livewire/ListaProductos.php
// vista:   resources/views/livewire/lista-productos.blade.php

// Migraciones
Schema::create('productos', function (Blueprint $tabla) {
    $tabla->id();
    $tabla->string('nombre');
    $tabla->text('descripcion')->nullable();
    $tabla->decimal('precio', 8, 2);
    $tabla->string('categoria');
    $tabla->boolean('activo')->default(true);
    $tabla->timestamps();
});
```

## Estructura de carpetas relevante
```
app/
  Livewire/          # Componentes Livewire (en español)
  Models/            # Modelos Eloquent (en español)
  Http/Controllers/  # Controladores (si aplica)
resources/
  views/
    layouts/         # Layouts base
    livewire/        # Vistas de componentes Livewire
    pages/           # Vistas de páginas completas
  css/
    app.css          # Entry point de Tailwind
database/
  migrations/        # Migraciones en español
  seeders/           # Seeders con datos de prueba
```

## Base de datos
- Motor: MySQL vía XAMPP
- DB name: `tileo`
- Prefijo de tablas: ninguno
- Todas las tablas y columnas en español (snake_case)

## Componentes y páginas planeadas
- **Inicio:** hero, destacados, propuesta de valor
- **Catálogo:** listado de productos con filtros por categoría
- **Producto:** detalle individual
- **Nosotros:** historia del emprendimiento
- **Contacto:** formulario o info de contacto
- **Admin (futuro):** gestión de productos (CRUD con Livewire)

## Paleta y estilo visual
- Estética artesanal, natural, cálida
- Colores base: verdes (hierbas), tierra, ocre, blanco crema
- Tipografía: serif para títulos, sans-serif para cuerpo
- Imágenes con textura, luz natural

## Comandos frecuentes
```bash
# Servidor de desarrollo
php artisan serve

# Compilar assets (Tailwind/Alpine)
npm run dev

# Crear componente Livewire
php artisan make:livewire NombreComponente

# Crear modelo
php artisan make:model Producto

# Crear migracion
php artisan make:migration create_nombre_table

# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders
php artisan db:seed
```

## Notas de entorno
- El proyecto corre en XAMPP: `http://localhost/tileo/public` o con `php artisan serve`
- El archivo `.env` usa `DB_DATABASE=tileo`, ajustar credenciales según XAMPP local
- Apache mod_rewrite debe estar activo para que funcionen las rutas de Laravel
