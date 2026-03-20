<div>
    {{-- Encabezado --}}
    <div class="py-14 px-4 text-center" style="background-color: #f0e9de;">
        <p class="text-xs tracking-[0.3em] uppercase text-[#8b5e3c] mb-3 font-semibold">Tileo</p>
        <h1 class="text-4xl md:text-5xl text-[#2c1a0e] mb-4" style="font-family: 'DM Serif Display', serif;">
            Preguntas Frecuentes
        </h1>
        <p class="text-[#8b5e3c]/70 text-base max-w-xl mx-auto">
            Todo lo que necesitás saber sobre nuestros productos, envíos y formas de pago.
        </p>
    </div>

    {{-- Acordeones --}}
    <div class="py-14 px-4" style="background-color: #faf6f0;">
        <div class="max-w-2xl mx-auto space-y-3">

            @forelse($faqs as $i => $faq)
                <div x-data="{ abierto: false }"
                     class="bg-white rounded-2xl border border-[#d4b896]/30 overflow-hidden shadow-sm">
                    <button @click="abierto = !abierto"
                            class="w-full flex items-center justify-between px-6 py-4 text-left focus:outline-none">
                        <span class="font-semibold text-[#2c1a0e] text-sm pr-4">{{ $faq['pregunta'] }}</span>
                        <i class="fa-solid fa-plus text-[#8b5e3c] text-xs shrink-0 transition-transform duration-200"
                           :class="abierto ? 'rotate-45' : ''"></i>
                    </button>
                    <div class="grid transition-all duration-300 ease-in-out"
                         :style="abierto ? 'grid-template-rows: 1fr' : 'grid-template-rows: 0fr'">
                        <div class="overflow-hidden">
                            <div class="px-6 pb-5 pt-1">
                                <p class="text-sm leading-relaxed" style="color: rgba(44,26,14,0.6);">
                                    {{ $faq['respuesta'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-sm text-[#8b5e3c]/60 py-10">No hay preguntas frecuentes disponibles.</p>
            @endforelse

        </div>
    </div>
</div>
