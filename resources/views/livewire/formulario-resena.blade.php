<div class="mt-8 bg-[#f0e9de] border border-[#d4b896]/40 rounded-2xl p-6">
    <h3 class="text-xl text-[#2c1a0e] mb-5" style="font-family: 'DM Serif Display', serif;">
        Dejá tu reseña
    </h3>

    @if($enviado)
        <div class="flex items-start gap-3 text-[#386641]">
            <i class="fa-solid fa-check-circle text-xl mt-0.5"></i>
            <div>
                <p class="font-semibold text-sm">¡Gracias por tu reseña!</p>
                <p class="text-sm text-[#2c1a0e]/60 mt-1">Será revisada antes de publicarse.</p>
            </div>
        </div>
    @elseif(!$pedidoVerificado)
        {{-- Paso 1: verificar número de pedido --}}
        <p class="text-sm text-[#2c1a0e]/60 mb-4">
            Para dejar una reseña necesitamos verificar que compraste este producto. Ingresá tu número de pedido (ej. TIL-0001).
        </p>
        <div class="flex gap-2">
            <input wire:model="numeroPedido"
                   type="text"
                   placeholder="Número de pedido (ej. TIL-0001)"
                   class="flex-1 border border-[#d4b896]/50 bg-white text-[#2c1a0e] placeholder-[#8b5e3c]/40 px-3 py-2 text-sm rounded-lg focus:outline-none focus:border-[#386641] transition-colors">
            <button wire:click="verificarPedido"
                    wire:loading.attr="disabled"
                    class="px-4 py-2 bg-[#386641] text-[#faf6f0] text-xs font-semibold rounded-lg hover:bg-[#2d5534] transition-colors flex-shrink-0">
                Verificar
            </button>
        </div>
        @if($errorPedido)
            <p class="text-red-500 text-xs mt-2">{{ $errorPedido }}</p>
        @endif
    @else
        {{-- Paso 2: formulario de reseña --}}
        <p class="text-sm text-[#386641] mb-5 flex items-center gap-1.5">
            <i class="fa-solid fa-check-circle"></i> Pedido verificado. Completá tu reseña.
        </p>

        <div class="space-y-4">
            {{-- Estrellas --}}
            <div>
                <label class="block text-xs font-semibold text-[#2c1a0e]/70 uppercase tracking-wider mb-2">Calificación</label>
                <div x-data="{ hover: 0 }" class="flex gap-1">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button"
                                @mouseenter="hover = {{ $i }}"
                                @mouseleave="hover = 0"
                                wire:click="$set('calificacion', {{ $i }})"
                                class="text-2xl transition-colors"
                                :class="(hover >= {{ $i }} || $wire.calificacion >= {{ $i }}) ? 'text-amber-400' : 'text-[#d4b896]'">
                            <i class="fa-solid fa-star"></i>
                        </button>
                    @endfor
                </div>
                @error('calificacion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Nombre --}}
            <div>
                <label class="block text-xs font-semibold text-[#2c1a0e]/70 uppercase tracking-wider mb-2">Tu nombre</label>
                <input wire:model="nombreCliente"
                       type="text"
                       class="w-full border border-[#d4b896]/50 bg-white text-[#2c1a0e] px-3 py-2 text-sm rounded-lg focus:outline-none focus:border-[#386641] transition-colors">
                @error('nombreCliente') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Comentario --}}
            <div>
                <label class="block text-xs font-semibold text-[#2c1a0e]/70 uppercase tracking-wider mb-2">Comentario <span class="font-normal normal-case">(opcional)</span></label>
                <textarea wire:model="comentario"
                          rows="3"
                          class="w-full border border-[#d4b896]/50 bg-white text-[#2c1a0e] px-3 py-2 text-sm rounded-lg focus:outline-none focus:border-[#386641] transition-colors resize-none"></textarea>
                @error('comentario') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button wire:click="enviar"
                    wire:loading.attr="disabled"
                    class="bg-[#386641] text-[#faf6f0] px-6 py-2.5 text-sm font-semibold rounded-xl hover:bg-[#2d5534] transition-colors disabled:opacity-60">
                <span wire:loading.remove wire:target="enviar">Enviar reseña</span>
                <span wire:loading wire:target="enviar">Enviando...</span>
            </button>
        </div>
    @endif
</div>
