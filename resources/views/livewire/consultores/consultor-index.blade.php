<div>
    <x-notification />
    {{-- Cabeçalho com Estatísticas --}}
    <div class="flex justify-between items-center mb-6">
        <div class="flex-1">
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-white leading-tight">
                    Consultores
                </h2>
            </x-slot>
            <div class="mt-1 flex space-x-4 text-sm text-gray-600">
                <span>Total: {{ $totalConsultores }}</span>
                <span>Inícios: {{ $totalInicios }}</span>
                <span>Normais: {{ $totalNormais }}</span>
            </div>
        </div>
        <div>
            <x-primary-button
                type="button"
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'novo-consultor');"
            >
                Novo Consultor
            </x-primary-button>
        </div>
    </div>

    {{-- Filtros e Controles --}}
    <div class="mb-4 flex space-x-4">
        <div class="flex-1">
            <x-input
                wire:model.live.debounce.300ms="search"
                type="search"
                placeholder="Buscar consultores..."
                class="block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm"
            />
        </div>
        <div>
            <x-select wire:model.live="filtroStatus" class="block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm">
                <option value="todos">Todos</option>
                <option value="inicios">Inícios</option>
                <option value="normais">Normais</option>
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
                    <th wire:click="sortBy('iniciado_por_mim')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                        Iniciado por mim
                        @if ($sortField === 'iniciado_por_mim')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($consultores as $consultor)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $consultor->nome }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $consultor->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $consultor->telefone }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $consultor->iniciado_por_mim ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $consultor->iniciado_por_mim ? 'Sim' : 'Não' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <x-secondary-button
                                wire:click="edit({{ $consultor->id }})"
                                x-data=""
                                x-on:click="$dispatch('open-modal', 'editar-consultor')"
                            >
                                Editar
                            </x-secondary-button>
                            <x-danger-button
                                wire:click="delete({{ $consultor->id }})"
                                wire:confirm="Tem certeza que deseja excluir este consultor?"
                            >
                                Excluir
                            </x-danger-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Nenhum consultor encontrado
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginação --}}
    <div class="mt-4">
        {{ $consultores->links() }}
    </div>

    {{-- Modais --}}
    <x-modal name="novo-consultor" :show="false">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                Novo Consultor
            </h2>
            <livewire:consultores.consultor-form />
        </div>
    </x-modal>

    <x-modal name="editar-consultor" :show="false">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                Editar Consultor
            </h2>
            @if($consultorEmEdicao)
                <livewire:consultores.consultor-form
                    :contato="$consultorEmEdicao"
                    :key="'consultor-form-'.$consultorEmEdicao->id"
                />
            @endif
        </div>
    </x-modal>
</div>