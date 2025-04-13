<div>
    <x-notification />
    {{-- Cabeçalho com Estatísticas --}}
    <div class="flex justify-between items-center mb-6">
        <div class="flex-1">
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-white leading-tight">
                    Abordagens
                </h2>
            </x-slot>
            <div class="mt-1 flex space-x-4 text-sm text-gray-600">
                <span>Total: {{ $totalAbordagens }}</span>
                <span>Inícios: {{ $totalInicios }}</span>
                <span>Clientes: {{ $totalClientes }}</span>
            </div>
        </div>
        <div>
            <x-primary-button
                type="button"
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'nova-abordagem');"
            >
                Nova Abordagem
            </x-primary-button>
        </div>
    </div>

    {{-- Filtros e Controles --}}
    <div class="mb-4 flex space-x-4">
        <div class="flex-1">
            <x-input
                wire:model.live.debounce.300ms="search"
                type="search"
                placeholder="Buscar abordagens..."
                class="block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm"
            />
        </div>
        <div>
            <x-select wire:model.live="filtroTipo" class="block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm">
                <option value="todos">Todos</option>
                <option value="inicio">Inícios</option>
                <option value="cliente">Clientes</option>
            </x-select>
        </div>
        <div>
            <x-select wire:model.live="perPage" class="block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm">
                <option value="10">10 por página</option>
                <option value="25">25 por página</option>
                <option value="50">50 por página</option>
                <option value="100">100 por página</option>
            </x-select>
        </div>
    </div>

    {{-- Tabela de Abordagens --}}
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nome
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        E-mail
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Telefone
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tipo
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Data Retorno
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($abordagens as $abordagem)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $abordagem->nome }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $abordagem->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $abordagem->telefone }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $abordagem->tipo_abordagem === 'inicio' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $abordagem->tipo_abordagem === 'inicio' ? 'Início' : 'Cliente' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $abordagem->data_retorno ? $abordagem->data_retorno->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <x-secondary-button
                                wire:click="edit({{ $abordagem->id }})"
                                x-data=""
                                x-on:click="$dispatch('open-modal', 'editar-abordagem')"
                            >
                                Editar
                            </x-secondary-button>
                            <x-danger-button
                                wire:click="delete({{ $abordagem->id }})"
                                wire:confirm="Tem certeza que deseja excluir esta abordagem?"
                            >
                                Excluir
                            </x-danger-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Nenhuma abordagem encontrada
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginação --}}
    <div class="mt-4">
        {{ $abordagens->links() }}
    </div>

    {{-- Modais --}}
    <x-modal name="nova-abordagem" :show="false">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                Nova Abordagem
            </h2>
            <livewire:abordagens.abordagem-form />
        </div>
    </x-modal>

    <x-modal name="editar-abordagem" :show="false">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                Editar Abordagem
            </h2>
            @if($abordagemEmEdicao)
                <livewire:abordagens.abordagem-form
                    :contato="$abordagemEmEdicao"
                    :key="'abordagem-form-'.$abordagemEmEdicao->id"
                />
            @endif
        </div>
    </x-modal>
</div>
