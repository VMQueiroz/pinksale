<div>
    <x-ui.card>
        <x-slot:title>Vendas</x-slot:title>
        
        <x-slot:actions>
            <div class="flex items-center gap-4">
                <!-- Busca -->
                <div class="w-64">
                    <x-ui.input 
                        wire:model.live.debounce.300ms="search"
                        type="search"
                        placeholder="Buscar vendas..."
                    />
                </div>

                <!-- Filtro de Status -->
                <x-ui.select 
                    wire:model.live="status"
                    class="w-40"
                >
                    <option value="">Todos Status</option>
                    <option value="pending">Pendente</option>
                    <option value="completed">Concluída</option>
                    <option value="cancelled">Cancelada</option>
                </x-ui.select>

                <!-- De: -->
                <x-ui.card>
                    <x-slot:title>Vendas</x-slot:title>
                    <x-slot:actions>
                        <x-ui.button 
                            wire:click="$dispatch('open-modal', 'create-venda')" 
                            variant="primary" 
                            size="sm"
                        >
                            Nova Venda
                        </x-ui.button>
                    </x-slot:actions>
                </x-ui.card>

                <!-- Para: -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900">Vendas</h2>
                    <x-ui.button 
                        wire:click="$dispatch('open-modal', 'create-venda')" 
                        variant="primary" 
                        size="sm"
                    >
                        Nova Venda
                    </x-ui.button>
                </div>
            </div>
        </x-slot:actions>

        <!-- Ações em Lote -->
        @if(count($selectedItems) > 0)
            <div class="bg-gray-50 px-4 py-2 mb-4 rounded-lg flex items-center justify-between">
                <span class="text-sm text-gray-700">
                    {{ count($selectedItems) }} {{ count($selectedItems) === 1 ? 'item selecionado' : 'itens selecionados' }}
                </span>
                <x-ui.button 
                    wire:click="deleteSelected"
                    variant="danger" 
                    size="sm"
                >
                    Excluir Selecionados
                </x-ui.button>
            </div>
        @endif

        @if($vendas->isEmpty())
            <x-ui.alert type="info">
                Nenhuma venda encontrada.
            </x-ui.alert>
        @else
            <x-ui.table :headers="[
                ['label' => '', 'sortable' => false],
                ['label' => 'Código', 'sortable' => true, 'field' => 'codigo'],
                ['label' => 'Cliente', 'sortable' => true, 'field' => 'cliente.nome'],
                ['label' => 'Valor', 'align' => 'right', 'sortable' => true, 'field' => 'valor_total'],
                ['label' => 'Status', 'sortable' => true, 'field' => 'status'],
                ['label' => 'Data', 'sortable' => true, 'field' => 'created_at'],
                ['label' => 'Ações', 'align' => 'right'],
            ]">
                @foreach($vendas as $venda)
                    <tr wire:key="venda-{{ $venda->id }}">
                        <x-ui.table.cell>
                            <input 
                                type="checkbox" 
                                wire:model.live="selectedItems" 
                                value="{{ $venda->id }}"
                                class="rounded border-gray-300"
                            >
                        </x-ui.table.cell>
                        <x-ui.table.cell>{{ $venda->codigo }}</x-ui.table.cell>
                        <x-ui.table.cell>{{ $venda->cliente->nome }}</x-ui.table.cell>
                        <x-ui.table.cell align="right">
                            R$ {{ number_format($venda->valor_total, 2, ',', '.') }}
                        </x-ui.table.cell>
                        <x-ui.table.cell>
                            <x-ui.badge :variant="$venda->status->color()">
                                {{ $venda->status->label() }}
                            </x-ui.badge>
                        </x-ui.table.cell>
                        <x-ui.table.cell>
                            {{ $venda->created_at->format('d/m/Y H:i') }}
                        </x-ui.table.cell>
                        <x-ui.table.cell align="right">
                            <div class="flex items-center justify-end gap-2">
                                <x-ui.button 
                                    wire:click="edit({{ $venda->id }})"
                                    variant="secondary" 
                                    size="sm"
                                >
                                    Editar
                                </x-ui.button>
                                <x-ui.button 
                                    wire:click="delete({{ $venda->id }})"
                                    variant="danger" 
                                    size="sm"
                                >
                                    Excluir
                                </x-ui.button>
                            </div>
                        </x-ui.table.cell>
                    </tr>
                @endforeach
            </x-ui.table>

            <div class="mt-4">
                {{ $vendas->links() }}
            </div>
        @endif
    </x-ui.card>

    <x-modal name="create-venda" :show="$errors->isNotEmpty()">
        <livewire:vendas.venda-form />
    </x-modal>
</div>




