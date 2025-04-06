<div class="p-6">
    <div class="mb-4">
        <select wire:model.live="filtroTipo" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <option value="todos">Todos os Contatos</option>
            <option value="2dias">2 Dias</option>
            <option value="2semanas">2 Semanas</option>
            <option value="2meses">2 Meses</option>
        </select>
    </div>

    @foreach($followups as $compradorId => $grupoFollowups)
        <div class="mb-8 bg-white rounded-lg shadow-md">
            <div class="p-4 bg-gray-50 rounded-t-lg">
                <h3 class="text-lg font-semibold">
                    {{ $grupoFollowups->first()->venda->comprador->nome }}
                </h3>
            </div>

            <div class="p-4">
                @foreach($grupoFollowups as $followup)
                    <div class="mb-4 border-b pb-4 last:border-b-0 last:pb-0">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">
                                Venda #{{ $followup->venda->id }} - {{ $followup->venda->data_venda->format('d/m/Y') }}
                            </span>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            @php
                                $status2dias = $this->getStatusContato($followup, 'contato_2dias');
                                $status2semanas = $this->getStatusContato($followup, 'contato_2semanas');
                                $status2meses = $this->getStatusContato($followup, 'contato_2meses');
                            @endphp

                            <!-- 2 Dias -->
                            <div class="p-3 rounded-lg {{ $status2dias['classe'] }}">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium">2 Dias</span>
                                    <span class="text-sm">{{ $followup->contato_2dias->format('d/m/Y') }}</span>
                                </div>
                                @if(!$followup->contato_2dias_realizado)
                                    <button 
                                        wire:click="marcarContatoRealizado({{ $followup->id }}, 'contato_2dias')"
                                        class="mt-2 w-full px-3 py-1 text-sm bg-white rounded-md shadow-sm hover:bg-gray-50">
                                        Marcar Realizado
                                    </button>
                                @endif
                            </div>

                            <!-- 2 Semanas -->
                            <div class="p-3 rounded-lg {{ $status2semanas['classe'] }}">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium">2 Semanas</span>
                                    <span class="text-sm">{{ $followup->contato_2semanas->format('d/m/Y') }}</span>
                                </div>
                                @if(!$followup->contato_2semanas_realizado)
                                    <button 
                                        wire:click="marcarContatoRealizado({{ $followup->id }}, 'contato_2semanas')"
                                        class="mt-2 w-full px-3 py-1 text-sm bg-white rounded-md shadow-sm hover:bg-gray-50">
                                        Marcar Realizado
                                    </button>
                                @endif
                            </div>

                            <!-- 2 Meses -->
                            <div class="p-3 rounded-lg {{ $status2meses['classe'] }}">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium">2 Meses</span>
                                    <span class="text-sm">{{ $followup->contato_2meses->format('d/m/Y') }}</span>
                                </div>
                                @if(!$followup->contato_2meses_realizado)
                                    <button 
                                        wire:click="marcarContatoRealizado({{ $followup->id }}, 'contato_2meses')"
                                        class="mt-2 w-full px-3 py-1 text-sm bg-white rounded-md shadow-sm hover:bg-gray-50">
                                        Marcar Realizado
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Produtos -->
                        <div class="mt-2 text-sm text-gray-600">
                            <p class="font-medium">Produtos:</p>
                            <ul class="list-disc list-inside">
                                @foreach($followup->venda->itens as $item)
                                    <li>{{ $item->produto->nome }} ({{ $item->quantidade }} un)</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    @if($followups->isEmpty())
        <div class="text-center py-8 text-gray-500">
            Nenhum follow-up pendente encontrado.
        </div>
    @endif
</div>