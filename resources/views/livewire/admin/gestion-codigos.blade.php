<div>
    {{-- Encabezado --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <p class="text-[#8b5e3c]/60 tracking-[0.25em] uppercase text-[10px] font-semibold mb-1">Marketing</p>
            <h1 class="text-3xl text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">Códigos de descuento</h1>
        </div>
        <button wire:click="abrirCrear"
                class="flex items-center gap-2 bg-[#386641] text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm hover:bg-[#2d5534] transition-colors">
            <i class="fa-solid fa-plus text-xs"></i> Nuevo código
        </button>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-2xl border border-[#d4b896]/30 shadow-sm overflow-hidden">
        @if($codigos->isEmpty())
            <div class="py-16 text-center text-[#8b5e3c]/60 text-sm">
                <i class="fa-solid fa-ticket text-3xl mb-3 block text-[#d4b896]"></i>
                No hay códigos de descuento creados.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-[#d4b896]/20" style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                            <th class="text-left px-5 py-3 text-[10px] tracking-wider text-[#8b5e3c] uppercase font-semibold">Código</th>
                            <th class="text-left px-5 py-3 text-[10px] tracking-wider text-[#8b5e3c] uppercase font-semibold">Descuento</th>
                            <th class="text-left px-5 py-3 text-[10px] tracking-wider text-[#8b5e3c] uppercase font-semibold">Usos</th>
                            <th class="text-left px-5 py-3 text-[10px] tracking-wider text-[#8b5e3c] uppercase font-semibold">Vence</th>
                            <th class="text-left px-5 py-3 text-[10px] tracking-wider text-[#8b5e3c] uppercase font-semibold">Estado</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#d4b896]/10">
                        @foreach($codigos as $codigo)
                            <tr class="hover:bg-[#faf6f0]/60 transition-colors">
                                <td class="px-5 py-3.5">
                                    <span class="font-mono font-bold text-[#2c1a0e] tracking-wider">{{ $codigo->codigo }}</span>
                                    @if($codigo->solo_un_uso_por_email)
                                        <span class="ml-2 text-[10px] bg-[#f0e9de] text-[#8b5e3c] px-1.5 py-0.5 rounded">1 uso/email</span>
                                    @endif
                                    @if($codigo->minimo_compra)
                                        <p class="text-[10px] text-[#8b5e3c]/60 mt-0.5">Mín. ${{ number_format($codigo->minimo_compra, 2, ',', '.') }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-[#386641]">
                                    @if($codigo->tipo === 'porcentaje')
                                        {{ $codigo->valor }}%
                                    @else
                                        ${{ number_format($codigo->valor, 2, ',', '.') }}
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-[#2c1a0e]/70">
                                    {{ $codigo->usos_actuales }}
                                    @if($codigo->usos_maximos)
                                        / {{ $codigo->usos_maximos }}
                                    @else
                                        / ∞
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-[#2c1a0e]/70">
                                    @if($codigo->expira_en)
                                        <span class="{{ $codigo->expira_en->isPast() ? 'text-red-500' : '' }}">
                                            {{ $codigo->expira_en->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="text-[#8b5e3c]/40 italic text-xs">Sin vencimiento</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    <button wire:click="toggleActivo({{ $codigo->id }})"
                                            class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full transition-colors">
                                        @if($codigo->activo && $codigo->estaVigente())
                                            <span class="bg-[#386641]/10 text-[#386641] px-2.5 py-1 rounded-full flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#386641] inline-block"></span> Activo
                                            </span>
                                        @elseif(!$codigo->activo)
                                            <span class="bg-[#d4b896]/20 text-[#8b5e3c] px-2.5 py-1 rounded-full flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#8b5e3c] inline-block"></span> Inactivo
                                            </span>
                                        @else
                                            <span class="bg-red-50 text-red-500 px-2.5 py-1 rounded-full flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-400 inline-block"></span> Expirado
                                            </span>
                                        @endif
                                    </button>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2 justify-end">
                                        <button wire:click="abrirEditar({{ $codigo->id }})"
                                                class="text-[#8b5e3c]/60 hover:text-[#386641] transition-colors p-1">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </button>
                                        <button wire:click="eliminar({{ $codigo->id }})"
                                                wire:confirm="¿Eliminar el código {{ $codigo->codigo }}?"
                                                class="text-[#8b5e3c]/60 hover:text-red-500 transition-colors p-1">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Paginación --}}
    @if($codigos->hasPages())
        <div class="mt-4">
            {{ $codigos->links() }}
        </div>
    @endif

    {{-- Modal crear/editar --}}
    @if($mostrarModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background-color: rgba(44,26,14,0.55); backdrop-filter: blur(4px);">
            <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl"
                 x-data x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                <div class="px-6 py-4 border-b border-[#d4b896]/30 flex items-center justify-between"
                     style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                    <h2 class="text-lg text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">
                        {{ $editandoId ? 'Editar código' : 'Nuevo código' }}
                    </h2>
                    <button wire:click="$set('mostrarModal', false)"
                            class="text-[#8b5e3c] hover:text-[#2c1a0e] transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-[10px] tracking-wider text-[#8b5e3c] uppercase font-semibold mb-1.5">Código *</label>
                            <input wire:model="codigo" type="text" placeholder="BIENVENIDA10"
                                   class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-xl px-3 py-2.5 text-sm uppercase tracking-wider text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
                            @error('codigo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] tracking-wider text-[#8b5e3c] uppercase font-semibold mb-1.5">Tipo *</label>
                            <select wire:model.live="tipo"
                                    class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-xl px-3 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
                                <option value="porcentaje">Porcentaje (%)</option>
                                <option value="monto_fijo">Monto fijo ($)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] tracking-wider text-[#8b5e3c] uppercase font-semibold mb-1.5">
                                Valor * {{ $tipo === 'porcentaje' ? '(%)' : '($)' }}
                            </label>
                            <input wire:model="valor" type="number" step="0.01" min="0"
                                   class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-xl px-3 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
                            @error('valor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] tracking-wider text-[#8b5e3c] uppercase font-semibold mb-1.5">Compra mínima ($)</label>
                            <input wire:model="minimoCompra" type="number" step="0.01" min="0" placeholder="Sin mínimo"
                                   class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-xl px-3 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] tracking-wider text-[#8b5e3c] uppercase font-semibold mb-1.5">Usos máximos</label>
                            <input wire:model="usosMaximos" type="number" min="1" placeholder="Sin límite"
                                   class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-xl px-3 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-[10px] tracking-wider text-[#8b5e3c] uppercase font-semibold mb-1.5">Fecha de vencimiento</label>
                            <input wire:model="expiraEn" type="date"
                                   class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-xl px-3 py-2.5 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors">
                        </div>
                    </div>

                    <div class="flex flex-col gap-2.5 pt-2">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input wire:model="activo" type="checkbox" class="rounded border-[#d4b896] text-[#386641]">
                            <span class="text-sm text-[#2c1a0e]">Código activo</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input wire:model="soloUnUsoPorEmail" type="checkbox" class="rounded border-[#d4b896] text-[#386641]">
                            <span class="text-sm text-[#2c1a0e]">Un solo uso por email</span>
                        </label>
                    </div>
                </div>

                <div class="px-6 pb-6 flex gap-3">
                    <button wire:click="$set('mostrarModal', false)"
                            class="flex-1 border border-[#d4b896]/50 text-[#8b5e3c] rounded-xl py-2.5 text-sm hover:bg-[#f0e9de] transition-colors">
                        Cancelar
                    </button>
                    <button wire:click="guardar"
                            class="flex-1 bg-[#386641] text-white rounded-xl py-2.5 text-sm font-semibold hover:bg-[#2d5534] transition-colors">
                        Guardar
                    </button>
                </div>

            </div>
        </div>
    @endif
</div>
