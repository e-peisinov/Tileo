<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $titulo ?? 'Tileo — Hierbas & Especias Artesanales' }}</title>

    {{-- SEO --}}
    <meta name="description" content="{{ $descripcion ?? 'Hierbas, especias y condimentos artesanales elaborados con dedicación en Mercedes, Buenos Aires.' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $titulo ?? 'Tileo — Hierbas & Especias Artesanales' }}">
    <meta property="og:description" content="{{ $descripcion ?? 'Hierbas, especias y condimentos artesanales elaborados con dedicación en Mercedes, Buenos Aires.' }}">
    @isset($ogImagen)
    <meta property="og:image" content="{{ $ogImagen }}">
    @endisset
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital,wght@0,400;1,400&family=Raleway:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Raleway', sans-serif; }
        h1, h2, h3, h4 { font-family: 'DM Serif Display', serif; }

        [x-cloak] { display: none !important; }

        /* ── Header shadow al hacer scroll ── */
        header { transition: box-shadow 0.4s ease, background-color 0.4s ease; }
        header.con-sombra { box-shadow: 0 2px 28px rgba(44, 26, 14, 0.10); }

        /* ── Nav link: subrayado que crece desde el centro ── */
        .nav-link {
            position: relative;
            padding-bottom: 3px;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 1.5px;
            background-color: #386641;
            transition: width 0.35s ease, left 0.35s ease;
        }
        .nav-link:hover::after,
        .nav-link.activo::after {
            width: 100%;
            left: 0;
        }
        .nav-link.activo { color: #386641; }

        /* ── Animaciones de entrada al scroll ── */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.75s ease, transform 0.75s ease;
        }
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .fade-desde-izq {
            opacity: 0;
            transform: translateX(-30px);
            transition: opacity 0.75s ease, transform 0.75s ease;
        }
        .fade-desde-izq.visible {
            opacity: 1;
            transform: translateX(0);
        }
        .fade-desde-der {
            opacity: 0;
            transform: translateX(30px);
            transition: opacity 0.75s ease, transform 0.75s ease;
        }
        .fade-desde-der.visible {
            opacity: 1;
            transform: translateX(0);
        }

        /* Delays para stagger */
        .stagger-1 { transition-delay: 0.10s; }
        .stagger-2 { transition-delay: 0.20s; }
        .stagger-3 { transition-delay: 0.30s; }
        .stagger-4 { transition-delay: 0.40s; }
        .stagger-5 { transition-delay: 0.50s; }

        /* ── Hero: animación de entrada al cargar la página ── */
        @keyframes heroSlideUp {
            from { opacity: 0; transform: translateY(22px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .hero-enter   { animation: heroSlideUp 0.9s ease forwards; }
        .hero-delay-1 { animation-delay: 0.25s; opacity: 0; }
        .hero-delay-2 { animation-delay: 0.5s;  opacity: 0; }
        .hero-delay-3 { animation-delay: 0.75s; opacity: 0; }
        .hero-delay-4 { animation-delay: 1.0s;  opacity: 0; }

    </style>
</head>
<body class="antialiased bg-[#faf6f0] text-[#2c1a0e] flex flex-col min-h-screen">

    {{-- HEADER --}}
    <header class="bg-[#f0e9de] border-b border-[#d4b896]/40 sticky top-0 z-50"
            x-data="{ menuAbierto: false }">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ url('/') }}" wire:navigate class="flex flex-col leading-tight">
                    <span class="text-2xl font-semibold text-[#386641]"
                          style="font-family: 'DM Serif Display', serif; letter-spacing: 0.02em;">
                        Tileo
                    </span>
                    <span class="text-[9px] tracking-[0.22em] uppercase text-[#8b5e3c]/80 font-light">
                        Hierbas &amp; Especias
                    </span>
                </a>

                {{-- Navegación desktop --}}
                <nav class="hidden md:flex items-center gap-7">
                    <a href="{{ url('/') }}" wire:navigate
                       class="nav-link text-[14px] font-medium text-[#2c1a0e]/70 hover:text-[#2c1a0e] transition-colors duration-300">
                        Inicio
                    </a>
                    <a href="{{ route('catalogo') }}" wire:navigate
                       class="nav-link text-[14px] font-medium text-[#2c1a0e]/70 hover:text-[#2c1a0e] transition-colors duration-300">
                        Catálogo
                    </a>
                    <a href="{{ route('nosotros') }}" wire:navigate
                       class="nav-link text-[14px] font-medium text-[#2c1a0e]/70 hover:text-[#2c1a0e] transition-colors duration-300">
                        Nosotros
                    </a>
                    <a href="{{ route('preguntas') }}" wire:navigate
                       class="nav-link text-[14px] font-medium text-[#2c1a0e]/70 hover:text-[#2c1a0e] transition-colors duration-300">
                        Preguntas
                    </a>
                    <a href="{{ route('contacto') }}" wire:navigate
                       class="text-[14px] font-medium text-[#386641] border border-[#386641]/50 px-5 py-1.5 rounded-full hover:bg-[#386641] hover:text-[#faf6f0] hover:border-[#386641] transition-all duration-300">
                        Contacto
                    </a>
                    @if (Auth::check())
                        <div class="w-px h-4 bg-[#d4b896] self-center"></div>
                        <a href="{{ route('admin.dashboard') }}" wire:navigate class="nav-link text-[14px] font-medium text-[#2c1a0e]/70 hover:text-[#2c1a0e] transition-colors duration-300">
                            Dashboard Admin
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="text-[14px] font-medium text-red-600 hover:text-red-800 transition-all duration-300">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Cerrar Sesión</span>
                            </button>
                        </form>
                    @endif
                </nav>

                {{-- Hamburguesa (mobile) --}}
                <button class="md:hidden flex flex-col gap-1.5 p-2 focus:outline-none"
                        @click="menuAbierto = !menuAbierto"
                        aria-label="Abrir menú">
                    <span class="block w-5 h-px bg-[#386641] transition-all duration-300"
                          :class="menuAbierto ? 'rotate-45 translate-y-[7px]' : ''"></span>
                    <span class="block w-5 h-px bg-[#386641] transition-all duration-300"
                          :class="menuAbierto ? 'opacity-0 scale-x-0' : ''"></span>
                    <span class="block w-5 h-px bg-[#386641] transition-all duration-300"
                          :class="menuAbierto ? '-rotate-45 -translate-y-[7px]' : ''"></span>
                </button>
            </div>
        </div>

        {{-- Menú mobile --}}
        <div class="md:hidden bg-[#f0e9de] border-t border-[#d4b896]/40"
             x-show="menuAbierto"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0 -translate-y-3"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-3"
             x-cloak>
            <nav class="flex flex-col px-6 py-5 gap-5">
                <a href="{{ url('/') }}" wire:navigate
                   class="text-sm font-medium text-[#2c1a0e]/70 hover:text-[#386641] transition-colors"
                   @click="menuAbierto = false">Inicio</a>
                <a href="{{ url('/catalogo') }}" wire:navigate
                   class="text-sm font-medium text-[#2c1a0e]/70 hover:text-[#386641] transition-colors"
                   @click="menuAbierto = false">Catálogo</a>
                <a href="{{ url('/nosotros') }}" wire:navigate
                   class="text-sm font-medium text-[#2c1a0e]/70 hover:text-[#386641] transition-colors"
                   @click="menuAbierto = false">Nosotros</a>
                <a href="{{ url('/preguntas') }}" wire:navigate
                   class="text-sm font-medium text-[#2c1a0e]/70 hover:text-[#386641] transition-colors"
                   @click="menuAbierto = false">Preguntas</a>
                <a href="{{ url('/contacto') }}" wire:navigate
                   class="self-start text-sm font-medium text-[#386641] border border-[#386641]/50 px-5 py-1.5 rounded-full hover:bg-[#386641] hover:text-[#faf6f0] transition-all duration-300"
                   @click="menuAbierto = false">Contacto</a>
                @if (Auth::check())
                    <div class="w-full h-px bg-[#d4b896]/40"></div>
                    <a href="{{ route('admin.dashboard') }}" wire:navigate
                       class="text-sm font-medium text-[#2c1a0e]/70 hover:text-[#386641] transition-colors"
                       @click="menuAbierto = false">
                        <i class="fas fa-gauge-high mr-1"></i> Dashboard Admin
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors">
                            <i class="fas fa-sign-out-alt mr-1"></i> Cerrar Sesión
                        </button>
                    </form>
                @endif
            </nav>
        </div>
    </header>

    {{-- CONTENIDO --}}
    <main class="flex-1">
        {{ $slot }}
    </main>

    {{-- BOTÓN FLOTANTE WHATSAPP --}}
    <a href="https://wa.me/{{ preg_replace('/\D/', '', config('tileo.whatsapp', '')) }}"
       target="_blank"
       rel="noopener noreferrer"
       title="Consultanos por WhatsApp"
       class="fixed bottom-6 right-6 z-40 w-14 h-14 rounded-full flex items-center justify-center shadow-lg
              hover:scale-110 active:scale-95 transition-all duration-200"
       style="background-color: #25D366;">
        <i class="fa-brands fa-whatsapp text-white text-2xl"></i>
    </a>

    {{-- FOOTER --}}
    <footer class="bg-[#2c1a0e] text-[#d4b896]">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

                <div class="flex flex-col gap-3">
                    <span class="text-2xl text-[#a7c957]"
                          style="font-family: 'DM Serif Display', serif;">
                        Tileo
                    </span>
                    <p class="text-sm leading-relaxed text-[#d4b896]/70">
                        Hierbas, especias y condimentos artesanales elaborados con dedicación en Mercedes, Buenos Aires.
                    </p>
                    <p class="text-[10px] tracking-[0.22em] uppercase text-[#d4b896]/40 mt-1">
                        Del campo a tu mesa
                    </p>
                </div>

                <div class="flex flex-col gap-2">
                    <span class="text-xs tracking-[0.2em] uppercase text-[#a7c957]/80 font-medium mb-2">
                        Navegación
                    </span>
                    <a href="{{ url('/') }}" wire:navigate class="text-sm text-[#d4b896]/70 hover:text-[#a7c957] transition-colors duration-200">Inicio</a>
                    <a href="{{ url('/catalogo') }}" wire:navigate class="text-sm text-[#d4b896]/70 hover:text-[#a7c957] transition-colors duration-200">Catálogo</a>
                    <a href="{{ url('/nosotros') }}" wire:navigate class="text-sm text-[#d4b896]/70 hover:text-[#a7c957] transition-colors duration-200">Nosotros</a>
                    <a href="{{ url('/preguntas') }}" wire:navigate class="text-sm text-[#d4b896]/70 hover:text-[#a7c957] transition-colors duration-200">Preguntas frecuentes</a>
                    <a href="{{ url('/contacto') }}" wire:navigate class="text-sm text-[#d4b896]/70 hover:text-[#a7c957] transition-colors duration-200">Contacto</a>
                </div>

                <div class="flex flex-col gap-2">
                    <span class="text-xs tracking-[0.2em] uppercase text-[#a7c957]/80 font-medium mb-2">
                        Dónde encontrarnos
                    </span>
                    <p class="text-sm text-[#d4b896]/70">Mercedes, Buenos Aires</p>
                    <p class="text-sm text-[#d4b896]/70">Argentina</p>
                    <div class="flex gap-3 mt-2">
                        <a href="https://www.instagram.com/tileo.mercedes" target="_blank" rel="noopener noreferrer"
                           class="text-[#d4b896]/50 hover:text-[#a7c957] transition-colors duration-200"
                           title="Seguinos en Instagram">
                            <i class="fa-brands fa-instagram text-xl"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <div class="border-t border-[#d4b896]/10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p class="text-xs text-[#d4b896]/35">&copy; {{ date('Y') }} Tileo. Todos los derechos reservados.</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('terminos') }}" wire:navigate class="text-xs text-[#d4b896]/35 hover:text-[#d4b896]/60 transition-colors">Términos y condiciones</a>
                    <span class="text-[#d4b896]/20">·</span>
                    <a href="{{ route('privacidad') }}" wire:navigate class="text-xs text-[#d4b896]/35 hover:text-[#d4b896]/60 transition-colors">Privacidad</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // ── Inicialización por página (carga inicial + wire:navigate) ──
        function initPagina() {
            // Scroll animations con IntersectionObserver
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.fade-in, .fade-desde-izq, .fade-desde-der')
                    .forEach(el => observer.observe(el));

            // Hero parallax suave
            const heroBg = document.querySelector('.hero-parallax');
            if (heroBg && !heroBg._parallaxAdded) {
                heroBg._parallaxAdded = true;
                window.addEventListener('scroll', () => {
                    heroBg.style.transform = `translateY(${window.scrollY * 0.22}px)`;
                }, { passive: true });
            }

            // Nav link activo según URL actual
            const ruta = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('activo');
                const href = new URL(link.href).pathname;
                if (href === ruta || (href !== '/' && ruta.startsWith(href))) {
                    link.classList.add('activo');
                }
            });
        }

        // ── Header: sombra al hacer scroll (se registra una sola vez) ──
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (header) header.classList.toggle('con-sombra', window.scrollY > 15);
        }, { passive: true });

        // ── Ejecutar en carga inicial y en cada navegación wire:navigate ──
        document.addEventListener('DOMContentLoaded', initPagina);
        document.addEventListener('livewire:navigated', initPagina);

    </script>

    @stack('scripts')

</body>
</html>
