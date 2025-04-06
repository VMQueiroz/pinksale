<div>
    <form wire:submit="save" class="space-y-6">
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <x-input-label for="nome" value="Nome" />
                <x-text-input wire:model="nome" id="nome" type="text" class="mt-1 block w-full" required />
                <x-input-error class="mt-2" :messages="$errors->get('nome')" />
            </div>

            <div class="sm:col-span-3">
                <x-input-label for="email" value="E-mail" />
                <x-text-input wire:model="email" id="email" type="email" class="mt-1 block w-full" required />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="telefone" value="Telefone" />
                <x-text-input wire:model="telefone" id="telefone" type="text" class="mt-1 block w-full" required />
                <x-input-error class="mt-2" :messages="$errors->get('telefone')" />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="nivel" value="Nível" />
                <select wire:model="nivel" id="nivel" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Selecione...</option>
                    @foreach($niveis as $valor => $label)
                        <option value="{{ $valor }}">{{ $label }}</option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('nivel')" />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="status" value="Status" />
                <select wire:model="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @foreach($status_list as $valor => $label)
                        <option value="{{ $valor }}">{{ $label }}</option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('status')" />
            </div>

            <div class="sm:col-span-3">
                <x-input-label for="data_inicio" value="Data de Início" />
                <x-text-input wire:model="data_inicio" id="data_inicio" type="date" class="mt-1 block w-full" required />
                <x-input-error class="mt-2" :messages="$errors->get('data_inicio')" />
            </div>

            <div class="sm:col-span-3">
                <x-input-label for="meta_mensal" value="Meta Mensal (R$)" />
                <x-text-input wire:model="meta_mensal" id="meta_mensal" type="number" step="0.01" class="mt-1 block w-full" required />
                <x-input-error class="mt-2" :messages="$errors->get('meta_mensal')" />
            </div>
        </div>

        <div class="flex justify-end gap-x-4">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">
                Cancelar
            </x-secondary-button>
            
            <x-primary-button type="submit">
                {{ $consultor ? 'Atualizar' : 'Criar' }}
            </x-primary-button>
        </div>
    </form>
</div>