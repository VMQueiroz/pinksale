<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Novo Produto</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Preencha as informações para criar um novo produto.
                </p>
            </div>
        </div>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <form wire:submit="save">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <x-input-label for="codigo" value="Código" />
                                <x-text-input wire:model="codigo" type="text" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('codigo')" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <x-input-label for="nome" value="Nome" />
                                <x-text-input wire:model="nome" type="text" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('nome')" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <x-input-label for="marca" value="Marca" />
                                <x-text-input wire:model="marca" type="text" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('marca')" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <x-input-label for="preco" value="Preço" />
                                <x-text-input wire:model="preco" type="number" step="0.01" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('preco')" class="mt-2" />
                            </div>

                            <div class="col-span-6">
                                <x-input-label for="descricao" value="Descrição" />
                                <x-textarea wire:model="descricao" class="mt-1 block w-full" rows="3" />
                                <x-input-error :messages="$errors->get('descricao')" class="mt-2" />
                            </div>

                            <div class="col-span-6">
                                <x-input-label for="imagem" value="Imagem" />
                                <input wire:model="imagem" type="file" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('imagem')" class="mt-2" />
                            </div>

                            <div class="col-span-6">
                                <label class="inline-flex items-center">
                                    <input wire:model="ativo" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-600">Ativo</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <x-secondary-button type="button" wire:click="$dispatch('close-modal')">
                            Cancelar
                        </x-secondary-button>
                        <x-primary-button type="submit">
                            Salvar
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>