# CLAUDE.md — Proyecto Tileo

## Descripción del proyecto
**Tileo** es una página web para un emprendimiento de hierbas, especias y condimentos artesanales ubicado en Mercedes, Buenos Aires. El sitio permite a los clientes explorar el catálogo y realizar pedidos online sin necesidad de registrarse.

## Stack tecnológico
- **Backend:** Laravel 12
- **Frontend reactivo:** Livewire 3 + Livewire Volt + Livewire Flux
- **Estilos:** Tailwind CSS v3 (evitar `style=""` inline, usar clases Tailwind; colores hex inline cuando no hay clase disponible)
- **JS interactivo:** Alpine.js
- **Gráficos:** Chart.js 4.4.0 (CDN, usado en el dashboard admin)
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
    layouts/           # app.blade.php (layout principal con @livewire('carrito') y @stack('scripts'))
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
| `productos` | Productos (`nombre`, `descripcion`, `precio`, `stock`, `unidad`, `imagen`, `activo`, `destacado`, FK `categoria_id`) |
| `pedidos` | Pedidos de clientes. `numero_pedido` = TIL-0001 (auto). `estado` enum. `costo_envio` nullable (lo confirma el admin) |
| `pedido_items` | Ítems de cada pedido con snapshot de `nombre_producto` y `precio_unitario` |
| `pedido_historial_estados` | Historial de cambios de estado de cada pedido (`pedido_id`, `estado_anterior`, `estado_nuevo`, `nota`) |
| `configuraciones` | Configuración del sitio. Pares clave-valor tipados (`clave`, `valor`, `tipo`, `etiqueta`, `descripcion`). Claves: `tiempo_entrega`, `mensaje_vacaciones`, `cbu`, `alias_cbu`, `titular_cuenta` |

## Modelos existentes
- `Categoria` — `hasMany Producto`
- `Producto` — `belongsTo Categoria`, método `hayStock(int $cantidad)`. Campos booleanos: `activo`, `destacado`
- `Pedido` — genera `numero_pedido` en `booted()`, métodos `etiquetaEstado()`, `colorEstado()`, `colorParaEstado()` (estático), `etiquetaParaEstado()` (estático), `calcularTotal()`, relación `historial()` → `PedidoHistorialEstado`
- `PedidoItem` — snapshot de precio y nombre al momento del pedido
- `PedidoHistorialEstado` — registra cada cambio de estado: `pedido_id`, `estado_anterior`, `estado_nuevo`, `nota`
- `User` — campo `es_admin` bool
- `Configuracion` — almacén de configuración del sitio. Métodos estáticos `obtener(clave, porDefecto)` y `establecer(clave, valor)`. Tipo puede ser `texto`, `booleano` o `numero`

## Componentes y páginas existentes

### Públicos
| Componente | Ruta | Descripción |
|---|---|---|
| `Dashboard` | `/` | Página de inicio. Muestra productos con `destacado=true` desde DB (fallback: todos activos, máx. 6) |
| `Catalogo` | `/catalogo` | Catálogo con filtros por categoría, buscador en tiempo real (`$busqueda`), badge de stock bajo (≤5), paginación de 12. Despacha evento `producto-agregado` |
| `DetalleProducto` | `/producto/{producto}` | Detalle completo de un producto: imagen, descripción, precio, badge stock bajo, botón agregar, productos relacionados |
| `Nosotros` | `/nosotros` | Historia del emprendimiento + sección "Cómo usarlos" con 4 recetas de ejemplo |
| `Contacto` | `/contacto` | Formulario de contacto |
| `Preguntas` | `/preguntas` | 8 preguntas frecuentes en acordeones con Alpine.js |
| `Carrito` | (drawer global) | Incluido en el layout. Escucha `producto-agregado` via `#[On]`. Carrito basado en `session('carrito')`. Botón flotante tiene `data-carrito-btn` para la animación bounce |
| `Checkout` | `/checkout` | Formulario de pedido. Crea `Pedido` + `PedidoItem`, descuenta stock, envía email al admin y confirmación al cliente |
| `ConfirmacionPedido` | `/pedido/{numero}` | Resumen del pedido + botón WhatsApp + panel "¿Qué pasa ahora?" + botón rastrear pedido |
| `SeguimientoPedido` | `/seguimiento` | El cliente busca su pedido por número y ve estado actual + timeline de historial |

