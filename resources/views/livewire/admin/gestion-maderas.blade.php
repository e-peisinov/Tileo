<div>

        {{-- Encabezado --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
            <div>
                <p class="text-[#8b5e3c]/60 tracking-[0.25em] uppercase text-[10px] font-semibold mb-1">Panel de administración</p>
                <h1 class="text-4xl text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">Maderas</h1>
            </div>
            <button wire:click="abrirCrear"
                    class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-[13px] font-semibold text-white shadow-sm
                       hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200"
                    style="background: linear-gradient(135deg, #386641 0%, #2d5534 100%);">
                <i class="fa-solid fa-plus text-xs"></i> Nueva madera
            </button>
        </div>

        {{-- Tabla --}}
        <div class="bg-white rounded-2xl shadow-sm border border-[#d4b896]/20 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-[#d4b896]/30 bg-[#f0e9de]">
                        <th class="px-5 py-3 text-left text-[10px] tracking-wider text-[#8b5e3c] uppercase font-semibold">Nombre</th>
                        <th class="px-5 py-3 text-center text-[10px] tracking-wider text-[#8b5e3c] uppercase font-semibold">Capacidad</th>
                        <th class="px-5 py-3 text-right text-[10px] tracking-wider text-[#8b5e3c] uppercase font-semibold">Precio</th>
                        <th class="px-5 py-3 text-center text-[10px] tracking-wider text-[#8b5e3c] uppercase font-semibold">Activo</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#d4b896]/20">
                    @forelse($maderas as $madera)
                        <tr class="hover:bg-[#faf6f0] transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if($madera->imagen)
                                        <img src="{{ asset('imagenes/' . rawurlencode($madera->imagen)) }}"
                                             alt="{{ $madera->nombre }}"
                                             class="w-10 h-10 object-cover flex-shrink-0">
                                    @else
                                        <div class="w-10 h-10 bg-[#f0e9de] flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-box text-[#8b5e3c]/40 text-xs"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-[#2c1a0e]">{{ $madera->nombre }}</p>
                                        @if($madera->descripcion)
                                            <p class="text-[11px] text-[#8b5e3c]/60 truncate max-w-[200px]">{{ $madera->descripcion }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-[#2c1a0e] bg-[#f0e9de] px-2.5 py-1 rounded-full">
                                    <i class="fa-solid fa-jar text-[#8b5e3c] text-[10px]"></i>
                                    {{ $madera->capacidad }} frascos
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right font-semibold text-[#2c1a0e]">
                                ${{ number_format($madera->precio, 2, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                <button wire:click="toggleActivo({{ $madera->id }})"
                                        class="inline-flex items-center gap-1.5 text-[11px] font-medium px-3 py-1 rounded-full transition-all duration-200
                                               {{ $madera->activo ? 'bg-[#386641]/10 text-[#386641] hover:bg-[#386641]/20' : 'bg-[#8b5e3c]/10 text-[#8b5e3c] hover:bg-[#8b5e3c]/20' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $madera->activo ? 'bg-[#386641]' : 'bg-[#8b5e3c]' }}"></span>
                                    {{ $madera->activo ? 'Activa' : 'Inactiva' }}
                                </button>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="abrirEditar({{ $madera->id }})"
                                            class="text-[#8b5e3c]/60 hover:text-[#386641] transition-colors px-2 py-1 text-xs">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button wire:click="confirmarEliminar({{ $madera->id }})"
                                            class="text-[#8b5e3c]/60 hover:text-red-500 transition-colors px-2 py-1 text-xs">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-[#8b5e3c]/60 text-sm">
                                No hay maderas creadas. <button wire:click="abrirCrear" class="text-[#386641] underline">Crear la primera.</button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $maderas->links() }}</div>

    {{-- Modal crear/editar --}}
    @if($mostrarModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background-color: rgba(44,26,14,0.6); backdrop-filter: blur(4px);">
            <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl"
                 x-data x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                <div class="px-6 py-4 border-b border-[#d4b896]/30 flex items-center justify-between"
                     style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                    <h2 class="text-lg text-[#2c1a0e]" style="font-family: 'DM Serif Display', serif;">
                        {{ $editandoId ? 'Editar madera' : 'Nueva madera' }}
                    </h2>
                    <button wire:click="$set('mostrarModal', false)"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-[#8b5e3c] hover:bg-[#d4b896]/30 transition-all">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5">Nombre *</label>
                        <input wire:model="nombre" type="text" placeholder="Ej: Madera de 6 frascos"
                               class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-4 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors rounded-lg">
                        @error('nombre') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5">Descripción</label>
                        <textarea wire:model="descripcion" rows="2" placeholder="Descripción breve..."
                                  class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-4 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors resize-none rounded-lg"></textarea>
                        @error('descripcion') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5">Capacidad *</label>
                            <input wire:model="capacidad" type="number" min="1" step="1" placeholder="Ej: 6"
                                   class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-4 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors rounded-lg">
                            @error('capacidad') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5">Precio *</label>
                            <input wire:model="precio" type="number" step="0.01" min="0" placeholder="0.00"
                                   class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-4 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors rounded-lg">
                            @error('precio') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5">Imagen</label>
                        <input wire:model="imagenArchivo" type="file" accept="image/*"
                               class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-4 py-2 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors rounded-lg file:mr-3 file:border-0 file:bg-[#f0e9de] file:text-[#8b5e3c] file:px-3 file:py-1 file:text-xs">
                        @if($imagen)
                            <p class="text-[11px] text-[#8b5e3c]/60 mt-1">Imagen actual: {{ $imagen }}</p>
                        @endif
                        @error('imagenArchivo') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <input wire:model="activo" type="checkbox" id="activo_madera"
                               class="rounded border-[#d4b896] text-[#386641] focus:ring-[#386641] focus:ring-offset-0">
                        <label for="activo_madera" class="text-sm text-[#2c1a0e] cursor-pointer">Activa (visible para clientes)</label>
                    </div>
                </div>

                <div class="px-6 pb-6 flex gap-3">
                    <button wire:click="$set('mostrarModal', false)"
                            class="flex-1 border border-[#d4b896]/50 text-[#8b5e3c] rounded-xl py-2.5 text-[13px] font-medium hover:border-[#8b5e3c] hover:bg-[#f0e9de] transition-all duration-200">
                        Cancelar
                    </button>
                    <button wire:click="guardar"
                            wire:loading.attr="disabled"
                            class="flex-1 rounded-xl py-2.5 text-[13px] font-semibold text-white shadow-sm hover:shadow-md transition-all duration-200 disabled:opacity-60"
                            style="background: linear-gradient(135deg, #386641 0%, #2d5534 100%);">
                        <span wire:loading.remove wire:target="guardar">
                            <i class="fa-solid fa-check text-xs mr-1"></i> Guardar
                        </span>
                        <span wire:loading wire:target="guardar">Guardando...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal confirmar eliminación --}}
    @if($mostrarConfirmarEliminar)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background-color: rgba(44,26,14,0.6); backdrop-filter: blur(4px);">
            <div class="bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center"
                 x-data x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-trash text-red-500"></i>
                </div>
                <h3 class="text-lg font-semibold text-[#2c1a0e] mb-2">¿Eliminar madera?</h3>
                <p class="text-sm text-[#8b5e3c]/70 mb-6">Estás por eliminar <strong>{{ $nombreParaEliminar }}</strong>. Esta acción no se puede deshacer.</p>
                <div class="flex gap-3">
                    <button wire:click="cancelarEliminar"
                            class="flex-1 border border-[#d4b896]/50 text-[#8b5e3c] rounded-xl py-2.5 text-sm font-medium hover:bg-[#f0e9de] transition-colors">
                        Cancelar
                    </button>
                    <button wire:click="eliminar"
                            class="flex-1 bg-red-500 text-white rounded-xl py-2.5 text-sm font-semibold hover:bg-red-600 transition-colors">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
