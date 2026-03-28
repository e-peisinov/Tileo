<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $titulo ?? 'Panel Admin — Tileo' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital,wght@0,400;1,400&family=Raleway:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Raleway', sans-serif; }
        h1, h2, h3, h4 { font-family: 'DM Serif Display', serif; }
        [x-cloak] { display: none !important; }

        .admin-sidebar {
            width: 256px;
            min-height: 100vh;
            background-color: #2c1a0e;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 40;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }
        .admin-main {
            margin-left: 256px;
            min-height: 100vh;
            background-color: #faf6f0;
        }
        @media (max-width: 1023px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.abierto { transform: translateX(0); }
            .admin-main { margin-left: 0; }
        }

        .nav-admin-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            color: rgba(212,184,150,0.7);
            transition: all 0.2s;
            text-decoration: none;
        }
        .nav-admin-link:hover {
            background-color: rgba(212,184,150,0.1);
            color: #d4b896;
        }
        .nav-admin-link.activo {
            background-color: rgba(56,102,65,0.25);
            color: #a7c957;
        }
        .nav-admin-link i { width: 16px; text-align: center; font-size: 12px; }
    </style>
</head>
<body class="antialiased" x-data="{ sidebarAbierto: false }">

    {{-- OVERLAY MOBILE --}}
    <div class="fixed inset-0 bg-black/50 z-30 lg:hidden"
         x-show="sidebarAbierto"
         x-cloak
         @click="sidebarAbierto = false"></div>

    {{-- SIDEBAR --}}
    <aside class="admin-sidebar" :class="sidebarAbierto ? 'abierto' : ''">

        {{-- Logo --}}
        <div class="px-5 py-5 border-b border-[#d4b896]/10">
            <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex flex-col leading-tight">
                <span class="text-xl text-[#a7c957]" style="font-family: 'DM Serif Display', serif;">Tileo</span>
                <span class="text-[9px] tracking-[0.2em] uppercase text-[#d4b896]/40">Panel Admin</span>
            </a>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-5">

            <div>
                <p class="text-[9px] tracking-[0.25em] uppercase text-[#d4b896]/30 font-semibold px-3 mb-1.5">Pedidos</p>
                <a href="{{ route('admin.pedidos') }}" wire:navigate class="nav-admin-link {{ request()->routeIs('admin.pedidos*') ? 'activo' : '' }}">
                    <i class="fa-solid fa-box"></i> Pedidos
                </a>
                <a href="{{ route('admin.clientes') }}" wire:navigate class="nav-admin-link {{ request()->routeIs('admin.clientes') ? 'activo' : '' }}">
                    <i class="fa-solid fa-users"></i> Clientes
                </a>
            </div>

            <div>
                <p class="text-[9px] tracking-[0.25em] uppercase text-[#d4b896]/30 font-semibold px-3 mb-1.5">Catálogo</p>
                <a href="{{ route('admin.productos') }}" wire:navigate class="nav-admin-link {{ request()->routeIs('admin.productos*') ? 'activo' : '' }}">
                    <i class="fa-solid fa-seedling"></i> Productos
                </a>
                <a href="{{ route('admin.categorias') }}" wire:navigate class="nav-admin-link {{ request()->routeIs('admin.categorias') ? 'activo' : '' }}">
                    <i class="fa-solid fa-tags"></i> Categorías
                </a>
                <a href="{{ route('admin.maderas') }}" wire:navigate class="nav-admin-link {{ request()->routeIs('admin.maderas') ? 'activo' : '' }}">
                    <i class="fa-solid fa-box-open"></i> Maderas
                </a>
            </div>

            <div>
                <p class="text-[9px] tracking-[0.25em] uppercase text-[#d4b896]/30 font-semibold px-3 mb-1.5">Marketing</p>
                <a href="{{ route('admin.banners') }}" wire:navigate class="nav-admin-link {{ request()->routeIs('admin.banners') ? 'activo' : '' }}">
                    <i class="fa-solid fa-rectangle-ad"></i> Banners
                </a>
                <a href="{{ route('admin.codigos-descuento') }}" wire:navigate class="nav-admin-link {{ request()->routeIs('admin.codigos-descuento') ? 'activo' : '' }}">
                    <i class="fa-solid fa-ticket"></i> Descuentos
                </a>
                <a href="{{ route('admin.resenas') }}" wire:navigate class="nav-admin-link {{ request()->routeIs('admin.resenas') ? 'activo' : '' }}">
                    <i class="fa-solid fa-star"></i> Reseñas
                </a>
            </div>

            <div>
                <p class="text-[9px] tracking-[0.25em] uppercase text-[#d4b896]/30 font-semibold px-3 mb-1.5">Contenido</p>
                <a href="{{ route('admin.contenidos') }}" wire:navigate class="nav-admin-link {{ request()->routeIs('admin.contenidos') ? 'activo' : '' }}">
                    <i class="fa-solid fa-file-alt"></i> Páginas
                </a>
            </div>

            <div>
                <p class="text-[9px] tracking-[0.25em] uppercase text-[#d4b896]/30 font-semibold px-3 mb-1.5">Sistema</p>
                <a href="{{ route('admin.reportes') }}" wire:navigate class="nav-admin-link {{ request()->routeIs('admin.reportes') ? 'activo' : '' }}">
                    <i class="fa-solid fa-chart-bar"></i> Reportes
                </a>
                <a href="{{ route('admin.usuarios') }}" wire:navigate class="nav-admin-link {{ request()->routeIs('admin.usuarios') ? 'activo' : '' }}">
                    <i class="fa-solid fa-user-cog"></i> Usuarios
                </a>
                <a href="{{ route('admin.configuracion') }}" wire:navigate class="nav-admin-link {{ request()->routeIs('admin.configuracion') ? 'activo' : '' }}">
                    <i class="fa-solid fa-gear"></i> Configuración
                </a>
            </div>

        </nav>

        {{-- Footer sidebar --}}
        <div class="px-3 py-4 border-t border-[#d4b896]/10 space-y-1">
            <a href="{{ url('/') }}" target="_blank" class="nav-admin-link">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Ver sitio
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-admin-link w-full text-left" style="color: rgba(192,57,43,0.7);">
                    <i class="fa-solid fa-sign-out-alt"></i> Cerrar sesión
                </button>
            </form>
        </div>

    </aside>

    {{-- MAIN --}}
    <div class="admin-main">

        {{-- Topbar --}}
        <div class="bg-white border-b border-[#d4b896]/30 px-6 py-3 flex items-center justify-between sticky top-0 z-20">
            <button class="lg:hidden text-[#8b5e3c] p-1" @click="sidebarAbierto = !sidebarAbierto">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
            <a href="{{ route('admin.dashboard') }}" wire:navigate class="hidden lg:flex items-center gap-2 text-[13px] text-[#2c1a0e]/50 hover:text-[#386641] transition-colors">
                <i class="fa-solid fa-house text-xs"></i> Dashboard
            </a>
            <div class="flex items-center gap-3">
                <span class="text-xs text-[#8b5e3c]/70">
                    <i class="fa-solid fa-user-circle mr-1"></i>
                    {{ Auth::user()->name ?? 'Admin' }}
                </span>
            </div>
        </div>

        {{-- Contenido --}}
        <div class="p-6">
            {{ $slot }}
        </div>

    </div>

    @stack('scripts')

</body>
</html>