### Admin (requieren `auth` + `es_admin`)
| Componente | Ruta | Descripción |
|---|---|---|
| `Admin\Dashboard` | `/admin/` | Panel principal con estadísticas, alertas de stock bajo y gráfico de actividad de los últimos 7 días (Chart.js) |
| `Admin\GestionPedidos` | `/admin/pedidos` | Lista de pedidos con filtros |
| `Admin\DetallePedido` | `/admin/pedidos/{id}` | Detalle + cambiar estado + costo de envío + notas + timeline visual de historial. Al cambiar estado: registra historial y envía email al cliente |
| `Admin\GestionProductos` | `/admin/productos` | CRUD de productos con subida de imágenes, control de stock y toggle `destacado` (aparece en inicio) |
| `Admin\GestionCategorias` | `/admin/categorias` | CRUD de categorías |
| `Admin\GestionUsuarios` | `/admin/usuarios` | CRUD de usuarios: crear, editar, toggle `es_admin`, eliminar (con confirmación). No permite eliminar ni quitarse admin a uno mismo |
| `Admin\GestionConfiguracion` | `/admin/configuracion` | Edición de las claves de la tabla `configuraciones`: tiempo de entrega, mensaje de vacaciones, datos bancarios (CBU, alias, titular) |

## Carrito — funcionamiento
- Almacenado en `session('carrito')` como array indexado por `producto_id`
- El componente `Carrito` está incluido en `layouts/app.blade.php` con `@livewire('carrito')`
- Para agregar un producto desde cualquier componente: `$this->dispatch('producto-agregado', productoId: $id)`
- El carrito se vacía automáticamente al confirmar el checkout
- Al dispararse `producto-agregado`, el botón flotante del carrito hace un bounce (CSS animation `cartBounce` + JS listener en el layout que busca `[data-carrito-btn]`)
- **Importante:** no usar clases `fade-in` / `fade-desde-izq` / `fade-desde-der` en elementos renderizados por Livewire — el IntersectionObserver del layout no los re-observa tras un re-render, dejándolos invisibles

## Sistema de pedidos
- Al confirmar el checkout: se crea el pedido, se descuenta stock, se envía email al admin (`NuevoPedidoMail`) y confirmación al cliente (`ConfirmacionClienteMail`)
- El admin gestiona el pedido desde `/admin/pedidos` (cambiar estado, confirmar costo de envío)
- Si el admin pasa un pedido a `rechazado` o `cancelado`, el stock se repone automáticamente
- Cada cambio de estado queda registrado en `pedido_historial_estados` y dispara un email al cliente (`CambioEstadoMail`)
- El cliente puede ver el estado de su pedido en `/seguimiento` ingresando el número (ej. TIL-0001)
- El cliente puede enviar el resumen del pedido por WhatsApp desde la página de confirmación

## Mailables existentes
| Clase | Cuándo se envía | Destinatario |
|---|---|---|
| `NuevoPedidoMail` | Al confirmar el checkout | Admin (`ADMIN_EMAIL`) |
| `ConfirmacionClienteMail` | Al confirmar el checkout | Cliente (email del pedido) |
| `CambioEstadoMail` | Cuando el admin cambia el estado del pedido | Cliente (email del pedido) |
| `ContactoMail` | Al enviar el formulario de contacto | Admin (`ADMIN_EMAIL`) |

Templates en `resources/views/emails/`: `nuevo-pedido.blade.php`, `confirmacion-cliente.blade.php`, `cambio-estado.blade.php`, `contacto.blade.php`

## Middleware
- `es_admin` — alias registrado en `bootstrap/app.php`. Verifica `Auth::user()->es_admin === true`
- Rutas admin protegidas con `middleware(['auth', 'es_admin'])`

## Layout — características globales (`layouts/app.blade.php`)
- Header sticky con sombra al hacer scroll, nav link activo con subrayado animado
- Menú mobile con hamburguesa animada (Alpine.js)
- Scroll animations: clases `fade-in`, `fade-desde-izq`, `fade-desde-der` con IntersectionObserver (**no usar en componentes Livewire**)
- `@livewire('carrito')` incluido globalmente
- Botón flotante de WhatsApp (`fixed bottom-24 right-5`, lee número desde `config('tileo.whatsapp')`)
- `@stack('scripts')` antes del `</body>` — usar `@push('scripts')` en vistas que necesiten JS adicional (ej. Chart.js en admin)
- Animación `cartBounce` en el botón del carrito al dispararse `producto-agregado`

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
- Para que aparezcan en el inicio, marcarlos como `destacado=true` desde `/admin/productos`

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
