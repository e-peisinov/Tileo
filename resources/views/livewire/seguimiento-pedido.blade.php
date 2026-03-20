<div>
    {{-- Encabezado --}}
    <div class="py-14 px-4 text-center" style="background-color: #f0e9de;">
        <p class="text-xs tracking-[0.3em] uppercase text-[#8b5e3c] mb-3 font-semibold">Tileo</p>
        <h1 class="text-4xl md:text-5xl text-[#2c1a0e] mb-4" style="font-family: 'DM Serif Display', serif;">
            Seguimiento de Pedido
        </h1>
        <p class="text-[#8b5e3c]/70 text-base max-w-xl mx-auto">
            Ingresá tu número de pedido o email para ver el estado actual.
        </p>
    </div>

    {{-- Formulario de búsqueda --}}
    <div class="py-12 px-4" style="background-color: #faf6f0;">
        <div class="max-w-lg mx-auto">
            <div class="bg-white rounded-2xl border border-[#d4b896]/40 shadow-sm p-8">

                {{-- Tabs de modo --}}
                <div class="flex gap-1 mb-6 bg-[#f0e9de] rounded-xl p-1">
                    <button wire:click="cambiarModo('numero')"
                            class="flex-1 py-2 rounded-lg text-xs font-semibold transition-all duration-200
                                   {{ $modoBusqueda === 'numero' ? 'bg-white text-[#386641] shadow-sm' : 'text-[#8b5e3c]/70 hover:text-[#2c1a0e]' }}">
                        <i class="fa-solid fa-hashtag text-[10px] mr-1"></i> Por número de pedido
                    </button>
                    <button wire:click="cambiarModo('email')"
                            class="flex-1 py-2 rounded-lg text-xs font-semibold transition-all duration-200
                                   {{ $modoBusqueda === 'email' ? 'bg-white text-[#386641] shadow-sm' : 'text-[#8b5e3c]/70 hover:text-[#2c1a0e]' }}">
                        <i class="fa-solid fa-envelope text-[10px] mr-1"></i> Por email
                    </button>
                </div>

                <form wire:submit="buscar" class="space-y-4">
                    @if($modoBusqueda === 'numero')
                        <div>
                            <label for="numeroPedido" class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-2 font-semibold">
                                Número de pedido
                            </label>
                            <input
                                wire:model="numeroPedido"
                                id="numeroPedido"
                                type="text"
                                placeholder="TIL-0001"
                                autocomplete="off"
                                class="w-full border border-[#d4b896]/60 bg-[#faf6f0] rounded-xl px-4 py-3 text-[#2c1a0e] text-base
                                       focus:outline-none focus:ring-2 focus:ring-[#386641]/25 focus:border-[#386641] transition-all duration-200
                                       placeholder:text-[#8b5e3c]/40"
                            >
                            @error('numeroPedido')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <div>
                            <label for="emailPedido" class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-2 font-semibold">
                                Email del pedido
                            </label>
                            <input
                                wire:model="emailPedido"
                                id="emailPedido"
                                type="email"
                                placeholder="tu@email.com"
                                autocomplete="off"
                                class="w-full border border-[#d4b896]/60 bg-[#faf6f0] rounded-xl px-4 py-3 text-[#2c1a0e] text-base
                                       focus:outline-none focus:ring-2 focus:ring-[#386641]/25 focus:border-[#386641] transition-all duration-200
                                       placeholder:text-[#8b5e3c]/40"
                            >
                            @error('emailPedido')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                            <p class="text-[11px] text-[#8b5e3c]/60 mt-1.5">Verás todos tus pedidos realizados con ese email.</p>
                        </div>
                    @endif

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="w-full py-3 rounded-xl text-white text-sm font-semibold shadow-sm
                               hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200
                               disabled:opacity-60 disabled:cursor-not-allowed"
                        style="background: linear-gradient(135deg, #386641 0%, #2d5534 100%);"
                    >
                        <span wire:loading.remove wire:target="buscar">
                            <i class="fa-solid fa-magnifying-glass mr-2"></i> Buscar pedido
                        </span>
                        <span wire:loading wire:target="buscar" class="flex items-center justify-center gap-2">
                            <i class="fa-solid fa-spinner fa-spin text-xs"></i> Buscando...
                        </span>
                    </button>
                </form>
            </div>

            {{-- Sin resultados --}}
            @if($buscado && !$pedido && $pedidos->isEmpty())
                <div class="mt-6 bg-white rounded-2xl border border-[#d4b896]/40 shadow-sm p-6 text-center">
                    <i class="fa-solid fa-circle-exclamation text-2xl mb-3" style="color: #8b5e3c;"></i>
                    <p class="text-[#2c1a0e] font-medium">No encontramos ningún pedido.</p>
                    <p class="text-sm text-[#8b5e3c]/70 mt-1">
                        @if($modoBusqueda === 'numero')
                            Verificá que el número sea correcto (ejemplo: TIL-0001).
                        @else
                            Verificá que el email sea el mismo que usaste al hacer el pedido.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- Resultado: búsqueda por número (1 pedido) --}}
    @if($modoBusqueda === 'numero' && $pedido)
        <div class="pb-16 px-4" style="background-color: #faf6f0;">
            <div class="max-w-3xl mx-auto">
                @include('livewire.partials.pedido-card', ['pedido' => $pedido])
            </div>
        </div>
    @endif

    {{-- Resultado: búsqueda por email (múltiples pedidos) --}}
    @if($modoBusqueda === 'email' && $pedidos->isNotEmpty())
        <div class="pb-16 px-4" style="background-color: #faf6f0;">
            <div class="max-w-3xl mx-auto">
                @if($pedidos->count() > 1)
                    <p class="text-sm text-[#8b5e3c]/70 mb-5 text-center">
                        <i class="fa-solid fa-boxes-stacked mr-1"></i>
                        Encontramos {{ $pedidos->count() }} pedidos con ese email.
                    </p>
                @endif
                <div class="space-y-6">
                    @foreach($pedidos as $pedidoItem)
                        @include('livewire.partials.pedido-card', ['pedido' => $pedidoItem])
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
