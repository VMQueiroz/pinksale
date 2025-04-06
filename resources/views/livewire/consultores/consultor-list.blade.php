<div>
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

    <div class="mt-4">
        <div class="flex space-x-2 overflow-x-auto">
            <select wire:model.live="nivel" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">Todos os níveis</option>
                @foreach($niveis as $valor => $label)
                    <option value="{{ $valor }}">{{ $label }}</option>
                @endforeach
            </select>

            <select wire:model.live="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">Todos os status</option>
                @foreach($status_list as $valor => $label)
                    <option value="{{ $valor }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mt-6 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Nome</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nível</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Meta Mensal</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Ações</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($consultores as $consultor)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                        {{ $consultor->nome }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ $niveis[$consultor->nivel] }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ $status_list[$consultor->status] }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        R$ {{ number_format($consultor->meta_mensal, 2, ',', '.') }}
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <x-secondary-button
                                            type="button"
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'editar-consultor-{{ $consultor->id }}')"
                                        >
                                            Editar
                                        </x-secondary-button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $consultores->links() }}
    </div>
</div>