<div>
    <x-notification />
    {{-- Cabeçalho com Estatísticas --}}
    <div class="flex justify-between items-center mb-6">
        <div class="flex-1">
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-white leading-tight">
                    Parceiros
                </h2>
            </x-slot>
        </div>
    </div>

    {{-- Filtros e Controles --}}
    <div class="mb-4 flex space-x-4">
        <div class="flex-1">
            <x-input
                wire:model.live.debounce.300ms="search"
                type="search"
                placeholder="Buscar parceiros..."
                class="block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm"
            />
        </div>
        <div>
            <x-select wire:model.live="perPage" class="block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm">
                <option value="10">10 por página</option>
                <option value="25">25 por página</option>
                <option value="50">50 por página</option>
            </x-select>
        </div>
        <div>
            <x-primary-button
                type="button"
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'novo-parceiro');"
            >
                Novo Parceiro
            </x-primary-button>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('nome')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                        Nome
                        @if ($sortField === 'nome')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('nome_contato')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                        Nome do Contato
                        @if ($sortField === 'nome_contato')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('email')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                        Email
                        @if ($sortField === 'email')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('telefone')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                        Telefone
                        @if ($sortField === 'telefone')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($parceiros as $parceiro)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $parceiro->nome }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $parceiro->nome_contato }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $parceiro->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $parceiro->telefone }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <x-secondary-button
                                wire:click="edit({{ $parceiro->id }})"
                                x-data=""
                                x-on:click="$dispatch('open-modal', 'editar-parceiro')"
                            >
                                Editar
                            </x-secondary-button>
                            <x-danger-button
                                wire:click="delete({{ $parceiro->id }})"
                                wire:confirm="Tem certeza que deseja remover este parceiro?"
                            >
                                Excluir
                            </x-danger-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Nenhum parceiro encontrado
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $parceiros->links() }}
    </div>

    <x-modal name="novo-parceiro" :show="false">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                Novo Parceiro
            </h2>
            <livewire:parceiros.parceiro-form/>
        </div>
    </x-modal>

    <x-modal name="editar-parceiro" :show="false">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                Editar Parceiro
            </h2>
            @if($parceiroEmEdicao)
                <livewire:parceiros.parceiro-form
                    :contato="$parceiroEmEdicao"
                    :key="'edit-'.$parceiroEmEdicao->id"
                />
            @endif
        </div>
    </x-modal>
</div>