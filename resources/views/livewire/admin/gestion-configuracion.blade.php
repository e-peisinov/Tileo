<div class="min-h-screen py-10 px-4" style="background: linear-gradient(150deg, #faf6f0 0%, #f0e9de 100%);">
    <div class="max-w-3xl mx-auto">

        {{-- Nav admin --}}
        <nav class="flex flex-wrap gap-1 mb-8 bg-white/70 backdrop-blur-sm rounded-2xl p-1.5 border border-[#d4b896]/25 shadow-sm w-fit">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200
                      {{ request()->routeIs('admin.dashboard') ? 'bg-[#386641] text-white shadow-sm' : 'text-[#8b5e3c] hover:bg-[#f0e9de] hover:text-[#2c1a0e]' }}">
                <i class="fa-solid fa-gauge-high text-[10px]"></i> Dashboard
            </a>
            <a href="{{ route('admin.pedidos') }}"
               class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200
                      {{ request()->routeIs('admin.pedidos', 'admin.detalle-pedido') ? 'bg-[#386641] text-white shadow-sm' : 'text-[#8b5e3c] hover:bg-[#f0e9de] hover:text-[#2c1a0e]' }}">
                <i class="fa-solid fa-bag-shopping text-[10px]"></i> Pedidos
            </a>
            <a href="{{ route('admin.productos') }}"
               class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200
                      {{ request()->routeIs('admin.productos') ? 'bg-[#386641] text-white shadow-sm' : 'text-[#8b5e3c] hover:bg-[#f0e9de] hover:text-[#2c1a0e]' }}">
                <i class="fa-solid fa-seedling text-[10px]"></i> Productos
            </a>
            <a href="{{ route('admin.categorias') }}"
               class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200
                      {{ request()->routeIs('admin.categorias') ? 'bg-[#386641] text-white shadow-sm' : 'text-[#8b5e3c] hover:bg-[#f0e9de] hover:text-[#2c1a0e]' }}">
                <i class="fa-solid fa-tags text-[10px]"></i> Categorías
            </a>
            <a href="{{ route('admin.usuarios') }}"
               class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200
                      {{ request()->routeIs('admin.usuarios') ? 'bg-[#386641] text-white shadow-sm' : 'text-[#8b5e3c] hover:bg-[#f0e9de] hover:text-[#2c1a0e]' }}">
                <i class="fa-solid fa-users text-[10px]"></i> Usuarios
            </a>
            <a href="{{ route('admin.configuracion') }}"
               class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200
                      {{ request()->routeIs('admin.configuracion') ? 'bg-[#386641] text-white shadow-sm' : 'text-[#8b5e3c] hover:bg-[#f0e9de] hover:text-[#2c1a0e]' }}">
                <i class="fa-solid fa-gear text-[10px]"></i> Config
            </a>
        </nav>

        {{-- Encabezado --}}
        <div class="mb-8">
            <p class="text-[#8b5e3c]/60 tracking-[0.25em] uppercase text-[10px] font-semibold mb-1">Panel de administración</p>
            <h1 class="text-4xl text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">Configuración</h1>
        </div>

        {{-- Banner de éxito --}}
        @if($guardado)
            <div class="flex items-center gap-2.5 text-[#386641] text-sm rounded-xl border border-[#386641]/20 px-4 py-3 mb-5 shadow-sm"
                 style="background-color: rgba(56,102,65,0.06);">
                <div class="w-5 h-5 rounded-full flex items-center justify-center" style="background-color: rgba(56,102,65,0.15);">
                    <i class="fa-solid fa-check text-[10px]"></i>
                </div>
                Configuración guardada correctamente.
            </div>
        @endif

        <form wire:submit="guardar" class="space-y-5">

            {{-- Datos bancarios --}}
            <div class="bg-white rounded-2xl shadow-sm border border-[#d4b896]/20 overflow-hidden">
                <div class="px-6 py-4 border-b border-[#d4b896]/20" style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                    <h2 class="text-base text-[#2c1a0e] flex items-center gap-2" style="font-family:'DM Serif Display',serif;">
                        <i class="fa-solid fa-building-columns text-sm text-[#8b5e3c]/50"></i>
                        Datos bancarios para transferencias
                    </h2>
                    <p class="text-[11px] text-[#8b5e3c]/60 mt-0.5">Se muestran al cliente cuando elige pagar por transferencia.</p>
                </div>
                <div class="p-6 space-y-4">
                    @foreach(['titular_cuenta', 'cbu', 'alias_cbu'] as $clave)
                        @php $config = $configuraciones->firstWhere('clave', $clave); @endphp
                        @if($config)
                            <div>
                                <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5 font-semibold">
                                    {{ $config->etiqueta }}
                                </label>
                                <input wire:model="valores.{{ $clave }}" type="text"
                                       class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-lg px-3 py-2.5 text-sm text-[#2c1a0e]
                                              focus:outline-none focus:ring-2 focus:ring-[#386641]/20 focus:border-[#386641] transition-all duration-200"
                                       placeholder="{{ $config->descripcion }}">
                                @error("valores.{$clave}") <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Entrega --}}
            <div class="bg-white rounded-2xl shadow-sm border border-[#d4b896]/20 overflow-hidden">
                <div class="px-6 py-4 border-b border-[#d4b896]/20" style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                    <h2 class="text-base text-[#2c1a0e] flex items-center gap-2" style="font-family:'DM Serif Display',serif;">
                        <i class="fa-solid fa-truck text-sm text-[#8b5e3c]/50"></i>
                        Entrega
                    </h2>
                </div>
                <div class="p-6">
                    @php $config = $configuraciones->firstWhere('clave', 'tiempo_entrega'); @endphp
                    @if($config)
                        <div>
                            <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5 font-semibold">
                                {{ $config->etiqueta }}
                            </label>
                            <input wire:model="valores.tiempo_entrega" type="text"
                                   class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-lg px-3 py-2.5 text-sm text-[#2c1a0e]
                                          focus:outline-none focus:ring-2 focus:ring-[#386641]/20 focus:border-[#386641] transition-all duration-200"
                                   placeholder="Ejemplo: 2 a 5 días hábiles">
                            @error('valores.tiempo_entrega') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif
                </div>
            </div>

            {{-- Modo vacaciones --}}
            <div class="bg-white rounded-2xl shadow-sm border border-[#d4b896]/20 overflow-hidden">
                <div class="px-6 py-4 border-b border-[#d4b896]/20" style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                    <h2 class="text-base text-[#2c1a0e] flex items-center gap-2" style="font-family:'DM Serif Display',serif;">
                        <i class="fa-solid fa-umbrella-beach text-sm text-[#8b5e3c]/50"></i>
                        Modo vacaciones
                    </h2>
                    <p class="text-[11px] text-[#8b5e3c]/60 mt-0.5">Cuando está activo, el checkout queda deshabilitado y se muestra el mensaje configurado.</p>
                </div>
                <div class="p-6 space-y-4">
                    {{-- Toggle modo vacaciones --}}
                    <div class="flex items-center justify-between p-4 rounded-xl border border-[#d4b896]/30"
                         style="background-color: rgba(250,246,240,0.6);">
                        <div>
                            <p class="text-sm font-semibold text-[#2c1a0e]">Activar modo vacaciones</p>
                            <p class="text-[11px] text-[#8b5e3c]/60 mt-0.5">Los clientes no podrán confirmar pedidos mientras esté activo.</p>
                        </div>
                        <label class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input type="checkbox"
                                       wire:model="valores.modo_vacaciones"
                                       class="sr-only peer"
                                       value="true"
                                       @checked(filter_var($valores['modo_vacaciones'] ?? false, FILTER_VALIDATE_BOOLEAN))>
                                <div class="w-10 h-5 bg-[#d4b896]/40 rounded-full peer peer-checked:bg-red-400 transition-colors duration-200"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-5"></div>
                            </div>
                        </label>
                    </div>

                    {{-- Mensaje vacaciones --}}
                    @php $config = $configuraciones->firstWhere('clave', 'mensaje_vacaciones'); @endphp
                    @if($config)
                        <div>
                            <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5 font-semibold">
                                {{ $config->etiqueta }}
                            </label>
                            <textarea wire:model="valores.mensaje_vacaciones" rows="2"
                                      class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-lg px-3 py-2.5 text-sm text-[#2c1a0e]
                                             focus:outline-none focus:ring-2 focus:ring-[#386641]/20 focus:border-[#386641] transition-all duration-200 resize-none"></textarea>
                            @error('valores.mensaje_vacaciones') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 rounded-xl px-6 py-2.5 text-[13px] font-semibold text-white shadow-sm
                               hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 disabled:opacity-60"
                        style="background: linear-gradient(135deg, #386641 0%, #2d5534 100%);">
                    <span wire:loading.remove wire:target="guardar">
                        <i class="fa-solid fa-floppy-disk text-xs"></i> Guardar cambios
                    </span>
                    <span wire:loading wire:target="guardar" class="flex items-center gap-2">
                        <i class="fa-solid fa-spinner fa-spin text-xs"></i> Guardando...
                    </span>
                </button>
            </div>

        </form>
    </div>
</div>
