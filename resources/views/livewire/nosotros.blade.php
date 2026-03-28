<div>
    {{-- HERO --}}
    <section class="relative h-[70vh] overflow-hidden flex items-end">
        <img src="{{ asset('imagenes/' . rawurlencode('WhatsApp Image 2026-03-17 at 13.24.12 (4).jpeg')) }}"
             alt="Tileo artesanal"
             class="absolute inset-0 w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-gradient-to-t from-[#1a0f05]/85 via-[#1a0f05]/30 to-transparent"></div>
    
        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 w-full">
            <p class="hero-enter hero-delay-1 text-[#d4b896]/70 tracking-[0.3em] uppercase text-[11px] font-medium mb-3">
                Nuestra historia
            </p>
            <h1 class="hero-enter hero-delay-2 text-5xl sm:text-6xl text-[#faf6f0]"
                style="font-family: 'DM Serif Display', serif;">
                Nosotros
            </h1>
        </div>
    </section>
    
    
    {{-- HISTORIA --}}
    <section class="bg-[#faf6f0] py-24 px-4">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
    
            <div class="fade-desde-izq flex flex-col gap-6">
                <p class="text-[#8b5e3c]/70 tracking-[0.28em] uppercase text-[11px] font-medium">
                    Cómo empezamos
                </p>
                <h2 class="text-4xl text-[#2c1a0e] leading-tight"
                    style="font-family: 'DM Serif Display', serif;">
                    Del amor por la cocina<br>
                    <em>a un proyecto propio</em>
                </h2>
                @if($historia)
                    <div class="text-sm text-[#2c1a0e]/60 leading-relaxed space-y-4 [&_p]:leading-relaxed">
                        Somos un emprendimiento familiar de Mercedes, Buenos Aires, apasionados por las hierbas, especias y condimentos artesanales.
                    </div>
                @else
                    <p class="text-sm text-[#2c1a0e]/60 leading-relaxed">
                        Tileo nació en Mercedes, Buenos Aires, de las ganas de llevar al plato algo más que sabor:
                        la historia detrás de cada ingrediente, el cuidado en cada detalle y la calidez de lo hecho a mano.
                    </p>
                @endif
            </div>
    
            <div class="fade-desde-der h-96 overflow-hidden">
                <img src="{{ asset('imagenes/' . rawurlencode('WhatsApp Image 2026-03-17 at 13.24.12 (3).jpeg')) }}"
                     alt="Productos Tileo"
                     class="w-full h-full object-cover object-center hover:scale-105 transition-transform duration-700">
            </div>
    
        </div>
    </section>
    
    
    {{-- VALORES --}}
    <section class="bg-[#f0e9de] py-24 px-4">
        <div class="max-w-5xl mx-auto">
    
            <div class="text-center mb-16 fade-in">
                <p class="text-[#8b5e3c]/70 tracking-[0.28em] uppercase text-[11px] font-medium mb-3">
                    Lo que nos guía
                </p>
                <h2 class="text-4xl text-[#2c1a0e]"
                    style="font-family: 'DM Serif Display', serif;">
                    Nuestros valores
                </h2>
            </div>
    
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ([
                    ['icono' => 'fa-heart',     'titulo' => 'Hecho con cuidado',   'texto' => 'Cada lote es pequeño y seleccionado. No producimos en serie; elegimos cada ingrediente con atención y lo preparamos con tiempo.', 'delay' => 'stagger-1'],
                    ['icono' => 'fa-leaf',      'titulo' => 'Natural y honesto',   'texto' => 'Sin aditivos, sin conservantes, sin artificios. Lo que ves en el tubo es exactamente lo que recibís: especia pura, molida al momento.', 'delay' => 'stagger-2'],
                    ['icono' => 'fa-handshake', 'titulo' => 'Cercanos y directos', 'texto' => 'Somos un emprendimiento familiar. Cuando nos escribís, te responde la misma persona que hizo el producto. Sin intermediarios.', 'delay' => 'stagger-3'],
                ] as $valor)
                    <div class="fade-in {{ $valor['delay'] }} bg-[#faf6f0] p-8 flex flex-col gap-4
                                border border-[#d4b896]/20 hover:border-[#d4b896]/50
                                hover:shadow-md hover:-translate-y-0.5 transition-all duration-400">
                        <div class="w-11 h-11 rounded-full bg-[#386641]/8 flex items-center justify-center">
                            <i class="fa-solid {{ $valor['icono'] }} text-[#386641]"></i>
                        </div>
                        <h3 class="text-xl text-[#2c1a0e]"
                            style="font-family: 'DM Serif Display', serif;">
                            {{ $valor['titulo'] }}
                        </h3>
                        <p class="text-sm text-[#2c1a0e]/55 leading-relaxed">
                            {{ $valor['texto'] }}
                        </p>
                    </div>
                @endforeach
            </div>
    
        </div>
    </section>
    
    
    {{-- SECCIÓN PACKAGING --}}
    <section class="bg-[#faf6f0] p-6 overflow-hidden">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2">
    
            <div class="fade-desde-der bg-[#2c1a0e] flex flex-col justify-center px-10 lg:px-14 py-16 gap-6 order-2 md:order-1">
                <p class="text-[#d4b896]/60 tracking-[0.28em] uppercase text-[11px] font-medium">
                    La presentación
                </p>
                <h2 class="text-4xl text-[#faf6f0] leading-tight"
                    style="font-family: 'DM Serif Display', serif;">
                    Un tubo, una historia
                </h2>
                <p class="text-[#d4b896]/75 text-sm leading-relaxed">
                    Elegimos el tubo de vidrio porque muestra el producto sin esconderlo. Podés ver el color,
                    sentir la textura, reconocer el ingrediente antes de abrirlo. El corcho lo sella de forma
                    natural y lo convierte en algo bonito para tener en la cocina o regalar.
                </p>
                <p class="text-[#d4b896]/75 text-sm leading-relaxed">
                    Los soportes de madera que usamos en ferias son artesanales también, hechos a medida
                    para que cada tubo tenga su lugar.
                </p>
            </div>
    
            <div class="fade-desde-izq h-80 md:h-auto overflow-hidden order-1 md:order-2">
                <img src="{{ asset('imagenes/' . rawurlencode('WhatsApp Image 2026-03-17 at 13.24.12 (1).jpeg')) }}"
                     alt="Tubos Tileo"
                     class="w-full h-full object-cover object-center hover:scale-105 transition-transform duration-700">
            </div>
    
        </div>
    </section>
    
    
    {{-- USOS ESTRELLA --}}
    <section class="bg-[#faf6f0] py-24 px-4">
        <div class="max-w-5xl mx-auto">

            <div class="text-center mb-16">
                <p class="text-[#8b5e3c]/70 tracking-[0.28em] uppercase text-[11px] font-medium mb-3">
                    Inspiración para tu cocina
                </p>
                <h2 class="text-4xl text-[#2c1a0e]"
                    style="font-family: 'DM Serif Display', serif;">
                    Cómo usarlos
                </h2>
                <p class="mt-3 text-sm text-[#2c1a0e]/55 max-w-md mx-auto leading-relaxed">
                    Algunas ideas para incorporar nuestras especias en tu día a día.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ([
                    [
                        'icono'    => 'fa-bowl-rice',
                        'titulo'   => 'Risotto de hongos',
                        'especia'  => 'Nuez Moscada',
                        'texto'    => 'Una pizca de nuez moscada rallada al final del risotto transforma por completo el plato. Realza la cremosidad sin opacar el sabor de los hongos.',
                        'color'    => '#8b5e3c',
                    ],
                    [
                        'icono'    => 'fa-fire-burner',
                        'titulo'   => 'Curry casero',
                        'especia'  => 'Cúrcuma + Pimentón',
                        'texto'    => 'Combiná cúrcuma, pimentón dulce y comino para armar tu curry base. Salteá en aceite antes de agregar la cebolla para liberar todos los aromas.',
                        'color'    => '#c0a020',
                    ],
                    [
                        'icono'    => 'fa-drumstick-bite',
                        'titulo'   => 'Pollo a la paprika',
                        'especia'  => 'Paprika ahumada',
                        'texto'    => 'Mezclá paprika ahumada con aceite de oliva y ajo en polvo para marinar el pollo. Dejalo al menos 2 horas antes de cocinar para que penetre bien el sabor.',
                        'color'    => '#c0392b',
                    ],
                    [
                        'icono'    => 'fa-pepper-hot',
                        'titulo'   => 'Chimichurri intenso',
                        'especia'  => 'Ají Molido Merkén',
                        'texto'    => 'Reemplazá el ají molido clásico por merkén en tu chimichurri. Aporta un ahumado suave que eleva el clásico acompañamiento de las carnes a la parrilla.',
                        'color'    => '#a93226',
                    ],
                ] as $uso)
                    <div class="bg-[#f0e9de] p-6 flex gap-5 border border-[#d4b896]/20 hover:border-[#d4b896]/50 hover:shadow-md hover:-translate-y-0.5 transition-all duration-400">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0"
                             style="background-color: {{ $uso['color'] }}20;">
                            <i class="fa-solid {{ $uso['icono'] }} text-lg"
                               style="color: {{ $uso['color'] }};"></i>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div>
                                <span class="text-[10px] uppercase tracking-wider font-semibold"
                                      style="color: {{ $uso['color'] }};">
                                    {{ $uso['especia'] }}
                                </span>
                                <h3 class="text-lg text-[#2c1a0e]"
                                    style="font-family: 'DM Serif Display', serif;">
                                    {{ $uso['titulo'] }}
                                </h3>
                            </div>
                            <p class="text-sm text-[#2c1a0e]/60 leading-relaxed">
                                {{ $uso['texto'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>


    {{-- CTA --}}
    <section class="bg-[#386641] py-20 px-4 text-center">
        <div class="max-w-xl mx-auto fade-in flex flex-col items-center gap-6">
            <h2 class="text-4xl text-[#faf6f0]"
                style="font-family: 'DM Serif Display', serif;">
                ¿Querés saber más?
            </h2>
            <p class="text-sm text-[#faf6f0]/70 leading-relaxed">
                Escribinos, seguinos en nuestras redes o buscanos en la próxima feria de Mercedes.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('contacto') }}" wire:navigate
                   class="bg-[#faf6f0] text-[#386641] px-8 py-3 text-[13px] tracking-wider font-medium
                          hover:bg-[#f0e9de] transition-all duration-300 hover:-translate-y-0.5">
                    Contactanos
                </a>
                <a href="{{ route('catalogo') }}" wire:navigate
                   class="border border-[#faf6f0]/40 text-[#faf6f0] px-8 py-3 text-[13px] tracking-wider font-medium
                          hover:border-[#faf6f0] transition-all duration-300 hover:-translate-y-0.5">
                    Ver catálogo
                </a>
            </div>
        </div>
    </section>
</div>

