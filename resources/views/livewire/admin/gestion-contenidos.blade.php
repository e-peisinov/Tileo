<div>
    {{-- Encabezado --}}
    <div class="mb-8">
        <p class="text-[#8b5e3c]/60 tracking-[0.25em] uppercase text-[10px] font-semibold mb-1">Contenido</p>
        <h1 class="text-3xl text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">Páginas</h1>
        <p class="text-sm text-[#8b5e3c]/70 mt-1">Editá el contenido de las páginas públicas del sitio.</p>
    </div>

    @if($editandoId)
        {{-- Editor --}}
        <div class="bg-white rounded-2xl border border-[#d4b896]/30 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-[#d4b896]/20 flex items-center justify-between"
                 style="background: linear-gradient(to right, #f0e9de, #faf6f0);">
                <div>
                    <h2 class="text-base text-[#2c1a0e]" style="font-family:'DM Serif Display',serif;">
                        Editando: {{ $etiqueta }}
                    </h2>
                    <p class="text-[10px] text-[#8b5e3c]/60 mt-0.5 uppercase tracking-wider">Tipo: {{ $tipo }}</p>
                </div>
                <button wire:click="cancelar" class="text-[#8b5e3c] hover:text-[#2c1a0e] transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-6">
                @if($tipo === 'json_faq')
                    {{-- Editor de FAQs --}}
                    <div class="space-y-4 mb-5">
                        @foreach($faqs as $i => $faq)
                            <div class="bg-[#faf6f0] rounded-xl p-4 border border-[#d4b896]/20">
                                <div class="flex items-start gap-3">
                                    <span class="w-6 h-6 rounded-full bg-[#386641]/10 text-[#386641] text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                                    <div class="flex-1 space-y-2">
                                        <input wire:model="faqs.{{ $i }}.pregunta" type="text" placeholder="Pregunta"
                                               class="w-full border border-[#d4b896]/50 bg-white rounded-lg px-3 py-2 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors font-medium">
                                        <textarea wire:model="faqs.{{ $i }}.respuesta" rows="2" placeholder="Respuesta"
                                                  class="w-full border border-[#d4b896]/50 bg-white rounded-lg px-3 py-2 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors resize-none"></textarea>
                                    </div>
                                    <button wire:click="eliminarFaq({{ $i }})"
                                            class="text-[#d4b896] hover:text-red-500 transition-colors flex-shrink-0 p-1">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button wire:click="agregarFaq"
                            class="flex items-center gap-2 text-[#386641] border border-[#386641]/30 rounded-lg px-4 py-2 text-sm hover:bg-[#386641]/5 transition-colors mb-5">
                        <i class="fa-solid fa-plus text-xs"></i> Agregar pregunta
                    </button>
                @elseif($tipo === 'html')
                    {{-- Editor HTML con Quill.js --}}
                    <div class="mb-2" x-data="{
                        quill: null,
                        init() {
                            this.quill = new Quill(this.$refs.editor, {
                                theme: 'snow',
                                modules: {
                                    toolbar: [
                                        ['bold', 'italic', 'underline'],
                                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                                        ['link'],
                                        ['clean']
                                    ]
                                }
                            });
                            // Cargar contenido inicial
                            const inicial = $wire.cuerpo;
                            if (inicial) this.quill.clipboard.dangerouslyPasteHTML(inicial);
                            // Sincronizar cambios al modelo Livewire
                            this.quill.on('text-change', () => {
                                $wire.set('cuerpo', this.quill.root.innerHTML);
                            });
                        }
                    }">
                        <div x-ref="editor"
                             class="bg-white rounded-b-lg"
                             style="min-height: 220px; font-size: 14px;"></div>
                    </div>
                @else
                    {{-- Texto plano --}}
                    <textarea wire:model="cuerpo" rows="8"
                              class="w-full border border-[#d4b896]/50 bg-[#faf6f0] rounded-xl px-4 py-3 text-sm text-[#2c1a0e] focus:outline-none focus:border-[#386641] transition-colors resize-y mb-2"></textarea>
                @endif

                <div class="flex gap-3 pt-4 border-t border-[#d4b896]/20">
                    <button wire:click="cancelar"
                            class="px-5 py-2.5 border border-[#d4b896]/50 text-[#8b5e3c] rounded-xl text-sm hover:bg-[#f0e9de] transition-colors">
                        Cancelar
                    </button>
                    <button wire:click="guardar"
                            class="px-5 py-2.5 bg-[#386641] text-white rounded-xl text-sm font-semibold hover:bg-[#2d5534] transition-colors">
                        <i class="fa-solid fa-check text-xs mr-1"></i> Guardar cambios
                    </button>
                </div>
            </div>
        </div>
    @else
        {{-- Lista de contenidos --}}
        <div class="space-y-3">
            @foreach($contenidos as $contenido)
                <div class="bg-white rounded-2xl border border-[#d4b896]/30 shadow-sm p-5 flex items-center justify-between gap-4 hover:shadow-md transition-shadow">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <p class="text-base font-medium text-[#2c1a0e]">{{ $contenido->etiqueta ?? $contenido->clave }}</p>
                            <span class="text-[10px] bg-[#f0e9de] text-[#8b5e3c] px-2 py-0.5 rounded-full uppercase tracking-wider font-semibold">
                                {{ $contenido->tipo }}
                            </span>
                        </div>
                        @if($contenido->descripcion)
                            <p class="text-sm text-[#8b5e3c]/70">{{ $contenido->descripcion }}</p>
                        @endif
                        <p class="text-xs text-[#8b5e3c]/40 mt-1 font-mono">{{ $contenido->clave }}</p>
                    </div>
                    <button wire:click="abrirEditar({{ $contenido->id }})"
                            class="flex items-center gap-2 bg-[#386641] text-white px-4 py-2 rounded-xl text-xs font-semibold hover:bg-[#2d5534] transition-colors flex-shrink-0">
                        <i class="fa-solid fa-pen text-[10px]"></i> Editar
                    </button>
                </div>
            @endforeach

            @if($contenidos->isEmpty())
                <div class="py-16 text-center bg-white rounded-2xl border border-[#d4b896]/30">
                    <i class="fa-solid fa-file-alt text-3xl text-[#d4b896] mb-3 block"></i>
                    <p class="text-sm text-[#8b5e3c]/60">No hay contenidos disponibles. Ejecutá el seeder de contenidos.</p>
                </div>
            @endif
        </div>
    @endif
</div>

@if($editandoId && $tipo === 'html')
@push('scripts')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
@endpush
@endif
