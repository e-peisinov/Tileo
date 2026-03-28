<div class="min-h-screen bg-[#faf6f0]">

    {{-- Encabezado --}}
    <section class="bg-[#f0e9de] border-b border-[#d4b896]/30 py-14 px-4 text-center">
        <div class="max-w-2xl mx-auto">
            <a href="{{ route('catalogo') }}" wire:navigate
               class="inline-flex items-center gap-1.5 bg-[#386641] text-white mb-4 px-3 py-1.5 text-[11px] font-semibold tracking-wide hover:bg-[#2d5534] transition-colors duration-300">
                <i class="fa-solid fa-arrow-left text-[10px]"></i> Volver al catálogo
            </a>
            <p class="text-[#8b5e3c]/70 tracking-[0.3em] uppercase text-[11px] font-medium mb-3">Armá tu madera</p>
            <h1 class="text-4xl sm:text-5xl text-[#2c1a0e]" style="font-family: 'DM Serif Display', serif;">
                {{ $madera->nombre }}
            </h1>
            @if($madera->descripcion)
                <p class="mt-3 text-sm text-[#2c1a0e]/50 leading-relaxed max-w-md mx-auto">{{ $madera->descripcion }}</p>
            @endif
            <p class="mt-4 text-2xl font-semibold text-[#386641]">${{ number_format($madera->precio, 2, ',', '.') }}</p>
        </div>
    </section>

    {{-- Barra de progreso sticky --}}
    <div class="sticky top-0 z-30 bg-white border-b border-[#d4b896]/40 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 flex-1">
                <span class="text-xs text-[#8b5e3c] font-medium whitespace-nowrap">
                    {{ $totalSeleccionado }}/{{ $madera->capacidad }} frascos
                </span>
                <div class="flex-1 bg-[#f0e9de] rounded-full h-2 overflow-hidden">
                    <div class="h-2 rounded-full transition-all duration-300"
                         style="width: {{ $madera->capacidad > 0 ? round(($totalSeleccionado / $madera->capacidad) * 100) : 0 }}%; background-color: #386641;"></div>
                </div>
                @if($totalSeleccionado === $madera->capacidad)
                    <span class="text-xs text-[#386641] font-semibold whitespace-nowrap flex items-center gap-1">
                        <i class="fa-solid fa-check text-[10px]"></i> ¡Completo!
                    </span>
                @else
                    <span class="text-xs text-[#8b5e3c]/60 whitespace-nowrap">
                        Faltan {{ $madera->capacidad - $totalSeleccionado }}
                    </span>
                @endif
            </div>
            <button wire:click="agregarAlCarrito"
                    @disabled($totalSeleccionado !== $madera->capacidad)
                    class="flex-shrink-0 px-5 py-2 text-[12px] font-semibold tracking-wide transition-all duration-300
                           {{ $totalSeleccionado === $madera->capacidad
                               ? 'bg-[#386641] text-white hover:bg-[#2d5534]'
                               : 'bg-[#d4b896]/40 text-[#8b5e3c]/40 cursor-not-allowed' }}">
                <i class="fa-solid fa-basket-shopping text-[11px] mr-1.5"></i>
                Agregar al carrito
            </button>
        </div>
    </div>

    {{-- Mensaje de éxito --}}
    @if($agregado)
        <div class="max-w-5xl mx-auto px-4 pt-6">
            <div class="bg-[#386641]/10 border border-[#386641]/30 text-[#386641] px-4 py-3 text-sm flex items-center gap-2">
                <i class="fa-solid fa-check-circle"></i>
                <span>¡Madera agregada al carrito! Podés seguir configurando otra o <a href="{{ route('checkout') }}" wire:navigate class="underline font-medium">finalizar el pedido</a>.</span>
            </div>
        </div>
    @endif

    {{-- Filtros --}}
    <section class="max-w-5xl mx-auto px-4 pt-8 pb-4">
        <p class="text-sm text-[#2c1a0e]/60 mb-5 text-center">
            Elegí {{ $madera->capacidad }} condimentos para llenar los frascos de tu madera. Podés repetir el mismo condimento.
        </p>

        {{-- Buscador --}}
        <div class="max-w-sm mx-auto mb-5">
            <div class="relative">
                <input wire:model.live.debounce.300ms="busqueda"
                       type="text"
                       placeholder="Buscar condimento..."
                       class="w-full border border-[#d4b896]/40 bg-white rounded-full px-5 py-2.5 pr-10 text-sm text-[#2c1a0e] placeholder-[#8b5e3c]/40 focus:outline-none focus:border-[#386641]/50 transition-all duration-200">
                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-[#8b5e3c]/40">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
            </div>
        </div>

        {{-- Filtros por categoría --}}
        <div class="flex flex-wrap justify-center gap-2">
            <button wire:click="$set('categoriaActiva', 'todos')"
                    class="border px-4 py-1.5 text-[11px] tracking-wider font-medium rounded-full transition-all duration-300
                           {{ $categoriaActiva === 'todos' ? 'bg-[#386641] text-[#faf6f0] border-[#386641]' : 'text-[#2c1a0e]/60 border-[#2c1a0e]/15 hover:border-[#386641]/40 hover:text-[#386641]' }}">
                Todos
            </button>
            @foreach($categorias as $cat)
                <button wire:click="$set('categoriaActiva', '{{ $cat->nombre }}')"
                        class="border px-4 py-1.5 text-[11px] tracking-wider font-medium rounded-full transition-all duration-300
                               {{ $categoriaActiva === $cat->nombre ? 'bg-[#386641] text-[#faf6f0] border-[#386641]' : 'text-[#2c1a0e]/60 border-[#2c1a0e]/15 hover:border-[#386641]/40 hover:text-[#386641]' }}">
                    {{ $cat->nombre }}
                </button>
            @endforeach
        </div>
    </section>

    {{-- Grid de productos --}}
    <section class="max-w-5xl mx-auto px-4 pb-20">
        @if($productos->isEmpty())
            <div class="text-center py-16">
                <p class="text-[#8b5e3c]/60 text-sm">No hay condimentos disponibles con ese filtro.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($productos as $producto)
                    @php $cantidadActual = $cantidades[$producto->id] ?? 0; @endphp
                    <div class="bg-white border border-[#d4b896]/25 p-4 flex flex-col gap-3 transition-all duration-200
                                {{ $cantidadActual > 0 ? 'border-[#386641]/50 shadow-md' : 'hover:border-[#d4b896]/50 hover:shadow-sm' }}">

                        {{-- Imagen --}}
                        <div class="h-28 overflow-hidden bg-[#f0e9de] relative">
                            @if($producto->imagen)
                                <img src="{{ asset('imagenes/' . rawurlencode($producto->imagen)) }}"
                                     alt="{{ $producto->nombre }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fa-solid fa-leaf text-3xl text-[#386641]/30"></i>
                                </div>
                            @endif
                            @if($cantidadActual > 0)
                                <div class="absolute top-2 right-2 w-6 h-6 rounded-full bg-[#386641] text-white text-[11px] font-bold flex items-center justify-center">
                                    {{ $cantidadActual }}
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1">
                            <p class="text-sm font-medium text-[#2c1a0e] leading-tight">{{ $producto->nombre }}</p>
                            <p class="text-[10px] text-[#8b5e3c]/60 mt-0.5">{{ $producto->unidad }}</p>
                        </div>

                        {{-- Controles --}}
                        @if($cantidadActual === 0)
                            <button wire:click="incrementar({{ $producto->id }})"
                                    @disabled($totalSeleccionado >= $madera->capacidad)
                                    class="w-full py-2 text-[11px] font-semibold tracking-wider border transition-all duration-200
                                           {{ $totalSeleccionado >= $madera->capacidad
                                               ? 'border-[#d4b896]/30 text-[#8b5e3c]/30 cursor-not-allowed'
                                               : 'border-[#386641]/40 text-[#386641] hover:bg-[#386641] hover:text-white' }}">
                                + Agregar
                            </button>
                        @else
                            <div class="flex items-center justify-between gap-2">
                                <button wire:click="decrementar({{ $producto->id }})"
                                        class="w-8 h-8 flex items-center justify-center border border-[#d4b896] text-[#8b5e3c] hover:border-[#c0392b] hover:text-[#c0392b] transition-colors">
                                    <i class="fa-solid fa-minus text-[10px]"></i>
                                </button>
                                <span class="text-sm font-bold text-[#386641] min-w-[1.5rem] text-center">{{ $cantidadActual }}</span>
                                <button wire:click="incrementar({{ $producto->id }})"
                                        @disabled($totalSeleccionado >= $madera->capacidad)
                                        class="w-8 h-8 flex items-center justify-center border transition-colors
                                               {{ $totalSeleccionado >= $madera->capacidad
                                                   ? 'border-[#d4b896]/30 text-[#8b5e3c]/30 cursor-not-allowed'
                                                   : 'border-[#d4b896] text-[#8b5e3c] hover:border-[#386641] hover:text-[#386641]' }}">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </section>

</div>
