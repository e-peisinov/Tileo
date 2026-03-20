<div>
    {{-- Encabezado --}}
    <div class="mb-6">
        <p class="text-[10px] tracking-[0.2em] uppercase text-[#8b5e3c] font-semibold mb-1">Pedidos</p>
        <h1 class="text-3xl text-[#2c1a0e]" style="font-family: 'DM Serif Display', serif;">Clientes</h1>
        <p class="text-sm text-[#8b5e3c]/70 mt-1">Historial de clientes derivado de los pedidos realizados.</p>
    </div>

    {{-- Buscador --}}
    <div class="bg-white rounded-2xl border border-[#d4b896]/30 shadow-sm p-4 mb-4">
        <div class="relative max-w-sm">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-[#8b5e3c]/40 text-sm"></i>
            <input
                type="text"
                wire:model.live.debounce.300ms="busqueda"
                placeholder="Buscar por nombre o email..."
                class="w-full pl-9 pr-4 py-2 text-sm border border-[#d4b896]/50 rounded-xl bg-[#faf6f0] text-[#2c1a0e] placeholder-[#8b5e3c]/40 focus:outline-none focus:ring-2 focus:ring-[#386641]/30"
            >
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-2xl border border-[#d4b896]/30 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-[#d4b896]/30 bg-[#f0e9de]/40">
                        <th class="text-left px-4 py-3 font-semibold text-[#2c1a0e]/60 uppercase text-[11px] tracking-wider">Cliente</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#2c1a0e]/60 uppercase text-[11px] tracking-wider">Email</th>
                        <th class="text-center px-4 py-3 font-semibold text-[#2c1a0e]/60 uppercase text-[11px] tracking-wider">Pedidos</th>
                        <th class="text-right px-4 py-3 font-semibold text-[#2c1a0e]/60 uppercase text-[11px] tracking-wider">Total gastado</th>
                        <th class="text-right px-4 py-3 font-semibold text-[#2c1a0e]/60 uppercase text-[11px] tracking-wider">Último pedido</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#d4b896]/20">
                    @forelse ($clientes as $cliente)
                        <tr class="hover:bg-[#faf6f0] transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold shrink-0"
                                         style="background-color: #386641;">
                                        {{ strtoupper(substr($cliente->nombre_cliente, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-[#2c1a0e]">{{ $cliente->nombre_cliente }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-[#8b5e3c]">{{ $cliente->email_cliente }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-semibold text-white"
                                      style="background-color: #386641;">
                                    {{ $cliente->total_pedidos }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-[#2c1a0e]">
                                ${{ number_format($cliente->total_gastado, 2, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-right text-[#8b5e3c]/70 text-xs">
                                {{ \Carbon\Carbon::parse($cliente->ultimo_pedido)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.pedidos') }}?busqueda={{ urlencode($cliente->email_cliente) }}" wire:navigate
                                   class="inline-flex items-center gap-1 text-xs px-3 py-1.5 rounded-lg border border-[#d4b896] text-[#8b5e3c] hover:bg-[#f0e9de] transition-colors">
                                    <i class="fa-solid fa-box text-[10px]"></i> Ver pedidos
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-[#8b5e3c]/50">
                                <i class="fa-solid fa-users text-3xl mb-2 block opacity-30"></i>
                                No se encontraron clientes.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($clientes->hasPages())
            <div class="px-4 py-3 border-t border-[#d4b896]/20">
                {{ $clientes->links() }}
            </div>
        @endif
    </div>
</div>
