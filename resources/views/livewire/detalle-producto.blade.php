<div>
    <div class="min-h-screen py-10 px-4" style="background-color: #faf6f0;">
        <div class="max-w-5xl mx-auto">

            {{-- Volver al catálogo --}}
            <div class="mb-7">
                <a href="{{ route('catalogo') }}"
                   class="inline-flex items-center gap-1.5 text-sm font-medium transition-colors"
                   style="color: #386641;">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Volver al catálogo
                </a>
            </div>

            {{-- Layout de 2 columnas --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">

                {{-- Imagen --}}
                <div class="rounded-2xl overflow-hidden border border-[#d4b896]/40 shadow-sm bg-white aspect-square flex items-center justify-center">
                    @if($producto->imagen)
                        <img src="{{ asset('imagenes/' . $producto->imagen) }}"
                             alt="{{ $producto->nombre }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="flex flex-col items-center justify-center gap-3 text-[#d4b896]">
                            <i class="fa-solid fa-leaf text-6xl"></i>
                            <span class="text-sm text-[#8b5e3c]/50">Sin imagen disponible</span>
                        </div>
                    @endif
                </div>

                {{-- Información del producto --}}
                <div class="space-y-5">

                    {{-- Badge de categoría --}}
                    @if($producto->categoria)
                        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full text-white"
                              style="background-color: #a7c957; color: #2c1a0e;">
                            {{ $producto->categoria->nombre }}
                        </span>
                    @endif

                    {{-- Nombre --}}
                    <h1 class="text-4xl text-[#2c1a0e] leading-tight" style="font-family: 'DM Serif Display', serif;">
                        {{ $producto->nombre }}
                    </h1>

                    {{-- Unidad --}}
                    <p class="text-sm text-[#8b5e3c]/70 flex items-center gap-1.5">
                        <i class="fa-solid fa-jar text-xs"></i>
                        {{ ucfirst($producto->unidad) }}
                    </p>

                    {{-- Descripción --}}
                    @if($producto->descripcion)
                        <p class="text-[#2c1a0e]/75 text-base leading-relaxed">
                            {{ $producto->descripcion }}
                        </p>
                    @endif

                    {{-- Precio --}}
                    <div>
                        @if($producto->precio > 0)
                            <p class="text-3xl font-bold" style="color: #386641;">
                                ${{ number_format($producto->precio, 2, ',', '.') }}
                            </p>
                        @else
                            <p class="text-xl text-[#8b5e3c] italic">Precio a consultar</p>
                        @endif
                    </div>

                    {{-- Badge de stock bajo --}}
                    @if($producto->stock > 0 && $producto->stock <= 5)
                        <div class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg"
                             style="background-color: rgba(139,94,60,0.12); color: #8b5e3c;">
                            <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                            Últimas {{ $producto->stock }} unidades
                        </div>
                    @endif

                    {{-- Botón carrito --}}
                    <div class="pt-2">
                        @if($producto->hayStock())
                            <button
                                wire:click="agregarAlCarrito"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl text-white text-sm font-semibold shadow-sm
                                       hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200
                                       disabled:opacity-60 disabled:cursor-not-allowed"
                                style="background: linear-gradient(135deg, #386641 0%, #2d5534 100%);"
                            >
                                <span wire:loading.remove wire:target="agregarAlCarrito">
                                    <i class="fa-solid fa-cart-plus mr-1"></i> Agregar al carrito
                                </span>
                                <span wire:loading wire:target="agregarAlCarrito" class="flex items-center gap-2">
                                    <i class="fa-solid fa-spinner fa-spin text-xs"></i> Agregando...
                                </span>
                            </button>
                        @else
                            <span class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl text-sm font-semibold cursor-not-allowed"
                                  style="background-color: rgba(139,94,60,0.12); color: #8b5e3c;">
                                <i class="fa-solid fa-ban text-xs"></i> Sin stock
                            </span>
                        @endif
                    </div>

                </div>
            </div>

            {{-- Productos relacionados --}}
            @if($relacionados->count() > 0)
                <div class="mt-16">
                    <h2 class="text-2xl text-[#2c1a0e] mb-7" style="font-family: 'DM Serif Display', serif;">
                        También te puede interesar
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($relacionados as $relacionado)
                            <a href="{{ route('detalle-producto', $relacionado) }}"
                               class="group bg-white rounded-2xl border border-[#d4b896]/40 shadow-sm overflow-hidden
                                      hover:shadow-md hover:-translate-y-1 transition-all duration-200">
                                <div class="aspect-square overflow-hidden bg-[#f0e9de] flex items-center justify-center">
                                    @if($relacionado->imagen)
                                        <img src="{{ asset('imagenes/' . $relacionado->imagen) }}"
                                             alt="{{ $relacionado->nombre }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <i class="fa-solid fa-leaf text-4xl text-[#d4b896]"></i>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <p class="text-xs text-[#8b5e3c]/60 mb-1">{{ $relacionado->categoria->nombre ?? '' }}</p>
                                    <h3 class="text-[#2c1a0e] font-semibold text-sm group-hover:text-[#386641] transition-colors">
                                        {{ $relacionado->nombre }}
                                    </h3>
                                    <p class="text-sm font-bold mt-1" style="color: #386641;">
                                        @if($relacionado->precio > 0)
                                            ${{ number_format($relacionado->precio, 2, ',', '.') }}
                                        @else
                                            <span class="italic font-normal text-[#8b5e3c]/70">A consultar</span>
                                        @endif
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
