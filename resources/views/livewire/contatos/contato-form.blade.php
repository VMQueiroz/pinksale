<div class="space-y-6">
    <form wire:submit="save" class="space-y-6">
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
            <div class="px-4 py-6 sm:p-8">
                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <!-- Dados Básicos -->
                    <div class="sm:col-span-4">
                        <x-input-label for="nome" value="Nome" />
                        <x-text-input wire:model="nome" id="nome" type="text" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('nome')" />
                    </div>

                    <div class="sm:col-span-4">
                        <x-input-label for="email" value="E-mail" />
                        <x-text-input wire:model="email" id="email" type="email" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div class="sm:col-span-2">
                        <x-input-label for="telefone" value="Telefone" />
                        <x-text-input wire:model="telefone" id="telefone" type="text" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('telefone')" />
                    </div>

                    <!-- Endereço -->
                    <div class="sm:col-span-2">
                        <x-input-label for="cep" value="CEP" />
                        <x-text-input wire:model="cep" id="cep" type="text" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('cep')" />
                    </div>

                    <div class="sm:col-span-4">
                        <x-input-label for="endereco" value="Endereço" />
                        <x-text-input wire:model="endereco" id="endereco" type="text" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('endereco')" />
                    </div>

                    <div class="sm:col-span-2">
                        <x-input-label for="numero" value="Número" />
                        <x-text-input wire:model="numero" id="numero" type="text" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('numero')" />
                    </div>

                    <div class="sm:col-span-2">
                        <x-input-label for="complemento" value="Complemento" />
                        <x-text-input wire:model="complemento" id="complemento" type="text" class="mt-1 block w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('complemento')" />
                    </div>

                    <div class="sm:col-span-2">
                        <x-input-label for="estado" value="Estado" />
                        <x-text-input wire:model="estado" id="estado" type="text" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('estado')" />
                    </div>

                    <div class="sm:col-span-2">
                        <x-input-label for="cidade" value="Cidade" />
                        <x-text-input wire:model="cidade" id="cidade" type="text" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('cidade')" />
                    </div>

                    <!-- Papéis -->
                    <div class="sm:col-span-6">
                        <x-input-label value="Papéis" />
                        <div class="mt-2 space-y-2">
                            @foreach($papeis_disponiveis as $valor => $label)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" wire:model="papeis" value="{{ $valor }}" class="rounded border-gray-300">
                                    <span class="ml-2">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('papeis')" />
                    </div>

                    <!-- Campos específicos baseados nos papéis selecionados -->
                    @if(in_array('parceiro', $papeis))
                        <div class="sm:col-span-4">
                            <x-input-label for="nome_contato" value="Nome do Contato" />
                            <x-text-input wire:model="nome_contato" id="nome_contato" type="text" class="mt-1 block w-full" />
                            <x-input-error class="mt-2" :messages="$errors->get('nome_contato')" />
                        </div>
                    @endif

                    @if(in_array('cliente', $papeis))
                        <div class="sm:col-span-3">
                            <x-input-label for="tipo_de_pele" value="Tipo de Pele" />
                            <x-select wire:model="tipo_de_pele" id="tipo_de_pele" class="mt-1 block w-full">
                                <option value="">Selecione...</option>
                                @foreach($tipos_pele as $valor => $label)
                                    <option value="{{ $valor }}">{{ $label }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error class="mt-2" :messages="$errors->get('tipo_de_pele')" />
                        </div>

                        <div class="sm:col-span-3">
                            <x-input-label for="tom_de_pele" value="Tom de Pele" />
                            <x-select wire:model="tom_de_pele" id="tom_de_pele" class="mt-1 block w-full">
                                <option value="">Selecione...</option>
                                @foreach($tons_pele as $valor => $label)
                                    <option value="{{ $valor }}">{{ $label }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error class="mt-2" :messages="$errors->get('tom_de_pele')" />
                        </div>
                    @endif

                    <!-- Aniversário -->
                    <div class="sm:col-span-3">
                        <x-input-label for="dia_aniversario" value="Dia do Aniversário" />
                        <x-text-input wire:model="dia_aniversario" id="dia_aniversario" type="number" min="1" max="31" class="mt-1 block w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('dia_aniversario')" />
                    </div>

                    <div class="sm:col-span-3">
                        <x-input-label for="mes_aniversario" value="Mês do Aniversário" />
                        <x-text-input wire:model="mes_aniversario" id="mes_aniversario" type="number" min="1" max="12" class="mt-1 block w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('mes_aniversario')" />
                    </div>

                    <!-- Observações -->
                    <div class="sm:col-span-6">
                        <x-input-label for="observacoes" value="Observações" />
                        <x-textarea wire:model="observacoes" id="observacoes" class="mt-1 block w-full" rows="3" />
                        <x-input-error class="mt-2" :messages="$errors->get('observacoes')" />
                    </div>

                    <!-- Flags -->
                    <div class="sm:col-span-6">
                        <div class="space-y-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" wire:model="habilitado_fidelidade" class="rounded border-gray-300">
                                <span class="ml-2">Habilitado para Programa de Fidelidade</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" wire:model="ativo" class="rounded border-gray-300">
                                <span class="ml-2">Ativo</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                <x-secondary-button type="button" wire:click="$dispatch('closeModal')">
                    Cancelar
                </x-secondary-button>
                <x-primary-button type="submit">
                    {{ $contato ? 'Atualizar' : 'Criar' }}
                </x-primary-button>
            </div>
        </div>
    </form>
</div>