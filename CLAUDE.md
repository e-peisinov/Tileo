# CLAUDE.md — Proyecto Tileo

## Descripción del proyecto
**Tileo** es una página web para un emprendimiento de hierbas, especias y condimentos artesanales ubicado en Mercedes, Buenos Aires. El sitio permite a los clientes explorar el catálogo y realizar pedidos online sin necesidad de registrarse.

## Stack tecnológico
- **Backend:** Laravel 12
- **Frontend reactivo:** Livewire 3
- **Estilos:** Tailwind CSS v3 (evitar `style=""` inline, usar clases Tailwind; colores hex inline cuando no hay clase disponible)
- **JS interactivo:** Alpine.js
- **Base de datos:** MySQL (XAMPP local)
- **Entorno local:** XAMPP (PHP 8.2, Apache)

## Convención de código
- Todo el código se escribe **en español**: variables, métodos, propiedades, clases, componentes Livewire, migraciones, rutas nombradas, vistas.
- Nombres de archivos de vistas y componentes Livewire también en español (kebab-case).
- Excepciones: palabras clave del lenguaje, nombres de paquetes, configuración de frameworks.
- **Modelos y migraciones se crean por separado**, nunca con el flag `-m`.

## Estructura de carpetas relevante
```
app/
  Livewire/
    Admin/             # Componentes del panel de administración
  Models/              # Modelos Eloquent
  Mail/                # Mailables
  Http/
    Middleware/        # EsAdmin.php
resources/
  views/
    layouts/           # app.blade.php (layout principal con @livewire('carrito'))
    livewire/
      admin/           # Vistas del panel admin
    emails/            # Templates de emails
config/
  tileo.php            # Configuración propia: whatsapp, email_admin
database/
  migrations/
  seeders/
```

## Base de datos
- Motor: MySQL vía XAMPP
- DB name: `tileo`
- Prefijo de tablas: ninguno
- Todas las tablas y columnas en español (snake_case)

### Tablas existentes
| Tabla | Descripción |
|---|---|
| `users` | Usuarios del sistema. Columna `es_admin` (bool) distingue administradores |
| `categorias` | Categorías de productos (`nombre`, `descripcion`, `activo`) |
| `productos` | Productos (`nombre`, `descripcion`, `precio`, `stock`, `unidad`, `imagen`, `activo`, FK `categoria_id`) |
| `pedidos` | Pedidos de clientes. `numero_pedido` = TIL-0001 (auto). `estado` enum. `costo_envio` nullable (lo confirma el admin) |
| `pedido_items` | Ítems de cada pedido con snapshot de `nombre_producto` y `precio_unitario` |

## Modelos existentes
- `Categoria` — `hasMany Producto`
- `Producto` — `belongsTo Categoria`, método `hayStock(int $cantidad)`
- `Pedido` — genera `numero_pedido` en `booted()`, métodos `etiquetaEstado()` y `colorEstado()`
- `PedidoItem` — snapshot de precio y nombre al momento del pedido
- `User` — campo `es_admin` bool

## Componentes y páginas existentes

### Públicos
| Componente | Ruta | Descripción |
|---|---|---|
| `Dashboard` | `/` | Página de inicio |
| `Catalogo` | `/catalogo` | Catálogo con filtros por categoría. Despacha evento `producto-agregado` |
| `Nosotros` | `/nosotros` | Historia del emprendimiento |
| `Contacto` | `/contacto` | Formulario de contacto |
| `Carrito` | (drawer global) | Incluido en el layout. Escucha `producto-agregado` via `#[On]`. Carrito basado en `session('carrito')` |
| `Checkout` | `/checkout` | Formulario de pedido. Crea `Pedido` + `PedidoItem`, descuenta stock, envía email al admin |
| `ConfirmacionPedido` | `/pedido/{numero}` | Resumen del pedido + botón WhatsApp pre-cargado |

### Admin (requieren `auth` + `es_admin`)
| Componente | Ruta | Descripción |
|---|---|---|
| `Admin\GestionPedidos` | `/admin/pedidos` | Lista de pedidos con filtros |
| `Admin\DetallePedido` | `/admin/pedidos/{id}` | Detalle + cambiar estado + costo de envío + notas |
| `Admin\GestionProductos` | `/admin/productos` | CRUD de productos con control de stock |
| `Admin\GestionCategorias` | `/admin/categorias` | CRUD de categorías |

## Carrito — funcionamiento
- Almacenado en `session('carrito')` como array indexado por `producto_id`
- El componente `Carrito` está incluido en `layouts/app.blade.php` con `@livewire('carrito')`
- Para agregar un producto desde cualquier componente: `$this->dispatch('producto-agregado', productoId: $id)`
- El carrito se vacía automáticamente al confirmar el checkout
- **Importante:** no usar clases `fade-in` / `stagger-*` en elementos renderizados por Livewire — el IntersectionObserver del layout no los re-observa tras un re-render, dejándolos invisibles

## Sistema de pedidos
- Al confirmar el checkout: se crea el pedido, se descuenta stock, se envía email al admin
- El admin gestiona el pedido desde `/admin/pedidos` (cambiar estado, confirmar costo de envío)
- Si el admin pasa un pedido a `rechazado` o `cancelado`, el stock se repone automáticamente
- El cliente puede enviar el resumen del pedido por WhatsApp desde la página de confirmación
- **No hay emails automáticos por cambio de estado** — la comunicación post-pedido es por WhatsApp

## Middleware
- `es_admin` — alias registrado en `bootstrap/app.php`. Verifica `Auth::user()->es_admin === true`
- Rutas admin protegidas con `middleware(['auth', 'es_admin'])`

## Paleta de colores (hex)
| Uso | Color |
|---|---|
| Verde principal | `#386641` |
| Verde claro / acento | `#a7c957` |
| Marrón oscuro / texto | `#2c1a0e` |
| Marrón medio | `#8b5e3c` |
| Fondo crema | `#faf6f0` |
| Fondo crema oscuro | `#f0e9de` |
| Borde / separador | `#d4b896` |

## Tipografía (CDN en layout)
- Títulos: `DM Serif Display` → `style="font-family: 'DM Serif Display', serif;"`
- Cuerpo: `Raleway` → aplicado en `body` vía CSS inline en el layout
- Íconos: Font Awesome 6 (CDN)

## Variables de entorno propias
```env
TILEO_WHATSAPP=5492324123456   # número sin + ni espacios (formato internacional)
ADMIN_EMAIL=admin@tileo.com    # recibe el email al crearse cada pedido
```

## Seeder
- Admin: `admin@tileo.com` / `tileo2024`
- 5 productos de prueba con `stock=10` y `precio=0` (completar desde `/admin/productos`)

## Comandos frecuentes
```bash
# Servidor de desarrollo
php artisan serve

# Compilar assets (Tailwind/Alpine)
npm run dev

# Migraciones
php artisan migrate

# Seeders
php artisan db:seed

# Limpiar caché de config (necesario tras editar .env)
php artisan config:clear

# Crear componente Livewire
php artisan make:livewire NombreComponente

# Crear modelo (sin -m, la migración se crea por separado)
php artisan make:model NombreModelo
php artisan make:migration create_nombre_table
```

## Notas de entorno
- El proyecto corre en XAMPP: `http://localhost/tileo/public` o con `php artisan serve`
- El archivo `.env` usa `DB_DATABASE=tileo`
- Apache mod_rewrite debe estar activo para que funcionen las rutas de Laravel
- El layout `app.blade.php` incluye el componente `Carrito` globalmente — no agregarlo en vistas individuales
