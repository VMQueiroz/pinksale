<div class="p-6">
    {{-- Cabeçalho --}}
    <div class="flex justify-between items-center mb-6">
        <div class="flex-1">
            <h2 class="text-2xl font-semibold text-gray-900">Produtos</h2>
        </div>
        <div>
            <x-primary-button
                type="button"
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'novo-produto')"
            >
                Novo Produto
            </x-primary-button>
        </div>
    </div>

    {{-- Filtros e Controles --}}
    <div class="mb-4 flex space-x-4">
        <div class="flex-1">
            <x-text-input
                wire:model.live.debounce.300ms="search"
                type="search"
                placeholder="Buscar produtos..."
                class="w-full"
            />
        </div>
        <div>
            <x-select wire:model.live="filtroStatus">
                <option value="todos">Todos</option>
                <option value="ativos">Ativos</option>
                <option value="inativos">Inativos</option>
            </x-select>
        </div>
        <div>
            <x-select wire:model.live="perPage">
                <option value="10">10 por página</option>
                <option value="25">25 por página</option>
                <option value="50">50 por página</option>
            </x-select>
        </div>
    </div>

    {{-- Tabela de Produtos --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow table-container">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('codigo')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                        Código
                        @if($sortField === 'codigo')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('nome')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                        Nome
                        @if($sortField === 'nome')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('marca')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                        Marca
                        @if($sortField === 'marca')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('preco')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                        Preço
                        @if($sortField === 'preco')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($produtos as $produto)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $produto->codigo }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $produto->nome }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $produto->marca }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            R$ {{ number_format($produto->preco, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $produto->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $produto->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <x-secondary-button wire:click="$dispatch('open-modal', 'editar-produto-{{ $produto->id }}')">
                                Editar
                            </x-secondary-button>
                            <x-danger-button
                                wire:click="delete({{ $produto->id }})"
                                wire:confirm="Tem certeza que deseja remover este produto?"
                            >
                                Excluir
                            </x-danger-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Nenhum produto encontrado
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginação --}}
    <div class="mt-4">
        {{ $produtos->links() }}
    </div>
</div>