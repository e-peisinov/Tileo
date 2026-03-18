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
        </nav>

        {{-- Encabezado --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
            <div>
                <p class="text-[#8b5e3c]/60 tracking-[0.25em] uppercase text-[10px] font-semibold mb-1">Panel de administración</p>
                <h1 class="text-4xl text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">Categorías</h1>
            </div>
            <button wire:click="abrirCrear"
                    class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-[13px] font-semibold text-white shadow-sm
                           hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200"
                    style="background: linear-gradient(135deg, #386641 0%, #2d5534 100%);">
                <i class="fa-solid fa-plus text-xs"></i> Nueva categoría
            </button>
        </div>

        {{-- Banner de éxito --}}
        @if($guardado)
            <div class="flex items-center gap-2.5 text-[#386641] text-sm rounded-xl border border-[#386641]/20 px-4 py-3 mb-5 shadow-sm"
                 style="background-color: rgba(56,102,65,0.06);">
                <div class="w-5 h-5 rounded-full flex items-center justify-center" style="background-color: rgba(56,102,65,0.15);">
                    <i class="fa-solid fa-check text-[10px]"></i>
                </div>
                Categoría guardada correctamente.
            </div>
        @endif

        {{-- Error de eliminación --}}
        @if($errorEliminar)
            <div class="flex items-center gap-2.5 text-red-700 text-sm bg-red-50 rounded-xl border border-red-200 px-4 py-3 mb-5 shadow-sm">
                <div class="w-5 h-5 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-exclamation text-[10px] text-red-600"></i>
                </div>
                {{ $errorEliminar }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-[#d4b896]/20 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                        <th class="text-left px-5 py-3.5 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-semibold border-b border-[#d4b896]/30">Nombre</th>
                        <th class="text-left px-4 py-3.5 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-semibold border-b border-[#d4b896]/30 hidden sm:table-cell">Descripción</th>
                        <th class="text-center px-4 py-3.5 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-semibold border-b border-[#d4b896]/30">Productos</th>
                        <th class="text-center px-5 py-3.5 text-[11px] tracking-wider text-[#8b5e3c] uppercase font-semibold border-b border-[#d4b896]/30">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorias as $cat)
                        <tr class="border-b border-[#d4b896]/15 hover:bg-[#faf6f0]/70 transition-colors duration-150">
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-[#2c1a0e]">{{ $cat->nombre }}</p>
                                <span class="inline-flex items-center gap-1 text-[10px] mt-0.5 {{ $cat->activo ? 'text-[#386641]' : 'text-gray-400' }}">
                                    <span class="w-1.5 h-1.5 rounded-full inline-block {{ $cat->activo ? 'bg-[#a7c957]' : 'bg-gray-300' }}"></span>
                                    {{ $cat->activo ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 hidden sm:table-cell text-[#2c1a0e]/60 text-xs">{{ $cat->descripcion ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="inline-block text-[12px] font-medium px-2.5 py-1 rounded-lg text-[#8b5e3c]" style="background-color: rgba(139,94,60,0.08);">
                                    {{ $cat->productos_count }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <button wire:click="abrirEditar({{ $cat->id }})"
                                        class="inline-flex items-center gap-1 text-[#386641] text-[12px] font-medium hover:underline mr-3 transition-colors">
                                    <i class="fa-solid fa-pen text-[9px]"></i> Editar
                                </button>
                                @if($cat->productos_count === 0)
                                    <button
                                        x-data
                                        x-on:click="confirm('¿Eliminar la categoría {{ addslashes($cat->nombre) }}?') && $wire.eliminar({{ $cat->id }})"
                                        class="inline-flex items-center gap-1 text-red-400 text-[12px] font-medium hover:text-red-600 transition-colors">
                                        <i class="fa-solid fa-trash text-[9px]"></i> Eliminar
                                    </button>
                                @else
                                    <span class="text-[11px] text-[#8b5e3c]/40" title="Tiene productos asociados">No eliminable</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-14 text-center">
                                <i class="fa-solid fa-tags text-4xl text-[#d4b896] mb-3 block"></i>
                                <p class="text-[#8b5e3c]/60">No hay categorías.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL --}}
    @if($mostrarModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background-color: rgba(44,26,14,0.5); backdrop-filter: blur(4px);"
             x-data x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 @click.stop>

                <div class="flex items-center justify-between px-6 py-4 border-b border-[#d4b896]/30"
                     style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                    <h2 class="text-lg text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">
                        {{ $editandoId ? 'Editar categoría' : 'Nueva categoría' }}
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
                        <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5 font-semibold">Descripción</label>
                        <textarea wire:model="descripcion" rows="2"
                                  class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-lg px-3 py-2.5 text-sm text-[#2c1a0e]
                                         focus:outline-none focus:ring-2 focus:ring-[#386641]/20 focus:border-[#386641] transition-all duration-200 resize-none"></textarea>
                    </div>
                    <div class="flex items-center gap-2.5 py-1">
                        <input wire:model="activo" type="checkbox" id="activoCat" class="accent-[#386641] w-4 h-4 rounded">
                        <label for="activoCat" class="text-sm text-[#2c1a0e] cursor-pointer">Categoría activa</label>
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
