<div>
    {{-- ENCABEZADO --}}
    <section class="bg-[#f0e9de] border-b border-[#d4b896]/30 py-16 px-4 text-center">
        <div class="max-w-xl mx-auto">
            <p class="text-[#8b5e3c]/70 tracking-[0.3em] uppercase text-[11px] font-medium mb-3">
                Estamos para ayudarte
            </p>
            <h1 class="text-5xl sm:text-6xl text-[#2c1a0e]"
                style="font-family: 'DM Serif Display', serif;">
                Contacto
            </h1>
            <p class="mt-4 text-sm text-[#2c1a0e]/50 leading-relaxed">
                ¿Tenés una consulta, un pedido o querés encontrarnos en una feria? Escribinos y te respondemos a la brevedad.
            </p>
        </div>
    </section>
    
    
    {{-- FORMULARIO + INFO --}}
    <section class="bg-[#faf6f0] py-20 px-4">
        <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-5 gap-12 lg:gap-16">
    
    
            {{-- FORMULARIO --}}
            <div class="lg:col-span-3">
                <h2 class="text-2xl text-[#2c1a0e] mb-8"
                    style="font-family: 'DM Serif Display', serif;">
                    Envianos un mensaje
                </h2>
    
                @if ($enviado)
                    <div class="bg-[#386641]/10 border border-[#386641]/30 text-[#386641] px-5 py-4 text-sm
                                flex items-center gap-3"
                         x-data
                         x-transition:enter="transition ease-out duration-400"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                        <span>¡Mensaje enviado! Te respondemos a la brevedad.</span>
                    </div>
                @else
                    <form wire:submit="enviar" class="flex flex-col gap-5">
    
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            {{-- Nombre --}}
                            <div class="flex flex-col gap-1.5">
                                <label for="nombre" class="text-[11px] tracking-[0.18em] uppercase text-[#2c1a0e]/60 font-medium">
                                    Nombre *
                                </label>
                                <input type="text" id="nombre" wire:model="nombre"
                                       class="bg-transparent border px-4 py-3 text-sm text-[#2c1a0e]
                                              placeholder-[#2c1a0e]/30 outline-none transition-colors duration-300
                                              {{ $errors->has('nombre') ? 'border-red-400' : 'border-[#d4b896]/50 focus:border-[#386641]' }}"
                                       placeholder="Tu nombre">
                                @error('nombre')
                                    <span class="text-[11px] text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="flex flex-col gap-1.5">
                                <label for="email" class="text-[11px] tracking-[0.18em] uppercase text-[#2c1a0e]/60 font-medium">
                                    Email *
                                </label>
                                <input type="email" id="email" wire:model="email"
                                       class="bg-transparent border px-4 py-3 text-sm text-[#2c1a0e]
                                              placeholder-[#2c1a0e]/30 outline-none transition-colors duration-300
                                              {{ $errors->has('email') ? 'border-red-400' : 'border-[#d4b896]/50 focus:border-[#386641]' }}"
                                       placeholder="tu@email.com">
                                @error('email')
                                    <span class="text-[11px] text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Teléfono --}}
                        <div class="flex flex-col gap-1.5">
                            <label for="telefono" class="text-[11px] tracking-[0.18em] uppercase text-[#2c1a0e]/60 font-medium">
                                Teléfono *
                            </label>
                            <input type="tel" id="telefono" wire:model="telefono"
                                   class="bg-transparent border px-4 py-3 text-sm text-[#2c1a0e]
                                          placeholder-[#2c1a0e]/30 outline-none transition-colors duration-300
                                          {{ $errors->has('telefono') ? 'border-red-400' : 'border-[#d4b896]/50 focus:border-[#386641]' }}"
                                   placeholder="Tu teléfono">
                            @error('telefono')
                                <span class="text-[11px] text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
    
                        {{-- Asunto --}}
                        <div class="flex flex-col gap-1.5">
                            <label for="asunto" class="text-[11px] tracking-[0.18em] uppercase text-[#2c1a0e]/60 font-medium">
                                Asunto
                            </label>
                            <select id="asunto" wire:model="asunto"
                                    class="bg-[#faf6f0] border border-[#d4b896]/50 px-4 py-3 text-sm text-[#2c1a0e]
                                           outline-none focus:border-[#386641] transition-colors duration-300 appearance-none cursor-pointer"
                                    style="background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238b5e3c' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E\"); background-repeat: no-repeat; background-position: right 16px center;">
                                <option value="">Seleccioná un motivo</option>
                                <option value="consulta">Consulta sobre un producto</option>
                                <option value="pedido">Hacer un pedido</option>
                                <option value="ferias">Próximas ferias y eventos</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
    
                        {{-- Mensaje --}}
                        <div class="flex flex-col gap-1.5">
                            <label for="mensaje" class="text-[11px] tracking-[0.18em] uppercase text-[#2c1a0e]/60 font-medium">
                                Mensaje *
                            </label>
                            <textarea id="mensaje" wire:model="mensaje" rows="5"
                                      class="bg-transparent border px-4 py-3 text-sm text-[#2c1a0e]
                                             placeholder-[#2c1a0e]/30 outline-none resize-none transition-colors duration-300
                                             {{ $errors->has('mensaje') ? 'border-red-400' : 'border-[#d4b896]/50 focus:border-[#386641]' }}"
                                      placeholder="Contanos en qué podemos ayudarte..."></textarea>
                            @error('mensaje')
                                <span class="text-[11px] text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
    
                        {{-- Submit --}}
                        <button type="submit"
                                class="self-start flex items-center gap-3 bg-[#386641] text-[#faf6f0] px-8 py-3 text-[13px] tracking-wider font-medium
                                       hover:bg-[#2d5235] transition-all duration-300 hover:-translate-y-0.5">
                            <span wire:loading.remove wire:target="enviar">Enviar mensaje</span>
                            <span wire:loading wire:target="enviar" class="flex items-center gap-2">
                                <i class="fa-solid fa-circle-notch fa-spin text-sm"></i>
                                Enviando...
                            </span>
                        </button>
    
                    </form>
                @endif
            </div>
    
    
            {{-- INFO --}}
            <div class="lg:col-span-2 flex flex-col gap-8">
    
                <h2 class="text-2xl text-[#2c1a0e]"
                    style="font-family: 'DM Serif Display', serif;">
                    Encontranos
                </h2>
    
                <div class="flex gap-4">
                    <div class="w-9 h-9 rounded-full bg-[#386641]/8 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-location-dot text-[#386641] text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[11px] tracking-[0.18em] uppercase text-[#8b5e3c]/70 font-medium mb-1">Ubicación</p>
                        <p class="text-sm text-[#2c1a0e]/70 leading-relaxed">Mercedes, Buenos Aires<br>Argentina</p>
                    </div>
                </div>
    
                <div class="flex gap-4">
                    <div class="w-9 h-9 rounded-full bg-[#386641]/8 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-store text-[#386641] text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[11px] tracking-[0.18em] uppercase text-[#8b5e3c]/70 font-medium mb-1">Ferias y eventos</p>
                        <p class="text-sm text-[#2c1a0e]/70 leading-relaxed">
                            Participamos regularmente en ferias artesanales de la región. Escribinos para saber dónde estamos próximamente.
                        </p>
                    </div>
                </div>
    
                <div class="flex gap-4">
                    <div class="w-9 h-9 rounded-full bg-[#386641]/8 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-clock text-[#386641] text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[11px] tracking-[0.18em] uppercase text-[#8b5e3c]/70 font-medium mb-1">Tiempo de respuesta</p>
                        <p class="text-sm text-[#2c1a0e]/70 leading-relaxed">
                            Respondemos en menos de 24 horas. Para urgencias escribinos directamente por WhatsApp.
                        </p>
                    </div>
                </div>
    
                <div class="h-px bg-[#d4b896]/30"></div>
    
                <div class="overflow-hidden h-44">
                    <img src="{{ asset('imagenes/' . rawurlencode('WhatsApp Image 2026-03-17 at 13.24.12.jpeg')) }}"
                         alt="Tileo en feria"
                         class="w-full h-full object-cover object-center hover:scale-105 transition-transform duration-700">
                </div>
    
            </div>
    
        </div>
    </section>
    
    
    {{-- BANNER FINAL --}}
    <section class="relative overflow-hidden h-52">
        <img src="{{ asset('imagenes/' . rawurlencode('WhatsApp Image 2026-03-17 at 13.24.12 (2).jpeg')) }}"
             alt="Tileo"
             class="w-full h-full object-cover object-top">
        <div class="absolute inset-0 bg-[#1a0f05]/65 flex items-center justify-center">
            <p class="text-center"
               style="font-family: 'DM Serif Display', serif; font-style: italic; font-size: clamp(1.25rem, 3vw, 1.75rem); color: #faf6f0; opacity: 0.9;">
                "Del campo a tu mesa, con cuidado artesanal"
            </p>
        </div>
    </section>
</div>
