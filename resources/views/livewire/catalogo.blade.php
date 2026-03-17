<div>
    {{-- ENCABEZADO --}}
    <section class="bg-[#f0e9de] border-b border-[#d4b896]/30 py-16 px-4 text-center">
        <div class="max-w-2xl mx-auto">
            <p class="text-[#8b5e3c]/70 tracking-[0.3em] uppercase text-[11px] font-medium mb-3">
                Lo que ofrecemos
            </p>
            <h1 class="text-5xl sm:text-6xl text-[#2c1a0e]"
                style="font-family: 'DM Serif Display', serif;">
                Catálogo
            </h1>
            <p class="mt-4 text-sm text-[#2c1a0e]/50 leading-relaxed max-w-md mx-auto">
                Todas nuestras especias y condimentos, elaborados artesanalmente y presentados en tubos de vidrio con corcho.
            </p>
        </div>
    </section>
    
    
    {{-- CATÁLOGO CON FILTROS --}}
    @php
        $productos = [
            ['nombre' => 'Nuez Moscada',     'imagen' => 'WhatsApp Image 2026-03-17 at 13.23.44.jpeg',     'descripcion' => 'De aroma intenso y sabor cálido, la nuez moscada es un clásico de la cocina. Ideal para bechameles, purés, pastas y postres.',                                                        'categoria' => 'especias', 'usos' => ['Bechamel', 'Purés', 'Postres'],          'color' => '#8b5e3c'],
            ['nombre' => 'Paprika',          'imagen' => 'WhatsApp Image 2026-03-17 at 13.23.44 (3).jpeg', 'descripcion' => 'Elaborada con pimientos rojos secos y molidos. Aporta color vibrante y sabor ahumado. Perfecta para carnes, arroces y marinadas.',                                               'categoria' => 'especias', 'usos' => ['Carnes', 'Arroces', 'Marinadas'],        'color' => '#c0392b'],
            ['nombre' => 'Pimienta Negra',   'imagen' => 'WhatsApp Image 2026-03-17 at 13.23.44 (4).jpeg', 'descripcion' => 'La reina de las especias. Clásica e imprescindible, realza el sabor de carnes, salsas, ensaladas y todo tipo de preparaciones saladas.',                                        'categoria' => 'especias', 'usos' => ['Carnes', 'Salsas', 'Ensaladas'],         'color' => '#2c1a0e'],
            ['nombre' => 'Ají Molido Merken','imagen' => 'WhatsApp Image 2026-03-17 at 13.23.45.jpeg',     'descripcion' => 'Mezcla mapuche tradicional con ají cacho de cabra ahumado y cilantro tostado. Sabor profundo, picante moderado y aroma único.',                                                'categoria' => 'picantes', 'usos' => ['Asados', 'Pastas', 'Salsas picantes'],  'color' => '#a93226'],
            ['nombre' => 'Pimentón Dulce',   'imagen' => 'WhatsApp Image 2026-03-17 at 13.23.45 (2).jpeg', 'descripcion' => 'Suave, aromático y con un hermoso color rojo intenso. Aporta dulzura y profundidad a guisos, empanadas, chorizo y cualquier plato casero.',                                    'categoria' => 'especias', 'usos' => ['Guisos', 'Empanadas', 'Chorizo'],        'color' => '#c0392b'],
        ];
    @endphp
    
    <section class="bg-[#faf6f0] py-16 px-4"
             x-data="{ categoriaActiva: 'todos' }">
        <div class="max-w-6xl mx-auto">
    
            {{-- Filtros --}}
            <div class="fade-in flex flex-wrap justify-center gap-2 mb-14">
                @foreach (['todos' => 'Todos', 'especias' => 'Especias', 'picantes' => 'Picantes'] as $clave => $etiqueta)
                    <button @click="categoriaActiva = '{{ $clave }}'"
                            :class="categoriaActiva === '{{ $clave }}'
                                ? 'bg-[#386641] text-[#faf6f0] border-[#386641]'
                                : 'text-[#2c1a0e]/60 border-[#2c1a0e]/15 hover:border-[#386641]/40 hover:text-[#386641]'"
                            class="border px-6 py-2 text-[12px] tracking-wider font-medium rounded-full transition-all duration-300">
                        {{ $etiqueta }}
                    </button>
                @endforeach
            </div>
    
            {{-- Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($productos as $i => $producto)
                    <article x-show="categoriaActiva === 'todos' || categoriaActiva === '{{ $producto['categoria'] }}'"
                             x-transition:enter="transition ease-out duration-400"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="fade-in stagger-{{ ($i % 3) + 1 }} flex flex-col bg-[#faf6f0]
                                    border border-[#d4b896]/25 hover:border-[#d4b896]/50
                                    hover:shadow-lg hover:-translate-y-1 transition-all duration-400">
    
                        <div class="relative h-64 overflow-hidden group">
                            <img src="{{ asset('imagenes/' . rawurlencode($producto['imagen'])) }}"
                                 alt="{{ $producto['nombre'] }}"
                                 class="w-full h-full object-cover object-center transition-transform duration-600 group-hover:scale-105">
                            <div class="absolute top-3 left-3">
                                <span class="bg-[#faf6f0]/90 text-[#8b5e3c] text-[10px] tracking-[0.18em] uppercase font-medium px-2.5 py-1">
                                    {{ $producto['categoria'] === 'picantes' ? 'Picante' : 'Especia' }}
                                </span>
                            </div>
                        </div>
    
                        <div class="p-6 flex flex-col gap-3 flex-1">
                            <div class="flex items-start justify-between gap-3">
                                <h2 class="text-2xl text-[#2c1a0e] leading-tight"
                                    style="font-family: 'DM Serif Display', serif;">
                                    {{ $producto['nombre'] }}
                                </h2>
                                <div class="w-2.5 h-2.5 rounded-full flex-shrink-0 mt-2 opacity-60"
                                     style="background-color: {{ $producto['color'] }}"></div>
                            </div>
    
                            <p class="text-sm text-[#2c1a0e]/55 leading-relaxed flex-1">
                                {{ $producto['descripcion'] }}
                            </p>
    
                            <div class="flex flex-wrap gap-1.5 mt-1">
                                @foreach ($producto['usos'] as $uso)
                                    <span class="text-[11px] bg-[#386641]/8 text-[#386641] px-2.5 py-1 font-medium">
                                        {{ $uso }}
                                    </span>
                                @endforeach
                            </div>
    
                            <a href="{{ route('contacto') }}"
                               class="mt-3 w-full text-center border border-[#386641]/40 text-[#386641] py-2.5 text-[12px] tracking-wider font-medium
                                      hover:bg-[#386641] hover:text-[#faf6f0] hover:border-[#386641] transition-all duration-300">
                                Consultá por este producto
                            </a>
                        </div>
    
                    </article>
                @endforeach
            </div>
    
        </div>
    </section>
    
    
    {{-- BANNER INFERIOR --}}
    <section class="relative overflow-hidden h-64">
        <img src="{{ asset('imagenes/' . rawurlencode('WhatsApp Image 2026-03-17 at 13.24.12 (2).jpeg')) }}"
             alt="Colección Tileo"
             class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-[#1a0f05]/60 flex items-center justify-center">
            <div class="text-center fade-in">
                <p class="text-[#d4b896]/80 tracking-[0.28em] uppercase text-[11px] font-medium mb-4">
                    ¿Querés llevarlos a tu cocina?
                </p>
                <a href="{{ route('contacto') }}"
                   class="inline-block border border-[#d4b896]/50 text-[#d4b896] px-8 py-3 text-[13px] tracking-wider font-medium
                          hover:bg-[#d4b896] hover:text-[#2c1a0e] transition-all duration-300 hover:-translate-y-0.5">
                    Escribinos
                </a>
            </div>
        </div>
    </section>
</div>
