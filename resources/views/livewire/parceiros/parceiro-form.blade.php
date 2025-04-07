<div>
    <form wire:submit="save">
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <x-input-label for="nome" value="Nome" />
                <x-input 
                    wire:model="nome" 
                    id="nome" 
                    type="text" 
                    class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" 
                    required 
                />
                <x-input-error :messages="$errors->get('nome')" class="mt-2" />
            </div>

            <div class="sm:col-span-3">
                <x-input-label for="nome_contato" value="Nome do Contato" />
                <x-input 
                    wire:model="nome_contato" 
                    id="nome_contato" 
                    type="text" 
                    class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" 
                />
                <x-input-error :messages="$errors->get('nome_contato')" class="mt-2" />
            </div>

            <div class="sm:col-span-3">
                <x-input-label for="email" value="E-mail" />
                <x-input 
                    wire:model="email" 
                    id="email" 
                    type="email" 
                    class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" 
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="sm:col-span-3">
                <x-input-label for="telefone" value="Telefone" />
                <x-input 
                    wire:model="telefone" 
                    x-mask="(99) 99999-9999"
                    id="telefone" 
                    type="text" 
                    class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" 
                    required 
                />
                <x-input-error :messages="$errors->get('telefone')" class="mt-2" />
            </div>

            <!-- Campos de endereço -->
            <div class="sm:col-span-2">
                <x-input-label for="cep" value="CEP" />
                <x-input 
                    wire:model.blur="cep" 
                    wire:change="buscarCep"
                    x-mask="99999-999"
                    id="cep" 
                    type="text" 
                    class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" 
                />
                <x-input-error :messages="$errors->get('cep')" class="mt-2" />
            </div>

            <div class="sm:col-span-4">
                <x-input-label for="endereco" value="Endereço" />
                <x-input wire:model="endereco" id="endereco" type="text" class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm"/>
                <x-input-error :messages="$errors->get('endereco')" class="mt-2" />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="numero" value="Número" />
                <x-input wire:model="numero" id="numero" type="text" class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm"/>
                <x-input-error :messages="$errors->get('numero')" class="mt-2" />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="complemento" value="Complemento" />
                <x-input wire:model="complemento" id="complemento" type="text" class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" />
                <x-input-error :messages="$errors->get('complemento')" class="mt-2" />
            </div>

            <div class="sm:col-span-3">
                <x-input-label for="cidade" value="Cidade" />
                <x-input wire:model="cidade" id="cidade" type="text" class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm"/>
                <x-input-error :messages="$errors->get('cidade')" class="mt-2" />
            </div>

            <div class="sm:col-span-1">
                <x-input-label for="estado" value="UF" />
                <x-input wire:model="estado" id="estado" type="text" class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm"/>
                <x-input-error :messages="$errors->get('estado')" class="mt-2" />
            </div>

            <div class="sm:col-span-6">
                <x-input-label for="observacoes" value="Observações" />
                <x-textarea 
                    wire:model="observacoes" 
                    id="observacoes" 
                    class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" 
                />
                <x-input-error :messages="$errors->get('observacoes')" class="mt-2" />
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-x-4">
            <x-secondary-button x-on:click="$dispatch('close')">
                Cancelar
            </x-secondary-button>
            <x-primary-button type="submit">
                Salvar
            </x-primary-button>
        </div>
    </form>
</div>