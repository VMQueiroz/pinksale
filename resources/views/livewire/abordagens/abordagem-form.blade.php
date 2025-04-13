<div x-data="{ tipo_abordagem: @entangle('tipo_abordagem') }">
    <!-- Indicador de tipo -->
    <div class="mb-4" x-show="tipo_abordagem">
        <div
            x-show="tipo_abordagem === 'cliente'"
            class="bg-blue-50 border-l-4 border-blue-400 p-3 flex items-center"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="text-blue-700 font-medium">Abordagem do tipo Cliente</span>
        </div>
        <div
            x-show="tipo_abordagem === 'inicio'"
            class="bg-green-50 border-l-4 border-green-400 p-3 flex items-center"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span class="text-green-700 font-medium">Abordagem do tipo Início</span>
        </div>
    </div>

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
                    <label class="inline-flex items-center p-2 rounded-md" :class="{ 'bg-blue-50': tipo_abordagem === 'cliente' }">
                        <input
                            type="radio"
                            wire:model.live="tipo_abordagem"
                            value="cliente"
                            class="form-radio border-gray-300 text-blue-600 focus:ring-blue-500"
                        >
                        <span class="ml-2 text-sm font-medium" :class="{ 'text-blue-700': tipo_abordagem === 'cliente', 'text-gray-600': tipo_abordagem !== 'cliente' }">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Cliente
                            </span>
                        </span>
                    </label>
                    <label class="inline-flex items-center p-2 rounded-md" :class="{ 'bg-green-50': tipo_abordagem === 'inicio' }">
                        <input
                            type="radio"
                            wire:model.live="tipo_abordagem"
                            value="inicio"
                            class="form-radio border-gray-300 text-green-600 focus:ring-green-500"
                        >
                        <span class="ml-2 text-sm font-medium" :class="{ 'text-green-700': tipo_abordagem === 'inicio', 'text-gray-600': tipo_abordagem !== 'inicio' }">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Início
                            </span>
                        </span>
                    </label>
                </div>
                <x-input-error :messages="$errors->get('tipo_abordagem')" class="mt-2" />
            </div>

            <!-- Campos de Indicação e Datas -->
            <div class="sm:col-span-6">
                <x-input-label for="indicado_por" value="Indicado por" />
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <x-input wire:model="indicado_por" id="indicado_por" type="text" class="pl-10 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" />
                </div>
                <x-input-error :messages="$errors->get('indicado_por')" class="mt-2" />
            </div>

            <div class="sm:col-span-3">
                <x-input-label for="data_retorno" value="Data de Retorno" />
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <x-input wire:model="data_retorno" id="data_retorno" type="date" class="pl-10 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" />
                </div>
                <x-input-error :messages="$errors->get('data_retorno')" class="mt-2" />
            </div>

            <div class="sm:col-span-3">
                <x-input-label for="ultimo_contato" value="Último Contato" />
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <x-input wire:model="ultimo_contato" id="ultimo_contato" type="date" class="pl-10 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" />
                </div>
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

        <!-- Botões de Ações Especiais -->
        @if($contato && $contato->exists)
            <div class="border-t border-gray-200 pt-4 mb-4">
                <h3 class="text-sm font-medium text-gray-500 mb-3">Ações Especiais</h3>
                <div class="flex flex-wrap gap-2 bg-gray-50 p-3 rounded-lg">
                    <div class="w-full text-xs text-gray-500 mb-2">Selecione uma ação para esta abordagem:</div>
                    @if($tipo_abordagem === 'inicio')
                        <button type="button" wire:click="transferirParaConsultor" class="flex items-center px-4 py-2 bg-green-100 border border-transparent rounded-md font-semibold text-xs text-green-800 uppercase tracking-widest hover:bg-green-200 active:bg-green-300 focus:outline-none focus:border-green-500 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                            Transferir para Consultor
                        </button>

                        <button type="button" wire:click="criarEntrevista" class="flex items-center px-4 py-2 bg-blue-100 border border-transparent rounded-md font-semibold text-xs text-blue-800 uppercase tracking-widest hover:bg-blue-200 active:bg-blue-300 focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Criar Entrevista
                        </button>
                    @endif

                    @if($tipo_abordagem === 'cliente')
                        <button type="button" wire:click="transferirParaCliente" class="flex items-center px-4 py-2 bg-blue-100 border border-transparent rounded-md font-semibold text-xs text-blue-800 uppercase tracking-widest hover:bg-blue-200 active:bg-blue-300 focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                            Transferir para Cliente
                        </button>
                    @endif
                </div>
            </div>
        @endif

        <!-- Botões de Salvar/Cancelar -->
        <div class="flex justify-end space-x-3 border-t border-gray-200 pt-4">
            <x-secondary-button type="button" wire:click="cancel" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Cancelar
            </x-secondary-button>
            <x-primary-button type="submit" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Salvar
            </x-primary-button>
        </div>
    </form>
</div>
