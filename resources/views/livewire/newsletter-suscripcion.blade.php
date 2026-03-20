<div>
    @if($suscripto)
        <p class="text-[#a7c957] text-sm flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i> ¡Gracias por suscribirte!
        </p>
    @elseif($yaExistia)
        <p class="text-[#d4b896] text-sm">Ya estás suscripto.</p>
    @else
        <form wire:submit="suscribir" class="flex gap-2">
            <input wire:model="emailSuscripcion" type="email" placeholder="Tu email"
                   class="flex-1 min-w-0 bg-white/10 border border-[#d4b896]/30 text-[#faf6f0] placeholder-[#d4b896]/50 px-3 py-2 text-sm focus:outline-none focus:border-[#a7c957] transition-colors">
            <button type="submit"
                    wire:loading.attr="disabled"
                    class="px-4 py-2 bg-[#a7c957] text-[#2c1a0e] text-xs font-semibold hover:bg-[#95b548] transition-colors flex-shrink-0">
                Suscribirme
            </button>
        </form>
        @error('emailSuscripcion') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    @endif
</div>
