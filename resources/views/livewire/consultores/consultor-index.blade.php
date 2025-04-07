<div>
    {{-- Cabeçalho --}}
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <div class="max-w-xl">
                <x-text-input 
                    wire:model.live.debounce.300ms="search"
                    type="search"
                    placeholder="Buscar consultores..."
                    class="w-full"
                />
            </div>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <x-primary-button
                type="button"
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'novo-consultor')"
            >
                Novo Consultor
            </x-primary-button>
        </div>
    </div>

    {{-- Filtros e Controles --}}
    <div class="mt-4 flex space-x-4">
        <div>
            <x-select wire:model.live="filtroStatus" class="block w-full">
                <option value="todos">Todos os Status</option>
                @foreach($status_list as $valor => $label)
                    <option value="{{ $valor }}">{{ $label }}</option>
                @endforeach
            </x-select>
        </div>
        <div>
            <x-select wire:model.live="filtroNivel" class="block w-full">
                <option value="todos">Todos os Níveis</option>
                @foreach($niveis as $valor => $label)
                    <option value="{{ $valor }}">{{ $label }}</option>
                @endforeach
            </x-select>
        </div>
        <div>
            <x-select wire:model.live="perPage" class="block w-full">
                <option value="10">10 por página</option>
                <option value="25">25 por página</option>
                <option value="50">50 por página</option>
            </x-select>
        </div>
    </div>

    {{-- Tabela --}}
    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead>
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Nome</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nível</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Contato</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">Ações</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($consultores as $consultor)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                                    {{ $consultor->nome }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $niveis[$consultor->nivel] }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $status_list[$consultor->status] }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $consultor->email }}<br>
                                    {{ $consultor->telefone }}
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium">
                                    <x-secondary-button wire:click="edit({{ $consultor->id }})">
                                        Editar
                                    </x-secondary-button>
                                    <x-danger-button wire:click="confirmDelete({{ $consultor->id }})">
                                        Excluir
                                    </x-danger-button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Paginação --}}
    <div class="mt-4">
        {{ $consultores->links() }}
    </div>

    {{-- Modais --}}
    <x-modal name="novo-consultor" focusable>
        <livewire:consultores.consultor-form />
    </x-modal>

    <x-modal name="editar-consultor" focusable>
        @if($consultorEmEdicao)
            <livewire:consultores.consultor-form :contato="$consultorEmEdicao" />
        @endif
    </x-modal>

    <x-modal name="confirmar-exclusao" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Confirmar Exclusão
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Tem certeza que deseja excluir este consultor?
            </p>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancelar
                </x-secondary-button>
                <x-danger-button class="ml-3" wire:click="deleteConsultor">
                    Excluir
                </x-danger-button>
            </div>
        </div>
    </x-modal>
</div>

