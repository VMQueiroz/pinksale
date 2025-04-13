<div>
    @if($avisoAbordagem)
        <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">{{ $avisoAbordagem }}</p>
                </div>
            </div>
        </div>
    @endif
    <form wire:submit.prevent="save" class="space-y-6">
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <!-- Título da Entrevista -->
            <div class="sm:col-span-6">
                <x-input-label for="titulo" value="Título da Entrevista" />
                <x-input wire:model="titulo" id="titulo" type="text" class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" required />
                <x-input-error :messages="$errors->get('titulo')" class="mt-2" />
            </div>

            <!-- Data e Horário -->
            <div class="sm:col-span-2">
                <x-input-label for="data_evento" value="Data da Entrevista" />
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <x-input wire:model="data_evento" id="data_evento" type="date" class="pl-10 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" required />
                </div>
                <x-input-error :messages="$errors->get('data_evento')" class="mt-2" />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="hora_inicio" value="Hora de Início" />
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <x-input wire:model="hora_inicio" id="hora_inicio" type="time" class="pl-10 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" required />
                </div>
                <x-input-error :messages="$errors->get('hora_inicio')" class="mt-2" />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="hora_fim" value="Hora de Término" />
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <x-input wire:model="hora_fim" id="hora_fim" type="time" class="pl-10 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" />
                </div>
                <x-input-error :messages="$errors->get('hora_fim')" class="mt-2" />
            </div>

            <!-- Local -->
            <div class="sm:col-span-6">
                <x-input-label for="local" value="Local da Entrevista" />
                <x-input wire:model="local" id="local" type="text" class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm" placeholder="Ex: Escritório, Videochamada, etc." />
                <x-input-error :messages="$errors->get('local')" class="mt-2" />
            </div>

            <!-- Descrição -->
            <div class="sm:col-span-6">
                <x-input-label for="descricao" value="Descrição" />
                <textarea
                    wire:model="descricao"
                    id="descricao"
                    class="mt-1 block w-full border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm"
                    rows="4"
                ></textarea>
                <x-input-error :messages="$errors->get('descricao')" class="mt-2" />
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="border-t border-gray-200 pt-4">
            @if($evento && $evento->exists && $evento->status === 'pendente')
                <div class="mb-4 bg-gray-50 p-3 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Marcar Entrevista como Realizada</h3>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" wire:click="marcarComoRealizada(false)" class="flex items-center px-4 py-2 bg-green-100 border border-transparent rounded-md font-semibold text-xs text-green-800 uppercase tracking-widest hover:bg-green-200 active:bg-green-300 focus:outline-none focus:border-green-500 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Realizada (Sem Conversão)
                        </button>
                        <button type="button" wire:click="marcarComoRealizada(true)" class="flex items-center px-4 py-2 bg-blue-100 border border-transparent rounded-md font-semibold text-xs text-blue-800 uppercase tracking-widest hover:bg-blue-200 active:bg-blue-300 focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Realizada e Convertida para Consultor
                        </button>
                    </div>
                </div>
            @endif

            <!-- Botões de Salvar/Cancelar -->
            <div class="flex justify-end space-x-3">
                <x-secondary-button type="button" wire:click="cancel" class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cancelar
                </x-secondary-button>
                <x-primary-button type="submit" class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ isset($evento) && $evento ? 'Atualizar Entrevista' : 'Agendar Entrevista' }}
                </x-primary-button>

                <!-- Botão de teste -->
                <button type="button" wire:click="$refresh" class="ml-2 px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                    Testar Componente
                </button>
            </div>
        </div>
    </form>
</div>
