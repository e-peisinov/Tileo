<div>
    {{-- ENCABEZADO --}}
    <section class="relative h-[50vh] overflow-hidden flex items-end">
        <img src="{{ asset('imagenes/' . rawurlencode('WhatsApp Image 2026-03-17 at 13.24.12.jpeg')) }}"
             alt="Catálogo Tileo"
             class="absolute inset-0 w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-gradient-to-t from-[#1a0f05]/80 via-[#1a0f05]/30 to-transparent"></div>

        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-14 w-full">
            <p class="text-[#d4b896]/70 tracking-[0.3em] uppercase text-[11px] font-medium mb-3">
                Lo que ofrecemos
            </p>
            <h1 class="text-5xl sm:text-6xl text-[#faf6f0]"
                style="font-family: 'DM Serif Display', serif;">
                Catálogo
            </h1>
            <p class="mt-3 text-sm text-[#faf6f0]/60 leading-relaxed max-w-md">
                Todas nuestras especias y condimentos, elaborados artesanalmente y presentados en tubos de vidrio con corcho.
            </p>
        </div>
    </section>

    {{-- SECCIÓN MADERAS --}}
    @if(isset($maderas) && $maderas->count() > 0)
    <section id="maderas" class="bg-white border-b border-[#d4b896]/30 py-14 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-10">
                <p class="text-[#8b5e3c]/70 tracking-[0.3em] uppercase text-[11px] font-medium mb-3">Regalos y colecciones</p>
                <h2 class="text-3xl sm:text-4xl text-[#2c1a0e]" style="font-family: 'DM Serif Display', serif;">
                    Armá tu madera
                </h2>
                <p class="mt-3 text-sm text-[#2c1a0e]/50 max-w-md mx-auto leading-relaxed">
                    Elegí una madera y personalizala con los condimentos que más te gusten. El precio incluye todos los frascos.
                </p>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($maderas as $madera)
                    <div class="bg-[#faf6f0] border border-[#d4b896]/30 hover:border-[#d4b896]/60 hover:shadow-lg transition-all duration-300 flex flex-col">
                        <div class="h-32 overflow-hidden bg-[#f0e9de]">
                            @if($madera->imagen)
                                <img src="{{ asset('imagenes/' . rawurlencode($madera->imagen)) }}"
                                     alt="{{ $madera->nombre }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fa-solid fa-box text-3xl text-[#8b5e3c]/20"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-4 flex flex-col gap-2 flex-1">
                            <div>
                                <h3 class="text-lg text-[#2c1a0e] leading-tight" style="font-family: 'DM Serif Display', serif;">
                                    {{ $madera->nombre }}
                                </h3>
                                <p class="text-[10px] text-[#8b5e3c]/60 mt-0.5">{{ $madera->capacidad }} frascos a elegir</p>
                            </div>
                            @if($madera->descripcion)
                                <p class="text-xs text-[#2c1a0e]/55 leading-relaxed flex-1 line-clamp-2">{{ $madera->descripcion }}</p>
                            @endif
                            <div class="flex items-center justify-end mt-auto pt-1">
                                <a href="{{ route('configurar-madera', $madera->id) }}" wire:navigate
                                   class="inline-flex items-center gap-1.5 bg-[#386641] text-white px-3 py-1.5 text-[11px] font-semibold tracking-wide rounded-lg hover:bg-[#2d5534] transition-colors duration-300">
                                    <i class="fa-solid fa-sliders text-[10px]"></i>
                                    Configurar
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- CATÁLOGO CON FILTROS --}}
    <section class="bg-[#faf6f0] py-16 px-4">
        <div class="max-w-6xl mx-auto">

            {{-- Buscador --}}
            <div class="max-w-md mx-auto mb-8">
                <div class="relative">
                    <input wire:model.live.debounce.300ms="busqueda"
                           type="text"
                           placeholder="Buscar especias, condimentos..."
                           class="w-full border border-[#d4b896]/40 bg-white rounded-full px-5 py-3 pr-12 text-sm text-[#2c1a0e] placeholder-[#8b5e3c]/40 focus:outline-none focus:border-[#386641]/50 focus:ring-2 focus:ring-[#386641]/10 transition-all duration-200">
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-[#8b5e3c]/40">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </div>
                </div>
            </div>

            {{-- Filtros por categoría --}}
            <div class="flex flex-wrap justify-center gap-2 mb-14">
                <button wire:click="$set('categoriaActiva', '0')"
                        class="border px-6 py-2 text-[12px] tracking-wider font-medium rounded-full transition-all duration-300
                               {{ $categoriaActiva === '0'
                                   ? 'bg-[#386641] text-[#faf6f0] border-[#386641]'
                                   : 'text-[#2c1a0e]/60 border-[#2c1a0e]/15 hover:border-[#386641]/40 hover:text-[#386641]' }}">
                    Todos
                </button>
                @foreach($categorias as $cat)
                    <button wire:click="$set('categoriaActiva', '{{ $cat->id }}')"
                            class="border px-6 py-2 text-[12px] tracking-wider font-medium rounded-full transition-all duration-300
                                   {{ $categoriaActiva === (string) $cat->id
                                       ? 'bg-[#386641] text-[#faf6f0] border-[#386641]'
                                       : 'text-[#2c1a0e]/60 border-[#2c1a0e]/15 hover:border-[#386641]/40 hover:text-[#386641]' }}">
                        {{ $cat->nombre }}
                    </button>
                @endforeach
            </div>

            {{-- Filtros avanzados --}}
            <div class="flex flex-wrap items-center gap-3 mb-8 justify-center">
                {{-- Solo con stock --}}
                <label class="flex items-center gap-1.5 cursor-pointer select-none">
                    <input wire:model.live="soloConStock" type="checkbox"
                           class="rounded border-[#d4b896] text-[#386641] focus:ring-[#386641] focus:ring-offset-0 transition-colors">
                    <span class="text-xs text-[#8b5e3c] font-medium">Solo con stock</span>
                </label>

                {{-- Ordenar --}}
                <select wire:model.live="ordenar"
                        class="border border-[#d4b896]/50 bg-white rounded-lg px-3 py-1.5 text-xs text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
                    <option value="nombre_asc">Nombre A–Z</option>
                    <option value="nombre_desc">Nombre Z–A</option>
                    <option value="recientes">Más recientes</option>
                </select>
            </div>

            {{-- Grid de productos --}}
            @if($productos->count() === 0)
                <div class="text-center py-20">
                    <p class="text-[#8b5e3c]/60 text-sm">No hay productos disponibles en esta categoría.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($productos as $producto)
                        <article class="flex flex-col bg-[#faf6f0] rounded-xl overflow-hidden
                                        border border-[#d4b896]/25 hover:border-[#d4b896]/50
                                        hover:shadow-lg hover:-translate-y-1 transition-all duration-400">

                            <div class="relative h-64 overflow-hidden group">
                                @if($producto->imagen)
                                    <img src="{{ asset('imagenes/' . rawurlencode($producto->imagen)) }}"
                                         alt="{{ $producto->nombre }}"
                                         class="w-full h-full object-cover object-center transition-transform duration-600 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full bg-[#f0e9de] flex items-center justify-center">
                                        <i class="fa-solid fa-leaf text-5xl text-[#386641]/30"></i>
                                    </div>
                                @endif
                                <div class="absolute top-3 left-3 flex flex-wrap gap-1">
                                    @foreach($producto->categorias as $cat)
                                        <span class="bg-[#faf6f0]/90 text-[#8b5e3c] text-[10px] tracking-[0.18em] uppercase font-medium px-2.5 py-1">
                                            {{ $cat->nombre }}
                                        </span>
                                    @endforeach
                                </div>
                                @if($producto->stock > 0 && $producto->stock <= config('tileo.stock_bajo_umbral'))
                                    <div class="absolute top-3 right-3">
                                        <span class="bg-red-600 text-[#faf6f0] text-[10px] tracking-[0.12em] uppercase font-medium px-2.5 py-1">
                                            Últimas {{ $producto->stock }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-6 flex flex-col gap-3 flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <a href="{{ route('detalle-producto', $producto->id) }}" wire:navigate class="hover:text-[#386641] transition-colors duration-200">
                                        <h2 class="text-2xl text-[#2c1a0e] leading-tight"
                                            style="font-family: 'DM Serif Display', serif;">
                                            {{ $producto->nombre }}
                                        </h2>
                                    </a>
                                    <span class="text-[11px] text-[#8b5e3c]/70 mt-2 flex-shrink-0">{{ $producto->unidad }}</span>
                                </div>

                                @if($producto->descripcion)
                                    <p class="text-sm text-[#2c1a0e]/55 leading-relaxed line-clamp-3">
                                        {{ $producto->descripcion }}
                                    </p>
                                @endif

                                <div class="mt-auto pt-2">
                                    <a href="{{ route('detalle-producto', $producto->id) }}" wire:navigate
                                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#386641] border border-[#386641]/30 px-4 py-2 rounded-lg hover:bg-[#386641] hover:text-white transition-all duration-200">
                                        Ver detalle <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>
                            </div>

                        </article>
                    @endforeach
                </div>
                <div class="mt-10 flex justify-center">
                    {{ $productos->links() }}
                </div>
            @endif

        </div>
    </section>

</div>
