<div>
    {{-- Encabezado --}}
    <div class="mb-6">
        <p class="text-[10px] tracking-[0.2em] uppercase text-[#8b5e3c] font-semibold mb-1">Marketing</p>
        <h1 class="text-3xl text-[#2c1a0e]" style="font-family: 'DM Serif Display', serif;">Reseñas</h1>
        @if ($pendientes > 0)
            <p class="text-sm text-amber-600 mt-1">
                <i class="fa-solid fa-clock mr-1"></i>
                {{ $pendientes }} reseña{{ $pendientes > 1 ? 's' : '' }} pendiente{{ $pendientes > 1 ? 's' : '' }} de aprobación.
            </p>
        @endif
    </div>

    {{-- Filtro --}}
    <div class="bg-white rounded-2xl border border-[#d4b896]/30 shadow-sm p-4 mb-4">
        <div class="flex items-center gap-3 flex-wrap">
            <span class="text-sm font-medium text-[#2c1a0e]">Filtrar:</span>
            <button
                wire:click="$set('filtroAprobada', '')"
                class="px-3 py-1.5 rounded-lg text-sm transition-colors {{ $filtroAprobada === '' ? 'bg-[#386641] text-white' : 'border border-[#d4b896] text-[#8b5e3c] hover:bg-[#f0e9de]' }}">
                Todas
            </button>
            <button
                wire:click="$set('filtroAprobada', '0')"
                class="px-3 py-1.5 rounded-lg text-sm transition-colors {{ $filtroAprobada === '0' ? 'bg-amber-500 text-white' : 'border border-[#d4b896] text-[#8b5e3c] hover:bg-[#f0e9de]' }}">
                Pendientes
            </button>
            <button
                wire:click="$set('filtroAprobada', '1')"
                class="px-3 py-1.5 rounded-lg text-sm transition-colors {{ $filtroAprobada === '1' ? 'bg-[#386641] text-white' : 'border border-[#d4b896] text-[#8b5e3c] hover:bg-[#f0e9de]' }}">
                Aprobadas
            </button>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-2xl border border-[#d4b896]/30 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-[#d4b896]/30 bg-[#f0e9de]/40">
                        <th class="text-left px-4 py-3 font-semibold text-[#2c1a0e]/60 uppercase text-[11px] tracking-wider">Producto</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#2c1a0e]/60 uppercase text-[11px] tracking-wider">Autor</th>
                        <th class="text-center px-4 py-3 font-semibold text-[#2c1a0e]/60 uppercase text-[11px] tracking-wider">Calificación</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#2c1a0e]/60 uppercase text-[11px] tracking-wider">Comentario</th>
                        <th class="text-center px-4 py-3 font-semibold text-[#2c1a0e]/60 uppercase text-[11px] tracking-wider">Estado</th>
                        <th class="text-right px-4 py-3 font-semibold text-[#2c1a0e]/60 uppercase text-[11px] tracking-wider">Fecha</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#d4b896]/20">
                    @forelse ($resenas as $resena)
                        <tr class="hover:bg-[#faf6f0] transition-colors">
                            <td class="px-4 py-3">
                                <span class="font-medium text-[#2c1a0e]">
                                    {{ $resena->producto?->nombre ?? 'Producto eliminado' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-medium text-[#2c1a0e]">{{ $resena->nombre_cliente }}</p>
                                <p class="text-xs text-[#8b5e3c]/60">{{ $resena->email_cliente }}</p>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-0.5">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa-{{ $i <= $resena->calificacion ? 'solid' : 'regular' }} fa-star text-xs"
                                           style="color: {{ $i <= $resena->calificacion ? '#a7c957' : '#d4b896' }};"></i>
                                    @endfor
                                </div>
                                <p class="text-[11px] text-[#8b5e3c]/60 mt-0.5">{{ $resena->calificacion }}/5</p>
                            </td>
                            <td class="px-4 py-3 max-w-xs">
                                <p class="text-[#2c1a0e]/80 line-clamp-2">{{ $resena->comentario ?? '—' }}</p>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if ($resena->aprobada)
                                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium" style="background-color:#d4f0d4; color:#386641;">
                                        Aprobada
                                    </span>
                                @else
                                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium" style="background-color:#fff3cd; color:#856404;">
                                        Pendiente
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right text-xs text-[#8b5e3c]/70">
                                {{ $resena->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @if (!$resena->aprobada)
                                        <button
                                            wire:click="aprobar({{ $resena->id }})"
                                            class="p-1.5 rounded-lg text-[#386641] hover:bg-[#d4f0d4] transition-colors"
                                            title="Aprobar">
                                            <i class="fa-solid fa-check text-xs"></i>
                                        </button>
                                    @else
                                        <button
                                            wire:click="rechazar({{ $resena->id }})"
                                            class="p-1.5 rounded-lg text-amber-600 hover:bg-amber-50 transition-colors"
                                            title="Rechazar">
                                            <i class="fa-solid fa-xmark text-xs"></i>
                                        </button>
                                    @endif
                                    <button
                                        wire:click="eliminar({{ $resena->id }})"
                                        wire:confirm="¿Eliminar esta reseña? Esta acción no se puede deshacer."
                                        class="p-1.5 rounded-lg text-red-400 hover:bg-red-50 transition-colors"
                                        title="Eliminar">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-[#8b5e3c]/50">
                                <i class="fa-solid fa-star text-3xl mb-2 block opacity-30"></i>
                                No se encontraron reseñas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($resenas->hasPages())
            <div class="px-4 py-3 border-t border-[#d4b896]/20">
                {{ $resenas->links() }}
            </div>
        @endif
    </div>
</div>
