<div class="mt-4 p-4 bg-[#f0e9de] border border-[#d4b896]/40 rounded-xl">
    @if($registrado)
        <p class="text-[#386641] text-sm flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i> ¡Listo! Te avisaremos cuando esté disponible.
        </p>
    @elseif($yaRegistrado)
        <p class="text-[#8b5e3c] text-sm flex items-center gap-2">
            <i class="fa-solid fa-info-circle"></i> Ya registramos tu email para este aviso.
        </p>
    @else
        <p class="text-sm font-medium text-[#2c1a0e] mb-3">
            <i class="fa-solid fa-bell text-[#8b5e3c] mr-1"></i> Avisarme cuando esté disponible
        </p>
        <form wire:submit="registrar" class="flex gap-2">
            <input wire:model="emailAviso" type="email" placeholder="Tu email"
                   class="flex-1 min-w-0 border border-[#d4b896]/50 bg-white text-[#2c1a0e] placeholder-[#8b5e3c]/40 px-3 py-2 text-sm rounded-lg focus:outline-none focus:border-[#386641] transition-colors">
            <button type="submit"
                    wire:loading.attr="disabled"
                    class="px-4 py-2 bg-[#386641] text-[#faf6f0] text-xs font-semibold rounded-lg hover:bg-[#2d5534] transition-colors flex-shrink-0">
                Avisar
            </button>
        </form>
        @error('emailAviso') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    @endif
</div>
