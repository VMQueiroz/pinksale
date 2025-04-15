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
            <div class="mt-1 flex flex-wrap gap-4 text-sm">
                <div class="bg-gray-100 px-3 py-1 rounded-full">
                    <span class="font-medium">Abordagens:</span> {{ $totalAbordagens }}
                </div>
                <div class="bg-green-100 px-3 py-1 rounded-full">
                    <span class="font-medium">Inícios:</span> {{ $totalInicios }}
                </div>
                <div class="bg-blue-100 px-3 py-1 rounded-full">
                    <span class="font-medium">Clientes:</span> {{ $totalClientes }}
                </div>
                <div class="bg-purple-100 px-3 py-1 rounded-full">
                    <span class="font-medium">Convertidos:</span> {{ $totalConvertidos }}
                    <span class="text-xs">({{ $totalConvertidosConsultor }} consultores, {{ $totalConvertidosCliente }} clientes)</span>
                </div>
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
                <option value="todos">Todos os tipos</option>
                <option value="inicio">Inícios</option>
                <option value="cliente">Clientes</option>
            </x-select>
        </div>
        <div>
            <x-select wire:model.live="filtroStatus" class="block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm">
                <option value="ativos">Apenas ativos</option>
                <option value="convertidos">Convertidos</option>
                <option value="todos">Todos os status</option>
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
    <div class="bg-white overflow-x-auto shadow-xl sm:rounded-lg table-container">
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
                                class="mr-1"
                            >
                                Editar
                            </x-secondary-button>

                            @if($abordagem->tipo_abordagem === 'inicio')
                                <x-secondary-button
                                    wire:click="abrirModalEntrevista({{ $abordagem->id }})"
                                    x-data=""
                                    x-on:click="$dispatch('open-modal', 'modal-entrevista')"
                                    class="mr-1 bg-blue-100 hover:bg-blue-200 text-blue-800 border-blue-300 flex items-center"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Entrevista
                                </x-secondary-button>
                            @endif

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

    <x-modal name="modal-entrevista" :show="false" max-width="2xl">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Agendar Entrevista
            </h2>
            @if($abordagemParaEntrevista)
                <div class="mb-4 bg-blue-50 p-3 rounded-md">
                    <p class="text-sm text-blue-800">
                        Agendando entrevista para <strong>{{ $abordagemParaEntrevista->nome }}</strong>
                    </p>
                </div>
                <livewire:agenda.entrevista-form
                    :abordagem="$abordagemParaEntrevista"
                    :key="'entrevista-form-'.$abordagemParaEntrevista->id"
                />
            @endif
        </div>
    </x-modal>
</div>
