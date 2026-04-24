<div titulo="Tileo — Hierbas & Especias Artesanales">

    {{-- ============================================================
         HERO
    ============================================================ --}}
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">

        {{-- Imagen de fondo --}}
        <div class="absolute inset-0">
            <img src="{{ asset('imagenes/' . rawurlencode('WhatsApp Image 2026-03-17 at 13.24.12 (3).jpeg')) }}"
                 alt="Tileo — colección de especias"
                 class="w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-[#1a0f05]/65"></div>
        </div>

        {{-- Contenido --}}
        <div class="relative z-10 text-center px-4 max-w-3xl mx-auto">
            <p class="hero-enter hero-delay-1 text-[#d4b896] tracking-[0.3em] uppercase text-xs font-semibold mb-4">
                Mercedes · Buenos Aires
            </p>
            <h1 class="hero-enter hero-delay-2 text-7xl sm:text-8xl font-bold text-[#faf6f0] mb-4 leading-none"
                style="font-family: 'DM Serif Display', serif; letter-spacing: -0.01em;">
                Tileo
            </h1>
            <p class="hero-enter hero-delay-3 text-xl sm:text-2xl text-[#d4b896] mb-3"
               style="font-family: 'DM Serif Display', serif;">
                Hierbas, especias y condimentos artesanales
            </p>
            <p class="hero-enter hero-delay-4 text-sm text-[#d4b896]/70 mb-10 tracking-wide">
                Elaborados con dedicación · Presentados en tubos de vidrio con corcho
            </p>
            <div class="hero-enter hero-delay-4 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#productos"
                   class="bg-[#386641] text-[#faf6f0] px-8 py-3 text-sm uppercase tracking-widest font-semibold rounded-lg hover:bg-[#2d5235] transition-colors duration-300">
                    Ver productos
                </a>
                <a href="{{ url('/contacto') }}"
                   class="border border-[#d4b896]/60 text-[#d4b896] px-8 py-3 text-sm uppercase tracking-widest font-semibold rounded-lg hover:border-[#d4b896] hover:text-[#faf6f0] transition-colors duration-300">
                    Contactanos
                </a>
            </div>
        </div>
    </section>


    {{-- ============================================================
         BANNERS VIGENTES
    ============================================================ --}}
    @if($banners->isNotEmpty())
        @foreach($banners as $banner)
            <div class="w-full py-3 px-4 text-center"
                 style="background-color: {{ $banner->color_fondo ?: '#386641' }};">
                <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-4">
                    <p class="text-sm font-semibold" style="color: #faf6f0;">
                        {{ $banner->titulo }}
                    </p>
                    @if($banner->imagen)
                        <img src="{{ asset('imagenes/' . rawurlencode($banner->imagen)) }}"
                             alt="Banner"
                             class="h-20 object-contain">
                    @endif
                    @if($banner->subtitulo)
                        <span class="hidden sm:inline text-sm" style="color: rgba(250,246,240,0.45);">·</span>
                        <p class="text-xs" style="color: rgba(250,246,240,0.75);">{{ $banner->subtitulo }}</p>
                    @endif
                    @if($banner->url_destino && $banner->texto_boton)
                        <a href="{{ $banner->url_destino }}"
                           class="text-xs border px-4 py-1 hover:bg-white/10 transition-colors duration-200"
                           style="border-color: rgba(250,246,240,0.45); color: #faf6f0;">
                            {{ $banner->texto_boton }} →
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    @endif


    {{-- ============================================================
         PROPUESTA DE VALOR
    ============================================================ --}}
    <section id="propuesta" class="bg-[#faf6f0] py-20 px-4">
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-12 text-center">

            <div class="flex flex-col items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-[#386641]/10 flex items-center justify-center">
                    <i class="fa-solid fa-seedling text-[#386641] text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-[#2c1a0e]">100% Artesanal</h3>
                <p class="text-sm text-[#2c1a0e]/60 leading-relaxed">
                    Cada producto es seleccionado y preparado a mano, sin conservantes ni procesos industriales.
                </p>
            </div>

            <div class="flex flex-col items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-[#386641]/10 flex items-center justify-center">
                    <i class="fa-solid fa-flask text-[#386641] text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-[#2c1a0e]">Presentación única</h3>
                <p class="text-sm text-[#2c1a0e]/60 leading-relaxed">
                    Envasados en tubos de vidrio con tapa de corcho, ideales para regalo o para la cocina de todos los días.
                </p>
            </div>

            <div class="flex flex-col items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-[#386641]/10 flex items-center justify-center">
                    <i class="fa-solid fa-location-dot text-[#386641] text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-[#2c1a0e]">De Mercedes al mundo</h3>
                <p class="text-sm text-[#2c1a0e]/60 leading-relaxed">
                    Un emprendimiento familiar con raíces bonaerenses y el sabor de los ingredientes de siempre.
                </p>
            </div>

        </div>
    </section>


    {{-- ============================================================
         PRODUCTOS DESTACADOS
    ============================================================ --}}
    <section id="productos" class="bg-[#f0e9de] py-20 px-4">
        <div class="max-w-6xl mx-auto">

            {{-- Encabezado --}}
            <div class="text-center mb-12">
                <p class="text-[#8b5e3c] tracking-[0.25em] uppercase text-xs font-semibold mb-3">
                    Lo que ofrecemos
                </p>
                <h2 class="text-4xl sm:text-5xl text-[#2c1a0e]"
                    style="font-family: 'DM Serif Display', serif;">
                    Nuestros productos
                </h2>
            </div>

            {{-- Grid de productos desde DB --}}
            @if($productosDestacados->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                    @foreach($productosDestacados as $producto)
                        <div class="group bg-[#faf6f0] overflow-hidden rounded-xl border border-[#d4b896]/25 hover:border-[#d4b896]/50 hover:shadow-lg hover:-translate-y-1 transition-all duration-400">
                            {{-- Imagen --}}
                            <div class="relative h-72 overflow-hidden">
                                @if($producto->imagen)
                                    <img src="{{ asset('imagenes/' . rawurlencode($producto->imagen)) }}"
                                         alt="{{ $producto->nombre }}"
                                         class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full bg-[#f0e9de] flex items-center justify-center">
                                        <i class="fa-solid fa-leaf text-5xl text-[#386641]/30"></i>
                                    </div>
                                @endif
                                {{-- Overlay con descripción al hover --}}
                                <div class="absolute inset-0 bg-[#1a0f05]/70 flex items-end p-5
                                            opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <p class="text-[#faf6f0]/90 text-sm leading-relaxed">
                                        {{ $producto->descripcion }}
                                    </p>
                                </div>
                            </div>

                            {{-- Info --}}
                            <div class="p-5 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] uppercase tracking-widest text-[#8b5e3c] font-semibold">
                                        {{ $producto->categoria->nombre }}
                                    </span>
                                    <h3 class="text-lg font-semibold text-[#2c1a0e] mt-0.5"
                                        style="font-family: 'DM Serif Display', serif;">
                                        {{ $producto->nombre }}
                                    </h3>
                                </div>
                                @if($producto->stock > 0)
                                    <a href="{{ route('detalle-producto', $producto->id) }}" wire:navigate
                                       class="text-[11px] text-[#386641] border border-[#386641]/30 px-3 py-1.5 hover:bg-[#386641] hover:text-white transition-all duration-200 flex-shrink-0">
                                        Ver →
                                    </a>
                                @else
                                    <span class="text-[11px] text-[#c0392b]/70">Sin stock</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-[#8b5e3c]/60 text-sm">Próximamente nuevos productos.</p>
                </div>
            @endif

            <div class="text-center">
                <a href="{{ route('catalogo') }}" wire:navigate
                   class="inline-block border border-[#2c1a0e]/20 text-[#2c1a0e]/70 px-8 py-3 text-xs uppercase tracking-widest font-semibold rounded-lg hover:border-[#386641] hover:text-[#386641] transition-all duration-300">
                    Ver catálogo completo
                </a>
            </div>

        </div>
    </section>


    {{-- ============================================================
         SECCIÓN PRESENTACIÓN — EL TUBO TILEO
    ============================================================ --}}
    <section class="bg-[#faf6f0] py-20 px-4">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-0 overflow-hidden">

            {{-- Imagen --}}
            <div class="h-80 md:h-auto overflow-hidden">
                <img src="{{ asset('imagenes/' . rawurlencode('WhatsApp Image 2026-03-17 at 13.24.12.jpeg')) }}"
                     alt="Display artesanal Tileo"
                     class="w-full h-full object-cover object-center">
            </div>

            {{-- Texto --}}
            <div class="bg-[#386641] text-[#faf6f0] flex flex-col justify-center px-10 py-14 gap-6">
                <p class="text-[#a7c957] tracking-[0.25em] uppercase text-xs font-semibold">
                    Nuestra identidad
                </p>
                <h2 class="text-4xl leading-tight"
                    style="font-family: 'DM Serif Display', serif;">
                    El tubo como packaging
                </h2>
                <p class="text-[#faf6f0]/80 text-sm leading-relaxed">
                    Cada especia Tileo llega en un tubo de vidrio transparente con tapa de corcho natural.
                    Una presentación que cuida el producto, lo muestra en su estado puro y transforma
                    cada compra en un regalo especial.
                </p>
                <p class="text-[#faf6f0]/80 text-sm leading-relaxed">
                    Los soportes de madera artesanales permiten exhibirlos en tu cocina con la misma
                    estética que los distingue en cada feria y evento.
                </p>
                <a href="{{ url('/catalogo') }}"
                   class="self-start border border-[#a7c957] text-[#a7c957] px-6 py-2.5 text-xs uppercase tracking-widest font-semibold rounded-lg hover:bg-[#a7c957] hover:text-[#2c1a0e] transition-all duration-300 mt-2">
                    Ver catálogo completo
                </a>
            </div>

        </div>
    </section>


    {{-- ============================================================
         GALERÍA AMBIENTE
    ============================================================ --}}
    <section class="grid grid-cols-2 md:grid-cols-4 h-64 md:h-80">
        @foreach ([
            'WhatsApp Image 2026-03-17 at 13.24.12 (1).jpeg',
            'WhatsApp Image 2026-03-17 at 13.24.12 (2).jpeg',
            'WhatsApp Image 2026-03-17 at 13.24.12 (4).jpeg',
            'WhatsApp Image 2026-03-17 at 13.23.45 (1).jpeg',
        ] as $img)
            <div class="overflow-hidden">
                <img src="{{ asset('imagenes/' . rawurlencode($img)) }}"
                     alt="Tileo"
                     class="w-full h-full object-cover object-center hover:scale-105 transition-transform duration-500">
            </div>
        @endforeach
    </section>


    {{-- ============================================================
         CTA FINAL
    ============================================================ --}}
    <section class="bg-[#2c1a0e] py-20 px-4 text-center">
        <div class="max-w-xl mx-auto flex flex-col items-center gap-6">
            <p class="text-[#d4b896]/60 tracking-[0.25em] uppercase text-xs font-semibold">
                ¿Te gustó lo que viste?
            </p>
            <h2 class="text-4xl sm:text-5xl text-[#faf6f0]"
                style="font-family: 'DM Serif Display', serif;">
                Llevá Tileo a tu mesa
            </h2>
            <p class="text-sm text-[#d4b896]/70 leading-relaxed">
                Escribinos para consultas, pedidos o para encontrarnos en la próxima feria.
            </p>
            <a href="{{ url('/contacto') }}"
               class="bg-[#386641] text-[#faf6f0] px-10 py-3 text-sm uppercase tracking-widest font-semibold rounded-lg hover:bg-[#2d5235] transition-colors duration-300 mt-2">
                Contactanos
            </a>
        </div>
    </section>

</div>
