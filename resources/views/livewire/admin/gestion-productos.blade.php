<div class="min-h-screen py-10 px-4" style="background: linear-gradient(150deg, #faf6f0 0%, #f0e9de 100%);">
    <div class="max-w-6xl mx-auto">

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
        </nav>

        {{-- Encabezado --}}
        <div class="mb-8">
            <p class="text-[#8b5e3c]/60 tracking-[0.25em] uppercase text-[10px] font-semibold mb-1">Panel de administración</p>
            <h1 class="text-4xl text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">Productos</h1>
        </div>

        {{-- Banner de éxito --}}
        @if($guardado)
            <div class="flex items-center gap-2.5 text-[#386641] text-sm rounded-xl border border-[#386641]/20 px-4 py-3 mb-5 shadow-sm"
                 style="background-color: rgba(56,102,65,0.06);">
                <div class="w-5 h-5 rounded-full flex items-center justify-center" style="background-color: rgba(56,102,65,0.15);">
                    <i class="fa-solid fa-check text-[10px]"></i>
                </div>
                Producto guardado correctamente.
            </div>
        @endif

        {{-- Búsqueda --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-5">
            <div class="relative w-full sm:w-80">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-[#8b5e3c]/40 text-xs"></i>
                <input wire:model.live.debounce.300ms="busqueda" type="text"
                       placeholder="Buscar producto..."
                       class="w-full border border-[#d4b896]/40 bg-white rounded-xl pl-9 pr-4 py-2.5 text-sm text-[#2c1a0e] shadow-sm
                              focus:outline-none focus:ring-2 focus:ring-[#386641]/20 focus:border-[#386641] transition-all duration-200">
            </div>
            <button wire:click="abrirCrear"
                class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-[13px] font-semibold text-white shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200" style="background: linear-gradient(135deg, #386641 0%, #2d5534 100%);">
                <i class="fa-solid fa-plus text-xs"></i> Nuevo producto
            </button>
        </div>

        {{-- Tabla --}}
        <div class="bg-white rounded-2xl shadow-sm border border-[#d4b896]/20 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                        <th class="text-left px-5 py-3.5 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-semibold border-b border-[#d4b896]/30">Nombre</th>
                        <th class="text-left px-4 py-3.5 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-semibold border-b border-[#d4b896]/30 hidden md:table-cell">Categoría</th>
                        <th class="text-right px-4 py-3.5 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-semibold border-b border-[#d4b896]/30">Precio</th>
                        <th class="text-right px-4 py-3.5 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-semibold border-b border-[#d4b896]/30">Stock</th>
                        <th class="text-center px-4 py-3.5 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-semibold border-b border-[#d4b896]/30">Estado</th>
                        <th class="text-center px-5 py-3.5 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-semibold border-b border-[#d4b896]/30">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                        <tr class="border-b border-[#d4b896]/15 hover:bg-[#faf6f0]/70 transition-colors duration-150">
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-[#2c1a0e]">{{ $producto->nombre }}</p>
                                <p class="text-[11px] text-[#8b5e3c]/60">{{ $producto->unidad }}</p>
                            </td>
                            <td class="px-4 py-3.5 hidden md:table-cell">
                                <span class="inline-block text-[11px] px-2.5 py-1 rounded-lg font-medium text-[#8b5e3c]" style="background-color: rgba(139,94,60,0.08);">
                                    {{ $producto->categoria->nombre }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-right font-semibold text-[#2c1a0e]">
                                @if($producto->precio > 0)
                                    ${{ number_format($producto->precio, 2, ',', '.') }}
                                @else
                                    <span class="text-[#8b5e3c]/40 text-xs font-normal">Sin precio</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <span class="font-bold {{ $producto->stock <= 3 ? 'text-red-500' : 'text-[#2c1a0e]' }}
                                             inline-block px-2 py-0.5 rounded-lg text-sm
                                             {{ $producto->stock <= 3 ? 'bg-red-50' : '' }}">
                                    {{ $producto->stock }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button wire:click="toggleActivo({{ $producto->id }})"
                                            class="text-[11px] px-3 py-1.5 rounded-lg font-semibold transition-all duration-200 hover:shadow-sm
                                                   {{ $producto->activo
                                                      ? 'text-[#386641] hover:bg-[#386641]/20'
                                                      : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}"
                                            style="{{ $producto->activo ? 'background-color: rgba(56,102,65,0.12);' : '' }}">
                                        {{ $producto->activo ? '✓ Activo' : 'Inactivo' }}
                                    </button>
                                    @if($producto->destacado)
                                        <span title="Destacado" class="text-[#a7c957]"><i class="fa-solid fa-star text-xs"></i></span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <button wire:click="abrirEditar({{ $producto->id }})"
                                        class="inline-flex items-center gap-1 text-[#386641] text-[12px] font-medium hover:underline mr-3 transition-colors">
                                    <i class="fa-solid fa-pen text-[9px]"></i> Editar
                                </button>
                                <button
                                    x-data
                                    x-on:click="confirm('¿Eliminar {{ addslashes($producto->nombre) }}? Esta acción no se puede deshacer.') && $wire.eliminar({{ $producto->id }})"
                                    class="inline-flex items-center gap-1 text-red-400 text-[12px] font-medium hover:text-red-600 transition-colors">
                                    <i class="fa-solid fa-trash text-[9px]"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-14 text-center">
                                <i class="fa-solid fa-seedling text-4xl text-[#d4b896] mb-3 block"></i>
                                <p class="text-[#8b5e3c]/60">No hay productos.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">{{ $productos->links() }}</div>
    </div>

    {{-- MODAL --}}
    @if($mostrarModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background-color: rgba(44,26,14,0.5); backdrop-filter: blur(4px);"
             x-data x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-white w-full max-w-lg max-h-[90vh] overflow-y-auto rounded-2xl shadow-2xl"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 @click.stop>

                <div class="flex items-center justify-between px-6 py-4 border-b border-[#d4b896]/30 rounded-t-2xl"
                     style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                    <h2 class="text-lg text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">
                        {{ $editandoId ? 'Editar producto' : 'Nuevo producto' }}
                    </h2>
                    <button wire:click="$set('mostrarModal', false)"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-[#8b5e3c] hover:bg-[#d4b896]/30 hover:text-[#2c1a0e] transition-all duration-200">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5 font-semibold">Nombre *</label>
                        <input wire:model="nombre" type="text"
                               class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-lg px-3 py-2.5 text-sm text-[#2c1a0e]
                                      focus:outline-none focus:ring-2 focus:ring-[#386641]/20 focus:border-[#386641] transition-all duration-200">
                        @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5 font-semibold">Categoría *</label>
                        <select wire:model="categoria_id"
                                class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-lg px-3 py-2.5 text-sm text-[#2c1a0e]
                                       focus:outline-none focus:ring-2 focus:ring-[#386641]/20 focus:border-[#386641] transition-all duration-200">
                            <option value="0">Seleccioná una categoría</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                        @error('categoria_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5 font-semibold">Precio ($) *</label>
                            <input wire:model="precio" type="number" min="0" step="0.01"
                                   class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-lg px-3 py-2.5 text-sm text-[#2c1a0e]
                                          focus:outline-none focus:ring-2 focus:ring-[#386641]/20 focus:border-[#386641] transition-all duration-200">
                            @error('precio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5 font-semibold">Stock *</label>
                            <input wire:model="stock" type="number" min="0"
                                   class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-lg px-3 py-2.5 text-sm text-[#2c1a0e]
                                          focus:outline-none focus:ring-2 focus:ring-[#386641]/20 focus:border-[#386641] transition-all duration-200">
                            @error('stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5 font-semibold">Unidad</label>
                        <input wire:model="unidad" type="text" placeholder="frasco, 50g, 100ml..."
                               class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-lg px-3 py-2.5 text-sm text-[#2c1a0e]
                                      focus:outline-none focus:ring-2 focus:ring-[#386641]/20 focus:border-[#386641] transition-all duration-200">
                    </div>
                    <div>
                        <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5 font-semibold">Imagen</label>
                        <input wire:model="imagenArchivo" type="file" accept="image/*"
                               class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-lg px-3 py-2 text-sm text-[#2c1a0e]
                                      focus:outline-none focus:border-[#386641] transition-colors
                                      file:mr-3 file:py-1 file:px-3 file:border-0 file:rounded-lg file:text-xs file:font-medium
                                      file:text-[#386641] file:cursor-pointer"
                               style="file:background-color: rgba(56,102,65,0.1);">
                        @error('imagenArchivo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        @if($imagen && !$imagenArchivo)
                            <p class="text-[11px] text-[#8b5e3c]/60 mt-1">Actual: {{ $imagen }}</p>
                        @endif
                        <div x-data class="mt-2">
                            <p class="text-[10px] text-[#8b5e3c]/50 mb-1">O ingresá el nombre del archivo manualmente:</p>
                            <input wire:model="imagen" type="text" placeholder="nombre-archivo.jpg"
                                   class="w-full border border-[#d4b896]/30 bg-[#faf6f0] rounded-lg px-3 py-2 text-sm text-[#2c1a0e]
                                          focus:outline-none focus:ring-2 focus:ring-[#386641]/20 focus:border-[#386641] transition-all duration-200">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5 font-semibold">Descripción</label>
                        <textarea wire:model="descripcion" rows="3"
                                  class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-lg px-3 py-2.5 text-sm text-[#2c1a0e]
                                         focus:outline-none focus:ring-2 focus:ring-[#386641]/20 focus:border-[#386641] transition-all duration-200 resize-none"></textarea>
                    </div>
                    <div class="flex items-center gap-2.5 py-1">
                        <input wire:model="activo" type="checkbox" id="activo" class="accent-[#386641] w-4 h-4 rounded">
                        <label for="activo" class="text-sm text-[#2c1a0e] cursor-pointer">Producto activo (visible en catálogo)</label>
                    </div>

                    {{-- Destacado --}}
                    <div class="flex items-center gap-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <div class="relative">
                                <input type="checkbox"
                                       wire:model="destacado"
                                       id="destacado"
                                       class="sr-only peer">
                                <div class="w-9 h-5 bg-[#d4b896]/40 rounded-full peer peer-checked:bg-[#a7c957] transition-colors duration-200"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-4"></div>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-[#2c1a0e]">Destacado en inicio</span>
                                <p class="text-[10px] text-[#8b5e3c]/60">Aparece en la sección de productos del inicio</p>
                            </div>
                        </label>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button wire:click="guardar"
                                wire:loading.attr="disabled"
                                class="flex-1 rounded-xl py-2.5 text-[13px] font-semibold text-white shadow-sm
                                       hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed"
                                style="background: linear-gradient(135deg, #386641 0%, #2d5534 100%);">
                            <span wire:loading.remove wire:target="guardar">Guardar</span>
                            <span wire:loading wire:target="guardar" class="flex items-center justify-center gap-2">
                                <i class="fa-solid fa-spinner fa-spin text-xs"></i> Guardando...
                            </span>
                        </button>
                        <button wire:click="$set('mostrarModal', false)"
                                class="flex-1 border border-[#d4b896]/50 text-[#8b5e3c] rounded-xl py-2.5 text-[13px] font-medium
                                       hover:border-[#8b5e3c] hover:bg-[#f0e9de] transition-all duration-200">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
