<div>
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <div class="max-w-xl">
                <x-text-input 
                    wire:model.live.debounce.300ms="search"
                    type="search"
                    placeholder="Buscar contatos..."
                    class="w-full"
                />
            </div>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <x-primary-button
                type="button"
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'novo-contato')"
            >
                Novo Contato
            </x-primary-button>
        </div>
    </div>

    <div class="mt-4">
        <div class="flex space-x-2 overflow-x-auto">
           