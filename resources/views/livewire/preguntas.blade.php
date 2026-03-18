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

            {{-- Pregunta 1 --}}
            <div x-data="{ abierto: false }"
                 class="bg-white rounded-2xl border border-[#d4b896]/30 overflow-hidden shadow-sm">
                <button @click="abierto = !abierto"
                        class="w-full flex items-center justify-between px-6 py-4 text-left focus:outline-none">
                    <span class="font-semibold text-[#2c1a0e] text-sm pr-4">¿Cómo hago un pedido?</span>
                    <i class="fa-solid fa-plus text-[#8b5e3c] text-xs shrink-0 transition-transform duration-200"
                       :class="abierto ? 'rotate-45' : ''"></i>
                </button>
                <div x-show="abierto" x-collapse class="px-6 pb-5">
                    <p class="text-sm leading-relaxed" style="color: rgba(44,26,14,0.6);">
                        Los pedidos se realizan desde el catálogo online. Agregás los productos al carrito y completás el formulario de checkout con tus datos.
                    </p>
                </div>
            </div>

            {{-- Pregunta 2 --}}
            <div x-data="{ abierto: false }"
                 class="bg-white rounded-2xl border border-[#d4b896]/30 overflow-hidden shadow-sm">
                <button @click="abierto = !abierto"
                        class="w-full flex items-center justify-between px-6 py-4 text-left focus:outline-none">
                    <span class="font-semibold text-[#2c1a0e] text-sm pr-4">¿Hacen envíos fuera de Mercedes?</span>
                    <i class="fa-solid fa-plus text-[#8b5e3c] text-xs shrink-0 transition-transform duration-200"
                       :class="abierto ? 'rotate-45' : ''"></i>
                </button>
                <div x-show="abierto" x-collapse class="px-6 pb-5">
                    <p class="text-sm leading-relaxed" style="color: rgba(44,26,14,0.6);">
                        Sí, realizamos envíos a todo el país. El costo de envío se calcula y confirma por el equipo de Tileo una vez recibido el pedido.
                    </p>
                </div>
            </div>

            {{-- Pregunta 3 --}}
            <div x-data="{ abierto: false }"
                 class="bg-white rounded-2xl border border-[#d4b896]/30 overflow-hidden shadow-sm">
                <button @click="abierto = !abierto"
                        class="w-full flex items-center justify-between px-6 py-4 text-left focus:outline-none">
                    <span class="font-semibold text-[#2c1a0e] text-sm pr-4">¿Cómo son los envases?</span>
                    <i class="fa-solid fa-plus text-[#8b5e3c] text-xs shrink-0 transition-transform duration-200"
                       :class="abierto ? 'rotate-45' : ''"></i>
                </button>
                <div x-show="abierto" x-collapse class="px-6 pb-5">
                    <p class="text-sm leading-relaxed" style="color: rgba(44,26,14,0.6);">
                        Todos nuestros productos vienen en tubos de vidrio transparente con tapa de corcho natural. Podés ver el producto directamente, y el corcho lo sella de forma natural y segura.
                    </p>
                </div>
            </div>

            {{-- Pregunta 4 --}}
            <div x-data="{ abierto: false }"
                 class="bg-white rounded-2xl border border-[#d4b896]/30 overflow-hidden shadow-sm">
                <button @click="abierto = !abierto"
                        class="w-full flex items-center justify-between px-6 py-4 text-left focus:outline-none">
                    <span class="font-semibold text-[#2c1a0e] text-sm pr-4">¿Los productos tienen conservantes?</span>
                    <i class="fa-solid fa-plus text-[#8b5e3c] text-xs shrink-0 transition-transform duration-200"
                       :class="abierto ? 'rotate-45' : ''"></i>
                </button>
                <div x-show="abierto" x-collapse class="px-6 pb-5">
                    <p class="text-sm leading-relaxed" style="color: rgba(44,26,14,0.6);">
                        No. Todos nuestros productos son 100% naturales, sin aditivos ni conservantes. Son especias puras, molidas y preparadas de forma artesanal.
                    </p>
                </div>
            </div>

            {{-- Pregunta 5 --}}
            <div x-data="{ abierto: false }"
                 class="bg-white rounded-2xl border border-[#d4b896]/30 overflow-hidden shadow-sm">
                <button @click="abierto = !abierto"
                        class="w-full flex items-center justify-between px-6 py-4 text-left focus:outline-none">
                    <span class="font-semibold text-[#2c1a0e] text-sm pr-4">¿Cuánto tarda la entrega?</span>
                    <i class="fa-solid fa-plus text-[#8b5e3c] text-xs shrink-0 transition-transform duration-200"
                       :class="abierto ? 'rotate-45' : ''"></i>
                </button>
                <div x-show="abierto" x-collapse class="px-6 pb-5">
                    <p class="text-sm leading-relaxed" style="color: rgba(44,26,14,0.6);">
                        Los pedidos con retiro en local están listos en 24-48hs. Los envíos a domicilio dependen del destino, pero generalmente demoran entre 3 y 7 días hábiles.
                    </p>
                </div>
            </div>

            {{-- Pregunta 6 --}}
            <div x-data="{ abierto: false }"
                 class="bg-white rounded-2xl border border-[#d4b896]/30 overflow-hidden shadow-sm">
                <button @click="abierto = !abierto"
                        class="w-full flex items-center justify-between px-6 py-4 text-left focus:outline-none">
                    <span class="font-semibold text-[#2c1a0e] text-sm pr-4">¿Puedo pagar con tarjeta?</span>
                    <i class="fa-solid fa-plus text-[#8b5e3c] text-xs shrink-0 transition-transform duration-200"
                       :class="abierto ? 'rotate-45' : ''"></i>
                </button>
                <div x-show="abierto" x-collapse class="px-6 pb-5">
                    <p class="text-sm leading-relaxed" style="color: rgba(44,26,14,0.6);">
                        Por el momento aceptamos efectivo o transferencia bancaria. Al confirmar tu pedido, te enviamos los datos de transferencia por email.
                    </p>
                </div>
            </div>

            {{-- Pregunta 7 --}}
            <div x-data="{ abierto: false }"
                 class="bg-white rounded-2xl border border-[#d4b896]/30 overflow-hidden shadow-sm">
                <button @click="abierto = !abierto"
                        class="w-full flex items-center justify-between px-6 py-4 text-left focus:outline-none">
                    <span class="font-semibold text-[#2c1a0e] text-sm pr-4">¿Dónde los encuentro en ferias?</span>
                    <i class="fa-solid fa-plus text-[#8b5e3c] text-xs shrink-0 transition-transform duration-200"
                       :class="abierto ? 'rotate-45' : ''"></i>
                </button>
                <div x-show="abierto" x-collapse class="px-6 pb-5">
                    <p class="text-sm leading-relaxed" style="color: rgba(44,26,14,0.6);">
                        Estamos presentes en ferias y eventos de Mercedes, Buenos Aires. Seguinos en redes o escribinos por WhatsApp para saber cuándo y dónde estaremos.
                    </p>
                </div>
            </div>

            {{-- Pregunta 8 --}}
            <div x-data="{ abierto: false }"
                 class="bg-white rounded-2xl border border-[#d4b896]/30 overflow-hidden shadow-sm">
                <button @click="abierto = !abierto"
                        class="w-full flex items-center justify-between px-6 py-4 text-left focus:outline-none">
                    <span class="font-semibold text-[#2c1a0e] text-sm pr-4">¿Los tubos se pueden regalar?</span>
                    <i class="fa-solid fa-plus text-[#8b5e3c] text-xs shrink-0 transition-transform duration-200"
                       :class="abierto ? 'rotate-45' : ''"></i>
                </button>
                <div x-show="abierto" x-collapse class="px-6 pb-5">
                    <p class="text-sm leading-relaxed" style="color: rgba(44,26,14,0.6);">
                        Absolutamente. Los tubos de vidrio con corcho son una presentación ideal para regalo. También ofrecemos combinaciones de productos y soportes de madera artesanales.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
