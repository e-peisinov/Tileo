<div>

    {{-- ENCABEZADO / BREADCRUMB --}}
    <section class="bg-[#f0e9de] border-b border-[#d4b896]/30 py-4 px-4">
        <div class="max-w-5xl mx-auto flex items-center gap-2 text-xs text-[#8b5e3c]/60 flex-wrap">
            <a href="{{ url('/') }}" wire:navigate class="hover:text-[#386641] transition-colors">Inicio</a>
            <i class="fa-solid fa-chevron-right text-[9px] text-[#d4b896]"></i>
            <a href="{{ route('catalogo') }}" wire:navigate class="hover:text-[#386641] transition-colors">Catálogo</a>
            @if($producto->categoria)
                <i class="fa-solid fa-chevron-right text-[9px] text-[#d4b896]"></i>
                <span>{{ $producto->categoria->nombre }}</span>
            @endif
            <i class="fa-solid fa-chevron-right text-[9px] text-[#d4b896]"></i>
            <span class="text-[#2c1a0e]/70 font-medium truncate max-w-[160px] sm:max-w-none">{{ $producto->nombre }}</span>
        </div>
    </section>

    <div class="py-10 px-4" style="background-color: #faf6f0;">
        <div class="max-w-5xl mx-auto">

            {{-- Layout de 2 columnas --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">

                {{-- Imagen + Galería --}}
                <div x-data="{ imagenActual: '{{ $producto->imagen ? asset('imagenes/' . rawurlencode($producto->imagen)) : '' }}' }">
                    <div class="rounded-2xl overflow-hidden border border-[#d4b896]/40 shadow-sm bg-white aspect-square flex items-center justify-center mb-3">
                        <template x-if="imagenActual">
                            <img :src="imagenActual" alt="{{ $producto->nombre }}" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!imagenActual">
                            <div class="flex flex-col items-center justify-center gap-3 text-[#d4b896]">
                                <i class="fa-solid fa-leaf text-6xl"></i>
                                <span class="text-sm text-[#8b5e3c]/50">Sin imagen disponible</span>
                            </div>
                        </template>
                    </div>
                    @if($imagenesGaleria->count() > 0 || $producto->imagen)
                        <div class="flex gap-2 overflow-x-auto pb-1">
                            @if($producto->imagen)
                                <button @click="imagenActual = '{{ asset('imagenes/' . rawurlencode($producto->imagen)) }}'"
                                        class="flex-shrink-0 w-16 h-16 rounded-lg border-2 overflow-hidden transition-all duration-150"
                                        :class="imagenActual === '{{ asset('imagenes/' . rawurlencode($producto->imagen)) }}' ? 'border-[#386641]' : 'border-[#d4b896]/40 hover:border-[#386641]/50'">
                                    <img src="{{ asset('imagenes/' . rawurlencode($producto->imagen)) }}" class="w-full h-full object-cover">
                                </button>
                            @endif
                            @foreach($imagenesGaleria as $img)
                                <button @click="imagenActual = '{{ $img->url }}'"
                                        class="flex-shrink-0 w-16 h-16 rounded-lg border-2 overflow-hidden transition-all duration-150"
                                        :class="imagenActual === '{{ $img->url }}' ? 'border-[#386641]' : 'border-[#d4b896]/40 hover:border-[#386641]/50'">
                                    <img src="{{ $img->url }}" class="w-full h-full object-cover">
                                </button>
                            @endforeach
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

                    {{-- Badge de stock bajo --}}
                    @if($producto->stock > 0 && $producto->stock <= config('tileo.stock_bajo_umbral'))
                        <div class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg"
                             style="background-color: rgba(139,94,60,0.12); color: #8b5e3c;">
                            <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                            Últimas {{ $producto->stock }} unidades
                        </div>
                    @endif

                    {{-- Botón WhatsApp --}}
                    <div class="pt-2">
                        @if($producto->hayStock())
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', config('tileo.whatsapp', '')) }}?text={{ urlencode('Hola! Me interesa el producto: ' . $producto->nombre) }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl text-white text-sm font-semibold shadow-sm
                                      hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200"
                               style="background-color: #25D366;">
                                <i class="fa-brands fa-whatsapp text-lg"></i>
                                Consultar por WhatsApp
                            </a>
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
                            <a href="{{ route('detalle-producto', $relacionado) }}" wire:navigate
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
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
