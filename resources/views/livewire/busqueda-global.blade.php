<div class="relative" x-data @keydown.escape.window="$wire.cerrar()">
    <div class="relative">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[#8b5e3c]/50 text-xs"></i>
        <input wire:model.live.debounce.300ms="termino"
               type="text"
               placeholder="Buscar productos..."
               class="w-full pl-8 pr-4 py-2 text-sm border border-[#d4b896]/50 bg-[#faf6f0] text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors rounded-lg"
               @focus="$wire.$set('abierta', $wire.termino.length >= 2)">
    </div>
    @if($abierta && !empty($resultados))
        <div class="absolute top-full left-0 right-0 mt-1 bg-white border border-[#d4b896]/40 shadow-lg rounded-xl z-50 overflow-hidden">
            @foreach($resultados as $resultado)
                <a href="{{ route('detalle-producto', $resultado) }}"
                   wire:navigate wire:click="cerrar"
                   class="flex items-center gap-3 px-4 py-3 hover:bg-[#faf6f0] transition-colors border-b border-[#d4b896]/10 last:border-0">
                    <div class="w-10 h-10 bg-[#f0e9de] flex-shrink-0 overflow-hidden rounded">
                        @if($resultado->imagen)
                            <img src="{{ asset('imagenes/' . rawurlencode($resultado->imagen)) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fa-solid fa-leaf text-[#386641]/40 text-sm"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-[#2c1a0e] truncate">{{ $resultado->nombre }}</p>
                        <p class="text-xs text-[#8b5e3c]">${{ number_format($resultado->precio, 2, ',', '.') }}</p>
                    </div>
                    @if($resultado->stock <= 0)
                        <span class="text-[10px] text-red-500 font-medium flex-shrink-0">Sin stock</span>
                    @endif
                </a>
            @endforeach
        </div>
    @elseif($abierta && strlen($termino) >= 2)
        <div class="absolute top-full left-0 right-0 mt-1 bg-white border border-[#d4b896]/40 shadow-lg rounded-xl z-50 p-4 text-center">
            <p class="text-sm text-[#8b5e3c]/70">No encontramos productos para "{{ $termino }}"</p>
        </div>
    @endif
</div>
