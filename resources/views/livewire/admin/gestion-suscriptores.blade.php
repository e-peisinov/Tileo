<div>
    {{-- Encabezado --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
        <div>
            <p class="text-[10px] tracking-[0.2em] uppercase text-[#8b5e3c] font-semibold mb-1">Marketing</p>
            <h1 class="text-3xl text-[#2c1a0e]" style="font-family: 'DM Serif Display', serif;">Suscriptores</h1>
        </div>
        <button
            wire:click="exportarCsv"
            class="inline-flex items-center gap-2 px-4 py-2 bg-[#386641] text-white text-sm rounded-xl hover:bg-[#2d5235] transition-colors">
            <i class="fa-solid fa-download text-xs"></i> Exportar activos (.csv)
        </button>
    </div>

    {{-- Estadísticas --}}
    <div class="grid grid-cols-2 gap-4 mb-5">
        <div class="bg-white rounded-2xl border border-[#d4b896]/30 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-[#386641]">{{ $totalActivos }}</p>
            <p class="text-xs text-[#8b5e3c]/70 mt-0.5">Suscriptores activos</p>
        </div>
        <div class="bg-white rounded-2xl border border-[#d4b896]/30 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-[#2c1a0e]">{{ $totalTotal }}</p>
            <p class="text-xs text-[#8b5e3c]/70 mt-0.5">Total registrados</p>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="bg-white rounded-2xl border border-[#d4b896]/30 shadow-sm p-4 mb-4 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-[#8b5e3c]/40 text-sm"></i>
            <input
                type="text"
                wire:model.live.debounce.300ms="busqueda"
                placeholder="Buscar por email..."
                class="w-full pl-9 pr-4 py-2 text-sm border border-[#d4b896]/50 rounded-xl bg-[#faf6f0] text-[#2c1a0e] placeholder-[#8b5e3c]/40 focus:outline-none focus:ring-2 focus:ring-[#386641]/30"
            >
        </div>
        <label class="flex items-center gap-2 text-sm text-[#2c1a0e] cursor-pointer select-none">
            <input type="checkbox" wire:model.live="soloActivos" class="rounded border-[#d4b896] text-[#386641] focus:ring-[#386641]/30">
            Solo activos
        </label>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-2xl border border-[#d4b896]/30 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-[#d4b896]/30 bg-[#f0e9de]/40">
                        <th class="text-left px-4 py-3 font-semibold text-[#2c1a0e]/60 uppercase text-[11px] tracking-wider">Email</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#2c1a0e]/60 uppercase text-[11px] tracking-wider">Nombre</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#2c1a0e]/60 uppercase text-[11px] tracking-wider">Origen</th>
                        <th class="text-center px-4 py-3 font-semibold text-[#2c1a0e]/60 uppercase text-[11px] tracking-wider">Estado</th>
                        <th class="text-right px-4 py-3 font-semibold text-[#2c1a0e]/60 uppercase text-[11px] tracking-wider">Fecha</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#d4b896]/20">
                    @forelse ($suscriptores as $suscriptor)
                        <tr class="hover:bg-[#faf6f0] transition-colors">
                            <td class="px-4 py-3 text-[#2c1a0e] font-medium">{{ $suscriptor->email }}</td>
                            <td class="px-4 py-3 text-[#8b5e3c]">{{ $suscriptor->nombre ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if ($suscriptor->origen)
                                    <span class="inline-block px-2 py-0.5 text-[11px] rounded-full bg-[#f0e9de] text-[#8b5e3c]">
                                        {{ $suscriptor->origen }}
                                    </span>
                                @else
                                    <span class="text-[#8b5e3c]/40">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button
                                    wire:click="toggleActivo({{ $suscriptor->id }})"
                                    wire:confirm="{{ $suscriptor->activo ? '¿Desactivar este suscriptor?' : '¿Activar este suscriptor?' }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium transition-colors"
                                    style="{{ $suscriptor->activo ? 'background-color:#d4f0d4; color:#386641;' : 'background-color:#f0e9de; color:#8b5e3c;' }}">
                                    <i class="fa-solid {{ $suscriptor->activo ? 'fa-circle-check' : 'fa-circle-xmark' }} text-[10px]"></i>
                                    {{ $suscriptor->activo ? 'Activo' : 'Inactivo' }}
                                </button>
                            </td>
                            <td class="px-4 py-3 text-right text-xs text-[#8b5e3c]/70">
                                {{ $suscriptor->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button
                                    wire:click="eliminar({{ $suscriptor->id }})"
                                    wire:confirm="¿Eliminar este suscriptor? Esta acción no se puede deshacer."
                                    class="text-red-400 hover:text-red-600 transition-colors p-1">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-[#8b5e3c]/50">
                                <i class="fa-solid fa-envelope text-3xl mb-2 block opacity-30"></i>
                                No se encontraron suscriptores.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($suscriptores->hasPages())
            <div class="px-4 py-3 border-t border-[#d4b896]/20">
                {{ $suscriptores->links() }}
            </div>
        @endif
    </div>
</div>
