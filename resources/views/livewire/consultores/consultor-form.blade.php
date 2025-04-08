<div>
    <form wire:submit="save" class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-6 gap-4">
            <!-- Campos básicos -->
            <div class="sm:col-span-4">
                <x-input-label for="nome" value="Nome" />
                <x-input wire:model="nome" id="nome" type="text" class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" required />
                <x-input-error :messages="$errors->get('nome')" class="mt-2" />
            </div>

            <div class="sm:col-span-3">
                <x-input-label for="email" value="E-mail" />
                <x-input wire:model="email" id="email" type="email" class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" />
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

            <!-- Flags -->
            <div class="sm:col-span-6">
                <div class="space-y-4">
                    <label class="inline-flex items-center">
                        <input 
                            type="checkbox" 
                            wire:model.live="iniciado_por_mim" 
                            class="rounded border-gray-300 text-pk focus:ring-pk shadow-sm"
                        >
                        <span class="ml-2 text-sm text-gray-600">Iniciado por mim</span>
                    </label>
                </div>
            </div>

            <!-- Campos adicionais -->
            @if($iniciado_por_mim)
                <div class="sm:col-span-3">
                    <x-input-label for="data_inicio" value="Data de Início" />
                    <x-input 
                        wire:model="data_inicio" 
                        id="data_inicio" 
                        type="date" 
                        class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm"
                        required
                    />
                    <x-input-error :messages="$errors->get('data_inicio')" class="mt-2" />
                </div>
            @endif

            <div class="sm:col-span-6">
                <x-input-label for="observacoes" value="Observações" />
                <textarea 
                    wire:model="observacoes" 
                    id="observacoes" 
                    class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" 
                    rows="3"
                ></textarea>
                <x-input-error :messages="$errors->get('observacoes')" class="mt-2" />
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-x-4">
            <x-secondary-button x-on:click="$dispatch('close')">
                Cancelar
            </x-secondary-button>
            <x-primary-button type="submit">
                {{ $contato && $contato->exists ? 'Atualizar' : 'Criar' }}
            </x-primary-button>
        </div>
    </form>
</div>



