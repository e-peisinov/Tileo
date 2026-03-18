<div class="min-h-screen bg-[#faf6f0] py-10 px-4">
    <div class="max-w-3xl mx-auto">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <p class="text-[#8b5e3c]/70 tracking-[0.25em] uppercase text-[10px] font-medium mb-1">Panel de administración</p>
                <h1 class="text-3xl text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">Categorías</h1>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <button wire:click="abrirCrear"
                        class="inline-flex items-center gap-2 bg-[#386641] text-[#faf6f0] px-5 py-2.5 text-[13px] tracking-wider font-medium hover:bg-[#2d5534] transition-colors">
                    <i class="fa-solid fa-plus text-xs"></i> Nueva categoría
                </button>
            </div>
        </div>

        {{-- Banner de éxito --}}
        @if($guardado)
            <div class="flex items-center gap-2 text-[#386641] text-sm bg-[#386641]/8 border border-[#386641]/20 px-4 py-3 mb-5">
                <i class="fa-solid fa-check"></i> Categoría guardada correctamente.
            </div>
        @endif

        {{-- Error de eliminación --}}
        @if($errorEliminar)
            <div class="flex items-center gap-2 text-red-700 text-sm bg-red-50 border border-red-200 px-4 py-3 mb-5">
                <i class="fa-solid fa-triangle-exclamation"></i> {{ $errorEliminar }}
            </div>
        @endif

        <div class="bg-white border border-[#d4b896]/30 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-[#f0e9de] border-b border-[#d4b896]/40">
                    <tr>
                        <th class="text-left px-4 py-3 text-[11px] tracking-wider text-[#8b5e3c] uppercase">Nombre</th>
                        <th class="text-left px-4 py-3 text-[11px] tracking-wider text-[#8b5e3c] uppercase hidden sm:table-cell">Descripción</th>
                        <th class="text-center px-4 py-3 text-[11px] tracking-wider text-[#8b5e3c] uppercase">Productos</th>
                        <th class="text-center px-4 py-3 text-[11px] tracking-wider text-[#8b5e3c] uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorias as $cat)
                        <tr class="border-b border-[#d4b896]/20 hover:bg-[#faf6f0] transition-colors">
                            <td class="px-4 py-3">
                                <p class="font-medium text-[#2c1a0e]">{{ $cat->nombre }}</p>
                                <span class="text-[10px] {{ $cat->activo ? 'text-[#386641]' : 'text-gray-400' }}">
                                    {{ $cat->activo ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 hidden sm:table-cell text-[#2c1a0e]/60 text-xs">{{ $cat->descripcion ?? '—' }}</td>
                            <td class="px-4 py-3 text-center text-[#2c1a0e]/70">{{ $cat->productos_count }}</td>
                            <td class="px-4 py-3 text-center">
                                <button wire:click="abrirEditar({{ $cat->id }})"
                                        class="text-[#386641] text-[12px] hover:underline mr-3">Editar</button>
                                @if($cat->productos_count === 0)
                                    <button
                                        x-data
                                        x-on:click="confirm('¿Eliminar la categoría {{ addslashes($cat->nombre) }}?') && $wire.eliminar({{ $cat->id }})"
                                        class="text-red-500 text-[12px] hover:underline">Eliminar</button>
                                @else
                                    <span class="text-[11px] text-[#8b5e3c]/40" title="Tiene productos asociados">No eliminable</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-12 text-center text-[#8b5e3c]/60">No hay categorías.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL --}}
    @if($mostrarModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
             x-data x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-white w-full max-w-md shadow-2xl p-6"
                 @click.stop>

                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-lg text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">
                        {{ $editandoId ? 'Editar categoría' : 'Nueva categoría' }}
                    </h2>
                    <button wire:click="$set('mostrarModal', false)" class="text-[#8b5e3c] hover:text-[#2c1a0e]">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5">Nombre *</label>
                        <input wire:model="nombre" type="text"
                               class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-3 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
                        @error('nombre') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5">Descripción</label>
                        <textarea wire:model="descripcion" rows="2"
                                  class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-3 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors resize-none"></textarea>
                    </div>
                    <div class="flex items-center gap-2">
                        <input wire:model="activo" type="checkbox" id="activoCat" class="accent-[#386641]">
                        <label for="activoCat" class="text-sm text-[#2c1a0e]">Categoría activa</label>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button wire:click="guardar"
                                wire:loading.attr="disabled"
                                class="flex-1 bg-[#386641] text-[#faf6f0] py-2.5 text-[13px] tracking-wider font-medium hover:bg-[#2d5534] transition-colors disabled:opacity-60">
                            <span wire:loading.remove wire:target="guardar">Guardar</span>
                            <span wire:loading wire:target="guardar">Guardando...</span>
                        </button>
                        <button wire:click="$set('mostrarModal', false)"
                                class="flex-1 border border-[#d4b896]/50 text-[#8b5e3c] py-2.5 text-[13px] hover:border-[#8b5e3c] transition-colors">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
