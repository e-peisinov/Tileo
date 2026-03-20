<div>
    {{-- Encabezado --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
        <div>
            <p class="text-[10px] tracking-[0.2em] uppercase text-[#8b5e3c] font-semibold mb-1">Marketing</p>
            <h1 class="text-3xl text-[#2c1a0e]" style="font-family: 'DM Serif Display', serif;">Banners</h1>
        </div>
        <button
            wire:click="abrirCrear"
            class="inline-flex items-center gap-2 px-4 py-2 bg-[#386641] text-white text-sm rounded-xl hover:bg-[#2d5235] transition-colors">
            <i class="fa-solid fa-plus text-xs"></i> Nuevo banner
        </button>
    </div>

    {{-- Lista de banners --}}
    <div class="space-y-3">
        @forelse ($banners as $banner)
            <div class="bg-white rounded-2xl border border-[#d4b896]/30 shadow-sm p-4 flex flex-col sm:flex-row sm:items-center gap-4">
                {{-- Miniatura --}}
                <div class="w-full sm:w-28 h-16 rounded-xl overflow-hidden shrink-0 bg-[#f0e9de] border border-[#d4b896]/30">
                    @if ($banner->imagen)
                        <img src="{{ $banner->imagen }}" alt="{{ $banner->titulo }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-[#d4b896]">
                            <i class="fa-solid fa-image text-xl"></i>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="font-semibold text-[#2c1a0e]">{{ $banner->titulo ?? 'Sin título' }}</p>
                        <span class="inline-block px-2 py-0.5 rounded-full text-[11px] font-medium"
                              style="{{ $banner->activo ? 'background-color:#d4f0d4; color:#386641;' : 'background-color:#f0e9de; color:#8b5e3c;' }}">
                            {{ $banner->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    @if ($banner->subtitulo)
                        <p class="text-sm text-[#8b5e3c]/70 mt-0.5 truncate">{{ $banner->subtitulo }}</p>
                    @endif
                    <div class="flex items-center gap-3 mt-1.5 flex-wrap text-xs text-[#8b5e3c]/60">
                        <span><i class="fa-solid fa-sort mr-1"></i>Orden: {{ $banner->orden }}</span>
                        @if ($banner->url_destino)
                            <span class="truncate max-w-[200px]"><i class="fa-solid fa-link mr-1"></i>{{ $banner->url_destino }}</span>
                        @endif
                        @if ($banner->mostrar_desde || $banner->mostrar_hasta)
                            <span>
                                <i class="fa-solid fa-calendar mr-1"></i>
                                {{ $banner->mostrar_desde?->format('d/m/Y') ?? '∞' }}
                                →
                                {{ $banner->mostrar_hasta?->format('d/m/Y') ?? '∞' }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="flex items-center gap-2 shrink-0">
                    <button
                        wire:click="toggleActivo({{ $banner->id }})"
                        class="p-2 rounded-lg border border-[#d4b896] text-[#8b5e3c] hover:bg-[#f0e9de] transition-colors text-xs"
                        title="{{ $banner->activo ? 'Desactivar' : 'Activar' }}">
                        <i class="fa-solid {{ $banner->activo ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                    </button>
                    <button
                        wire:click="abrirEditar({{ $banner->id }})"
                        class="p-2 rounded-lg border border-[#d4b896] text-[#8b5e3c] hover:bg-[#f0e9de] transition-colors text-xs"
                        title="Editar">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <button
                        wire:click="eliminar({{ $banner->id }})"
                        wire:confirm="¿Eliminar este banner? Esta acción no se puede deshacer."
                        class="p-2 rounded-lg border border-red-200 text-red-400 hover:bg-red-50 transition-colors text-xs"
                        title="Eliminar">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-[#d4b896]/30 shadow-sm p-12 text-center text-[#8b5e3c]/50">
                <i class="fa-solid fa-rectangle-ad text-4xl mb-3 block opacity-30"></i>
                No hay banners creados aún.
                <button wire:click="abrirCrear" class="block mx-auto mt-3 text-[#386641] hover:underline text-sm">
                    Crear el primero
                </button>
            </div>
        @endforelse
    </div>

    {{-- Modal --}}
    @if ($mostrarModal)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" x-data>
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between px-6 py-4 border-b border-[#d4b896]/30">
                    <h2 class="text-xl text-[#2c1a0e]" style="font-family: 'DM Serif Display', serif;">
                        {{ $editandoId ? 'Editar banner' : 'Nuevo banner' }}
                    </h2>
                    <button wire:click="$set('mostrarModal', false)" class="text-[#8b5e3c] hover:text-[#2c1a0e] transition-colors p-1">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    {{-- Imagen --}}
                    <div>
                        <label class="block text-xs font-semibold text-[#2c1a0e]/60 uppercase tracking-wider mb-1.5">URL de imagen *</label>
                        <input type="text" wire:model="imagen" placeholder="https://..." class="w-full px-3 py-2 text-sm border border-[#d4b896]/50 rounded-xl bg-[#faf6f0] text-[#2c1a0e] focus:outline-none focus:ring-2 focus:ring-[#386641]/30">
                        @error('imagen') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Título --}}
                    <div>
                        <label class="block text-xs font-semibold text-[#2c1a0e]/60 uppercase tracking-wider mb-1.5">Título</label>
                        <input type="text" wire:model="titulo" class="w-full px-3 py-2 text-sm border border-[#d4b896]/50 rounded-xl bg-[#faf6f0] text-[#2c1a0e] focus:outline-none focus:ring-2 focus:ring-[#386641]/30">
                        @error('titulo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Subtítulo --}}
                    <div>
                        <label class="block text-xs font-semibold text-[#2c1a0e]/60 uppercase tracking-wider mb-1.5">Subtítulo</label>
                        <input type="text" wire:model="subtitulo" class="w-full px-3 py-2 text-sm border border-[#d4b896]/50 rounded-xl bg-[#faf6f0] text-[#2c1a0e] focus:outline-none focus:ring-2 focus:ring-[#386641]/30">
                    </div>

                    {{-- URL destino --}}
                    <div>
                        <label class="block text-xs font-semibold text-[#2c1a0e]/60 uppercase tracking-wider mb-1.5">URL de destino</label>
                        <input type="text" wire:model="urlDestino" placeholder="https://..." class="w-full px-3 py-2 text-sm border border-[#d4b896]/50 rounded-xl bg-[#faf6f0] text-[#2c1a0e] focus:outline-none focus:ring-2 focus:ring-[#386641]/30">
                        @error('urlDestino') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Texto botón y color fondo --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[#2c1a0e]/60 uppercase tracking-wider mb-1.5">Texto del botón</label>
                            <input type="text" wire:model="textoBoton" class="w-full px-3 py-2 text-sm border border-[#d4b896]/50 rounded-xl bg-[#faf6f0] text-[#2c1a0e] focus:outline-none focus:ring-2 focus:ring-[#386641]/30">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#2c1a0e]/60 uppercase tracking-wider mb-1.5">Color de fondo</label>
                            <input type="text" wire:model="colorFondo" placeholder="#386641" class="w-full px-3 py-2 text-sm border border-[#d4b896]/50 rounded-xl bg-[#faf6f0] text-[#2c1a0e] focus:outline-none focus:ring-2 focus:ring-[#386641]/30">
                        </div>
                    </div>

                    {{-- Orden y activo --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[#2c1a0e]/60 uppercase tracking-wider mb-1.5">Orden</label>
                            <input type="number" wire:model="orden" min="0" class="w-full px-3 py-2 text-sm border border-[#d4b896]/50 rounded-xl bg-[#faf6f0] text-[#2c1a0e] focus:outline-none focus:ring-2 focus:ring-[#386641]/30">
                        </div>
                        <div class="flex items-end pb-2">
                            <label class="flex items-center gap-2 text-sm text-[#2c1a0e] cursor-pointer">
                                <input type="checkbox" wire:model="activo" class="rounded border-[#d4b896] text-[#386641] focus:ring-[#386641]/30">
                                Activo
                            </label>
                        </div>
                    </div>

                    {{-- Rango de fechas --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[#2c1a0e]/60 uppercase tracking-wider mb-1.5">Mostrar desde</label>
                            <input type="date" wire:model="mostrarDesde" class="w-full px-3 py-2 text-sm border border-[#d4b896]/50 rounded-xl bg-[#faf6f0] text-[#2c1a0e] focus:outline-none focus:ring-2 focus:ring-[#386641]/30">
                            @error('mostrarDesde') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#2c1a0e]/60 uppercase tracking-wider mb-1.5">Mostrar hasta</label>
                            <input type="date" wire:model="mostrarHasta" class="w-full px-3 py-2 text-sm border border-[#d4b896]/50 rounded-xl bg-[#faf6f0] text-[#2c1a0e] focus:outline-none focus:ring-2 focus:ring-[#386641]/30">
                            @error('mostrarHasta') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-[#d4b896]/30">
                    <button wire:click="$set('mostrarModal', false)"
                            class="px-4 py-2 text-sm border border-[#d4b896] text-[#8b5e3c] rounded-xl hover:bg-[#f0e9de] transition-colors">
                        Cancelar
                    </button>
                    <button wire:click="guardar"
                            class="px-4 py-2 text-sm bg-[#386641] text-white rounded-xl hover:bg-[#2d5235] transition-colors">
                        {{ $editandoId ? 'Guardar cambios' : 'Crear banner' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
