<div class="min-h-screen bg-[#faf6f0] py-10 px-4">
    <div class="max-w-6xl mx-auto">

        {{-- Encabezado --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <p class="text-[#8b5e3c]/70 tracking-[0.25em] uppercase text-[10px] font-medium mb-1">Panel de administración</p>
                <h1 class="text-3xl text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">Productos</h1>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <button wire:click="abrirCrear"
                        class="inline-flex items-center gap-2 bg-[#386641] text-[#faf6f0] px-5 py-2.5 text-[13px] tracking-wider font-medium hover:bg-[#2d5534] transition-colors">
                    <i class="fa-solid fa-plus text-xs"></i> Nuevo producto
                </button>
            </div>
        </div>

        {{-- Banner de éxito --}}
        @if($guardado)
            <div class="flex items-center gap-2 text-[#386641] text-sm bg-[#386641]/8 border border-[#386641]/20 px-4 py-3 mb-5">
                <i class="fa-solid fa-check"></i> Producto guardado correctamente.
            </div>
        @endif

        {{-- Búsqueda --}}
        <div class="mb-5">
            <input wire:model.live.debounce.300ms="busqueda" type="text"
                   placeholder="Buscar producto..."
                   class="w-full sm:w-72 border border-[#d4b896]/50 bg-white px-4 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
        </div>

        {{-- Tabla --}}
        <div class="bg-white border border-[#d4b896]/30 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-[#f0e9de] border-b border-[#d4b896]/40">
                    <tr>
                        <th class="text-left px-4 py-3 text-[11px] tracking-wider text-[#8b5e3c] uppercase">Nombre</th>
                        <th class="text-left px-4 py-3 text-[11px] tracking-wider text-[#8b5e3c] uppercase hidden md:table-cell">Categoría</th>
                        <th class="text-right px-4 py-3 text-[11px] tracking-wider text-[#8b5e3c] uppercase">Precio</th>
                        <th class="text-right px-4 py-3 text-[11px] tracking-wider text-[#8b5e3c] uppercase">Stock</th>
                        <th class="text-center px-4 py-3 text-[11px] tracking-wider text-[#8b5e3c] uppercase">Estado</th>
                        <th class="text-center px-4 py-3 text-[11px] tracking-wider text-[#8b5e3c] uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                        <tr class="border-b border-[#d4b896]/20 hover:bg-[#faf6f0] transition-colors">
                            <td class="px-4 py-3">
                                <p class="font-medium text-[#2c1a0e]">{{ $producto->nombre }}</p>
                                <p class="text-[11px] text-[#8b5e3c]/60">{{ $producto->unidad }}</p>
                            </td>
                            <td class="px-4 py-3 hidden md:table-cell text-[#2c1a0e]/70">{{ $producto->categoria->nombre }}</td>
                            <td class="px-4 py-3 text-right font-medium text-[#2c1a0e]">
                                @if($producto->precio > 0)
                                    ${{ number_format($producto->precio, 2, ',', '.') }}
                                @else
                                    <span class="text-[#8b5e3c]/50 text-xs">Sin precio</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="{{ $producto->stock <= 3 ? 'text-red-600 font-semibold' : 'text-[#2c1a0e]' }}">
                                    {{ $producto->stock }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button wire:click="toggleActivo({{ $producto->id }})"
                                        class="text-[11px] px-2.5 py-1 rounded-full font-medium transition-colors
                                               {{ $producto->activo ? 'bg-[#386641]/15 text-[#386641] hover:bg-[#386641]/25' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                    {{ $producto->activo ? 'Activo' : 'Inactivo' }}
                                </button>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button wire:click="abrirEditar({{ $producto->id }})"
                                        class="text-[#386641] text-[12px] hover:underline mr-3">Editar</button>
                                <button
                                    x-data
                                    x-on:click="confirm('¿Eliminar {{ addslashes($producto->nombre) }}? Esta acción no se puede deshacer.') && $wire.eliminar({{ $producto->id }})"
                                    class="text-red-500 text-[12px] hover:underline">Eliminar</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-12 text-center text-[#8b5e3c]/60">No hay productos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $productos->links() }}</div>
    </div>

    {{-- MODAL --}}
    @if($mostrarModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
             x-data x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-white w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 @click.stop>

                <div class="flex items-center justify-between px-6 py-4 border-b border-[#d4b896]/40 bg-[#f0e9de]">
                    <h2 class="text-lg text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">
                        {{ $editandoId ? 'Editar producto' : 'Nuevo producto' }}
                    </h2>
                    <button wire:click="$set('mostrarModal', false)" class="text-[#8b5e3c] hover:text-[#2c1a0e] transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5">Nombre *</label>
                        <input wire:model="nombre" type="text" class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-3 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
                        @error('nombre') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5">Categoría *</label>
                        <select wire:model="categoria_id" class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-3 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
                            <option value="0">Seleccioná una categoría</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                        @error('categoria_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5">Precio ($) *</label>
                            <input wire:model="precio" type="number" min="0" step="0.01"
                                   class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-3 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
                            @error('precio') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5">Stock *</label>
                            <input wire:model="stock" type="number" min="0"
                                   class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-3 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
                            @error('stock') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5">Unidad</label>
                        <input wire:model="unidad" type="text" placeholder="frasco, 50g, 100ml..."
                               class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-3 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5">Imagen</label>
                        <input wire:model="imagenArchivo" type="file" accept="image/*"
                               class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-3 py-2 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors file:mr-3 file:py-1 file:px-3 file:border-0 file:text-xs file:bg-[#386641]/10 file:text-[#386641] file:cursor-pointer">
                        @error('imagenArchivo') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        @if($imagen && !$imagenArchivo)
                            <p class="text-[11px] text-[#8b5e3c]/60 mt-1">Actual: {{ $imagen }}</p>
                        @endif
                        <div x-data class="mt-2">
                            <p class="text-[10px] text-[#8b5e3c]/50 mb-1">O ingresá el nombre del archivo manualmente:</p>
                            <input wire:model="imagen" type="text" placeholder="nombre-archivo.jpg"
                                   class="w-full border border-[#d4b896]/30 bg-[#faf6f0] px-3 py-2 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5">Descripción</label>
                        <textarea wire:model="descripcion" rows="3"
                                  class="w-full border border-[#d4b896]/50 bg-[#faf6f0] px-3 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors resize-none"></textarea>
                    </div>
                    <div class="flex items-center gap-2">
                        <input wire:model="activo" type="checkbox" id="activo" class="accent-[#386641]">
                        <label for="activo" class="text-sm text-[#2c1a0e]">Producto activo (visible en catálogo)</label>
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
