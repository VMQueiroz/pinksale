<div>
    <form wire:submit.prevent="save" class="space-y-6">
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
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
                <x-input wire:model="telefone" x-mask="(99) 99999-9999" id="telefone" type="text" class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" required />
                <x-input-error :messages="$errors->get('telefone')" class="mt-2" />
            </div>

            <!-- Tipo de Abordagem -->
            <div class="sm:col-span-6">
                <x-input-label for="tipo_abordagem" value="Tipo de Abordagem" />
                <div class="mt-2 space-x-4">
                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            wire:model="tipo_abordagem"
                            value="cliente"
                            class="form-radio border-gray-300 text-pk focus:ring-pk"
                        >
                        <span class="ml-2 text-sm text-gray-600">Cliente</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            wire:model="tipo_abordagem"
                            value="inicio"
                            class="form-radio border-gray-300 text-pk focus:ring-pk"
                        >
                        <span class="ml-2 text-sm text-gray-600">Início</span>
                    </label>
                </div>
                <x-input-error :messages="$errors->get('tipo_abordagem')" class="mt-2" />
            </div>

            <!-- Campos de Indicação e Datas -->
            <div class="sm:col-span-6">
                <x-input-label for="indicado_por" value="Indicado por" />
                <x-input wire:model="indicado_por" id="indicado_por" type="text" class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" />
                <x-input-error :messages="$errors->get('indicado_por')" class="mt-2" />
            </div>

            <div class="sm:col-span-3">
                <x-input-label for="data_retorno" value="Data de Retorno" />
                <x-input wire:model="data_retorno" id="data_retorno" type="date" class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" />
                <x-input-error :messages="$errors->get('data_retorno')" class="mt-2" />
            </div>

            <div class="sm:col-span-3">
                <x-input-label for="ultimo_contato" value="Último Contato" />
                <x-input wire:model="ultimo_contato" id="ultimo_contato" type="date" class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" />
                <x-input-error :messages="$errors->get('ultimo_contato')" class="mt-2" />
            </div>

            <!-- Campos de Endereço -->
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
                <x-input wire:model="endereco" id="endereco" type="text" class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" />
                <x-input-error :messages="$errors->get('endereco')" class="mt-2" />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="numero" value="Número" />
                <x-input wire:model="numero" id="numero" type="text" class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" />
                <x-input-error :messages="$errors->get('numero')" class="mt-2" />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="complemento" value="Complemento" />
                <x-input wire:model="complemento" id="complemento" type="text" class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" />
                <x-input-error :messages="$errors->get('complemento')" class="mt-2" />
            </div>

            <div class="sm:col-span-3">
                <x-input-label for="cidade" value="Cidade" />
                <x-input wire:model="cidade" id="cidade" type="text" class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" />
                <x-input-error :messages="$errors->get('cidade')" class="mt-2" />
            </div>

            <div class="sm:col-span-1">
                <x-input-label for="estado" value="UF" />
                <x-input
                    wire:model="estado"
                    id="estado"
                    type="text"
                    class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm"
                    maxlength="2"
                />
                <x-input-error :messages="$errors->get('estado')" class="mt-2" />
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
        </div>

        <!-- Botões de Ação -->
        <div class="flex justify-between">
            <div>
                @if($contato && $contato->exists)
                    @if($tipo_abordagem === 'inicio')
                        <x-secondary-button type="button" wire:click="transferirParaConsultor" class="mr-2">
                            Transferir para Consultor
                        </x-secondary-button>
                    @endif

                    @if($tipo_abordagem === 'cliente')
                        <x-secondary-button type="button" wire:click="transferirParaCliente" class="mr-2">
                            Transferir para Cliente
                        </x-secondary-button>
                    @endif

                    @if($tipo_abordagem === 'inicio')
                        <x-secondary-button type="button" wire:click="criarEntrevista" class="mr-2">
                            Criar Entrevista
                        </x-secondary-button>
                    @endif
                @endif
            </div>

            <div>
                <x-secondary-button type="button" wire:click="cancel" class="mr-2">
                    Cancelar
                </x-secondary-button>
                <x-primary-button type="submit">
                    Salvar
                </x-primary-button>
            </div>
        </div>
    </form>
</div>
