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
                <button wire:click="$set('categoriaActiva', 'todos')"
                        class="border px-6 py-2 text-[12px] tracking-wider font-medium rounded-full transition-all duration-300
                               {{ $categoriaActiva === 'todos'
                                   ? 'bg-[#386641] text-[#faf6f0] border-[#386641]'
                                   : 'text-[#2c1a0e]/60 border-[#2c1a0e]/15 hover:border-[#386641]/40 hover:text-[#386641]' }}">
                    Todos
                </button>
                @foreach($categorias as $cat)
                    <button wire:click="$set('categoriaActiva', '{{ $cat->nombre }}')"
                            class="border px-6 py-2 text-[12px] tracking-wider font-medium rounded-full transition-all duration-300
                                   {{ $categoriaActiva === $cat->nombre
                                       ? 'bg-[#386641] text-[#faf6f0] border-[#386641]'
                                       : 'text-[#2c1a0e]/60 border-[#2c1a0e]/15 hover:border-[#386641]/40 hover:text-[#386641]' }}">
                        {{ $cat->nombre }}
                    </button>
                @endforeach
            </div>

            {{-- Grid de productos --}}
            @if($productos->count() === 0)
                <div class="text-center py-20">
                    <p class="text-[#8b5e3c]/60 text-sm">No hay productos disponibles en esta categoría.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($productos as $producto)
                        <article class="flex flex-col bg-[#faf6f0]
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
                                <div class="absolute top-3 left-3">
                                    <span class="bg-[#faf6f0]/90 text-[#8b5e3c] text-[10px] tracking-[0.18em] uppercase font-medium px-2.5 py-1">
                                        {{ $producto->categoria->nombre }}
                                    </span>
                                </div>
                                @if($producto->stock > 0 && $producto->stock <= 5)
                                    <div class="absolute top-3 right-3">
                                        <span class="bg-red-600 text-[#faf6f0] text-[10px] tracking-[0.12em] uppercase font-medium px-2.5 py-1">
                                            Últimas {{ $producto->stock }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-6 flex flex-col gap-3 flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <a href="{{ route('detalle-producto', $producto->id) }}" class="hover:text-[#386641] transition-colors duration-200">
                                        <h2 class="text-2xl text-[#2c1a0e] leading-tight"
                                            style="font-family: 'DM Serif Display', serif;">
                                            {{ $producto->nombre }}
                                        </h2>
                                    </a>
                                    <span class="text-[11px] text-[#8b5e3c]/70 mt-2 flex-shrink-0">{{ $producto->unidad }}</span>
                                </div>

                                @if($producto->descripcion)
                                    <p class="text-sm text-[#2c1a0e]/55 leading-relaxed flex-1">
                                        {{ $producto->descripcion }}
                                    </p>
                                @endif

                                <div class="flex items-center justify-between mt-auto pt-3 border-t border-[#d4b896]/20">
                                    @if($producto->precio > 0)
                                        <span class="text-lg font-semibold text-[#2c1a0e]">
                                            ${{ number_format($producto->precio, 2, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-sm text-[#8b5e3c]/60 italic">Precio a confirmar</span>
                                    @endif

                                    @if($producto->stock > 0)
                                        <div class="flex items-center gap-2">
                                            <p class="text-sm text-[#2c1a0e]/55 leading-relaxed flex-1">Cantidad: </p>
                                            <input type="number"
                                                   wire:model="cantidades.{{ $producto->id }}"
                                                   min="1"
                                                   max="{{ $producto->stock }}"
                                                   value="1"
                                                   class="w-14 border border-[#d4b896]/50 bg-[#faf6f0] text-center text-sm text-[#2c1a0e] py-1.5
                                                          focus:outline-none focus:border-[#386641] transition-colors">
                                            <button wire:click="agregarAlCarrito({{ $producto->id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="agregarAlCarrito({{ $producto->id }})"
                                                    class="flex items-center gap-1.5 bg-[#386641] text-[#faf6f0] px-3 py-2 text-[12px] tracking-wider font-medium
                                                           hover:bg-[#2d5534] transition-colors duration-300 disabled:opacity-60">
                                                <span wire:loading.remove wire:target="agregarAlCarrito({{ $producto->id }})">
                                                    <i class="fa-solid fa-basket-shopping text-xs"></i>
                                                </span>
                                                <span wire:loading wire:target="agregarAlCarrito({{ $producto->id }})">...</span>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-[12px] text-[#c0392b]/80 font-medium">Sin stock</span>
                                    @endif
                                </div>
                                @if($producto->precio == 0)
                                    <p class="text-[10px] text-[#8b5e3c]/50 italic mt-1">* El precio se confirma con el pedido</p>
                                @endif
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
                <a href="{{ route('checkout') }}"
                   class="inline-block border border-[#d4b896]/50 text-[#d4b896] px-8 py-3 text-[13px] tracking-wider font-medium
                          hover:bg-[#d4b896] hover:text-[#2c1a0e] transition-all duration-300 hover:-translate-y-0.5">
                    Ver mi carrito
                </a>
            </div>
        </div>
    </section>
</div>
