<div>
    <x-notification />
    {{-- Cabeçalho com Estatísticas --}}
    <div class="flex justify-between items-center mb-6">
        <div class="flex-1">
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-white leading-tight">
                    Clientes
                </h2>
            </x-slot>
            <div class="mt-1 flex space-x-4 text-sm text-gray-600">
                <span>Total: {{ $totalClientes }}</span>
                <span>Ativos: {{ $totalAtivos }}</span>
                <span>Inativos: {{ $totalInativos }}</span>
            </div>
        </div>
        <div>
            <x-primary-button
                type="button"
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'novo-cliente');"
            >
                Novo Cliente
            </x-primary-button>
        </div>
    </div>

    {{-- Filtros e Controles --}}
    <div class="mb-4 flex space-x-4">
        <div class="flex-1">
            <x-input
                wire:model.live.debounce.300ms="search"
                type="search"
                placeholder="Buscar clientes..."
                class="block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm"
            />
        </div>
        <div>
            <x-select wire:model.live="filtroStatus" class="block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm">
                <option value="todos">Todos</option>
                <option value="ativos">Ativos</option>
                <option value="inativos">Inativos</option>
            </x-select>
        </div>
        <div>
            <x-select wire:model.live="perPage" class="block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm">
                <option value="10">10 por página</option>
                <option value="25">25 por página</option>
                <option value="50">50 por página</option>
            </x-select>
        </div>
    </div>

    {{-- Tabela --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow table-container">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($clientes as $cliente)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $cliente->nome }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $cliente->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $cliente->telefone }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $cliente->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $cliente->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <x-secondary-button
                                wire:click="edit({{ $cliente->id }})"
                                x-data=""
                                x-on:click="$dispatch('open-modal', 'editar-cliente')"
                            >
                                Editar
                            </x-secondary-button>
                            <x-danger-button
                                wire:click="delete({{ $cliente->id }})"
                                wire:confirm="Tem certeza que deseja remover este cliente?"
                            >
                                Excluir
                            </x-danger-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Nenhum cliente encontrado
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginação --}}
    <div class="mt-4">
        {{ $clientes->links() }}
    </div>

    {{-- Modais --}}
    <x-modal name="novo-cliente" :show="false">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                Novo Cliente
            </h2>
            <livewire:clientes.cliente-form/>
        </div>
    </x-modal>

    <x-modal name="editar-cliente" :show="false">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                Editar Cliente
            </h2>
            @if($clienteEmEdicao)
                <livewire:clientes.cliente-form
                    :contato="$clienteEmEdicao"
                    :key="'cliente-form-'.$clienteEmEdicao->id"
                />
            @endif
        </div>
    </x-modal>
</div>