<div>
    {{-- Encabezado --}}
    <div class="mb-8">
        <p class="text-[#8b5e3c]/60 tracking-[0.25em] uppercase text-[10px] font-semibold mb-1">Sistema</p>
        <h1 class="text-4xl text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">Configuración</h1>
    </div>

    {{-- Banner de éxito --}}
    @if($guardado)
        <div class="flex items-center gap-2.5 text-[#386641] text-sm rounded-xl border border-[#386641]/20 px-4 py-3 mb-5 shadow-sm"
             style="background-color: rgba(56,102,65,0.06);">
            <div class="w-5 h-5 rounded-full flex items-center justify-center" style="background-color: rgba(56,102,65,0.15);">
                <i class="fa-solid fa-check text-[10px]"></i>
            </div>
            Configuración guardada correctamente.
        </div>
    @endif

    <form wire:submit="guardar" class="space-y-5">

        {{-- Modo vacaciones --}}
        <div class="bg-white rounded-2xl shadow-sm border border-[#d4b896]/20 overflow-hidden">
            <div class="px-6 py-4 border-b border-[#d4b896]/20" style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                <h2 class="text-base text-[#2c1a0e] flex items-center gap-2" style="font-family:'DM Serif Display',serif;">
                    <i class="fa-solid fa-umbrella-beach text-sm text-[#8b5e3c]/50"></i>
                    Modo vacaciones
                </h2>
                <p class="text-[11px] text-[#8b5e3c]/60 mt-0.5">Cuando está activo, el checkout queda deshabilitado y se muestra el mensaje configurado.</p>
            </div>
            <div class="p-6 space-y-4">
                {{-- Toggle modo vacaciones --}}
                <div class="flex items-center justify-between p-4 rounded-xl border border-[#d4b896]/30"
                     style="background-color: rgba(250,246,240,0.6);">
                    <div>
                        <p class="text-sm font-semibold text-[#2c1a0e]">Activar modo vacaciones</p>
                        <p class="text-[11px] text-[#8b5e3c]/60 mt-0.5">Los clientes no podrán confirmar pedidos mientras esté activo.</p>
                    </div>
                    <label class="flex items-center cursor-pointer">
                        <div class="relative">
                            <input type="checkbox"
                                   wire:model.live="valores.modo_vacaciones"
                                   class="sr-only peer"
                                   value="true"
                                   @checked(filter_var($valores['modo_vacaciones'] ?? false, FILTER_VALIDATE_BOOLEAN))>
                            <div class="w-10 h-5 bg-[#d4b896]/40 rounded-full peer peer-checked:bg-red-400 transition-colors duration-200"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-5"></div>
                        </div>
                    </label>
                </div>

                {{-- Mensaje vacaciones --}}
                @php $config = $configuraciones->firstWhere('clave', 'mensaje_vacaciones'); @endphp
                @if($config)
                    <div>
                        <label class="block text-xs tracking-wider text-[#8b5e3c] uppercase mb-1.5 font-semibold">
                            {{ $config->etiqueta }}
                        </label>
                        <textarea wire:model.live="valores.mensaje_vacaciones" rows="2"
                                  class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-lg px-3 py-2.5 text-sm text-[#2c1a0e]
                                         focus:outline-none focus:ring-2 focus:ring-[#386641]/20 focus:border-[#386641] transition-all duration-200 resize-none"></textarea>
                        @error('valores.mensaje_vacaciones') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                @endif

                {{-- Preview modo vacaciones --}}
                @if(filter_var($valores['modo_vacaciones'] ?? false, FILTER_VALIDATE_BOOLEAN))
                    <div>
                        <p class="text-[10px] tracking-wider text-[#8b5e3c]/60 uppercase font-semibold mb-2">
                            <i class="fa-solid fa-eye text-[9px] mr-1"></i> Vista previa — así verá el cliente el checkout:
                        </p>
                        <div class="rounded-xl border-2 border-dashed border-[#d4b896]/40 p-4">
                            <div class="max-w-sm mx-auto text-center py-6">
                                <div class="w-12 h-12 bg-[#f0e9de] rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-umbrella-beach text-2xl text-[#8b5e3c]"></i>
                                </div>
                                <h2 class="text-lg text-[#2c1a0e] mb-2" style="font-family:'DM Serif Display',serif;">Estamos de vacaciones</h2>
                                <p class="text-[#8b5e3c]/80 text-sm leading-relaxed">
                                    {{ $valores['mensaje_vacaciones'] ?: 'Temporalmente no estamos recibiendo pedidos.' }}
                                </p>
                                <div class="mt-4 inline-block border border-[#386641]/40 text-[#386641] px-6 py-2 text-xs tracking-wider">
                                    Ver catálogo
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 rounded-xl px-6 py-2.5 text-[13px] font-semibold text-white shadow-sm
                           hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 disabled:opacity-60"
                    style="background: linear-gradient(135deg, #386641 0%, #2d5534 100%);">
                <span wire:loading.remove wire:target="guardar">
                    <i class="fa-solid fa-floppy-disk text-xs"></i> Guardar cambios
                </span>
                <span wire:loading wire:target="guardar" class="flex items-center gap-2">
                    <i class="fa-solid fa-spinner fa-spin text-xs"></i> Guardando...
                </span>
            </button>
        </div>

    </form>
</div>
