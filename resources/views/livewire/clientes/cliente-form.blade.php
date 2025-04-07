<div class="p-6">
    <form wire:submit.prevent="save" class="space-y-6">
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <!-- Dados Básicos -->
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

            <!-- Endereço -->
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
                <x-input 
                    wire:model="endereco" 
                    id="endereco" 
                    type="text" 
                    class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" 
                />
                <x-input-error :messages="$errors->get('endereco')" class="mt-2" />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="numero" value="Número" />
                <x-input 
                    wire:model="numero" 
                    id="numero" 
                    type="text" 
                    class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" 
                />
                <x-input-error :messages="$errors->get('numero')" class="mt-2" />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="complemento" value="Complemento" />
                <x-input 
                    wire:model="complemento" 
                    id="complemento" 
                    type="text" 
                    class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm"
                />
                <x-input-error :messages="$errors->get('complemento')" class="mt-2" />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="estado" value="Estado" />
                <x-input 
                    wire:model="estado" 
                    id="estado" 
                    type="text" 
                    class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" 
                    required 
                    maxlength="2"
                />
                <x-input-error :messages="$errors->get('estado')" class="mt-2" />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="cidade" value="Cidade" />
                <x-input 
                    wire:model="cidade" 
                    id="cidade" 
                    type="text" 
                    class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" 
                />
                <x-input-error :messages="$errors->get('cidade')" class="mt-2" />
            </div>

            <!-- Aniversário -->
            <div class="sm:col-span-2">
                <x-input-label for="dia_aniversario" value="Dia do Aniversário" />
                <x-input 
                    wire:model="dia_aniversario" 
                    id="dia_aniversario" 
                    type="text"
                    x-mask="99"
                    maxlength="2"
                    placeholder="DD"
                    class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm"
                />
                <x-input-error :messages="$errors->get('dia_aniversario')" class="mt-2" />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="mes_aniversario" value="Mês do Aniversário" />
                <x-input 
                    wire:model="mes_aniversario" 
                    id="mes_aniversario" 
                    type="text"
                    x-mask="99"
                    maxlength="2"
                    placeholder="MM"
                    class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm"
                />
                <x-input-error :messages="$errors->get('mes_aniversario')" class="mt-2" />
            </div>

            <!-- Campos específicos -->
            <div class="sm:col-span-3">
                <x-input-label for="tipo_de_pele" value="Tipo de Pele" />
                <select 
                    wire:model="tipo_de_pele" 
                    id="tipo_de_pele" 
                    class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm"
                >
                    <option value="">Selecione...</option>
                    <option value="normal">Normal</option>
                    <option value="seca">Seca</option>
                    <option value="oleosa">Oleosa</option>
                    <option value="mista">Mista</option>
                    <option value="sensivel">Sensível</option>
                </select>
                <x-input-error :messages="$errors->get('tipo_de_pele')" class="mt-2" />
            </div>

            <div class="sm:col-span-3">
                <x-input-label for="tom_de_pele" value="Tom de Pele" />
                <select 
                    wire:model="tom_de_pele" 
                    id="tom_de_pele" 
                    class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm"
                >
                    <option value="">Selecione...</option>
                    <option value="muito_clara">Muito Clara</option>
                    <option value="clara">Clara</option>
                    <option value="media">Média</option>
                    <option value="morena">Morena</option>
                    <option value="negra">Negra</option>
                </select>
                <x-input-error :messages="$errors->get('tom_de_pele')" class="mt-2" />
            </div>

            <!-- Observações -->
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

            <!-- Flags -->
            <div class="sm:col-span-6">
                <div class="space-y-4">
                    <label class="inline-flex items-center">
                        <input 
                            type="checkbox" 
                            wire:model="habilitado_fidelidade" 
                            class="rounded border-gray-300 text-pk focus:ring-pk shadow-sm"
                        >
                        <span class="ml-2 text-sm text-gray-600">Habilitado para Programa de Fidelidade</span>
                    </label>
                    
                    <label class="inline-flex items-center">
                        <input 
                            type="checkbox" 
                            wire:model="ativo" 
                            class="rounded border-gray-300 text-pk focus:ring-pk shadow-sm"
                        >
                        <span class="ml-2 text-sm text-gray-600">Ativo</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-3 mt-6">
            <x-secondary-button 
                type="button" 
                x-on:click="$dispatch('close'); $wire.resetForm()"
            >
                Cancelar
            </x-secondary-button>

            <x-primary-button type="submit">
                {{ $contato && $contato->exists ? 'Atualizar' : 'Criar' }}
            </x-primary-button>
        </div>
    </form>
</div>








