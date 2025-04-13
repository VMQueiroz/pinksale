<div>
    <x-notification />

    {{-- Cabeçalho --}}
    <div class="mb-6">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Agenda
            </h2>
        </x-slot>
    </div>

    {{-- Filtros (apenas no modo lista) --}}
    @if($viewMode === 'list')
        <div class="bg-white p-4 rounded-lg shadow mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                    <x-input wire:model.live.debounce.300ms="search" id="search" type="search" placeholder="Buscar eventos..." class="w-full" />
                </div>

                <div>
                    <label for="tipoEvento" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Evento</label>
                    <x-select wire:model.live="tipoEvento" id="tipoEvento" class="w-full">
                        <option value="">Todos os tipos</option>
                        @foreach($tiposEventos as $tipo)
                            <option value="{{ $tipo }}">{{ ucfirst($tipo) }}</option>
                        @endforeach
                    </x-select>
                </div>

                <div>
                    <label for="dataInicio" class="block text-sm font-medium text-gray-700 mb-1">Data Início</label>
                    <x-input wire:model.live="dataInicio" id="dataInicio" type="date" class="w-full" />
                </div>

                <div>
                    <label for="dataFim" class="block text-sm font-medium text-gray-700 mb-1">Data Fim</label>
                    <x-input wire:model.live="dataFim" id="dataFim" type="date" class="w-full" />
                </div>
            </div>
        </div>
    @endif

    {{-- Botões de Visualização e Ações --}}
    <div class="flex justify-between items-center mb-4">
        <div class="flex space-x-2">
            <button
                wire:click="alterarVisualizacao('calendar')"
                class="view-button {{ $viewMode === 'calendar' ? 'active' : '' }}"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Calendário
            </button>
            <button
                wire:click="alterarVisualizacao('list')"
                class="view-button {{ $viewMode === 'list' ? 'active' : '' }}"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                Lista
            </button>
        </div>

        <div>
            <x-button wire:click="prepararNovoEvento" class="bg-pk hover:bg-pk-dark text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Novo Evento
            </x-button>
        </div>
    </div>

    {{-- Visualização de Calendário --}}
    @if($viewMode === 'calendar')
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div
                x-data="calendarData"
                x-init="$nextTick(() => {
                    if (!calendar) {
                        setTimeout(() => initCalendar(), 100);
                    }
                })"
                class="calendar-container"
            >
                <div x-ref="calendar" wire:ignore></div>
            </div>
        </div>
    @endif

        {{-- Visualização de Lista --}}
        @if($viewMode === 'list')
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if(count($eventosPorData) === 0)
                <div class="p-6 text-center text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-lg">Nenhum evento encontrado para o período selecionado.</p>
                    <p class="mt-2">Tente ajustar os filtros ou criar um novo evento.</p>
                </div>
            @else
                <div class="divide-y divide-gray-200">
                    @foreach($eventosPorData as $data => $eventosData)
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">
                                {{ \Carbon\Carbon::parse($data)->format('d/m/Y') }} -
                                {{ \Carbon\Carbon::parse($data)->locale('pt-BR')->isoFormat('dddd') }}
                            </h3>

                            <div class="space-y-3">
                                @foreach($eventosData as $evento)
                                    <div
                                        wire:click="verEvento({{ $evento->id }})"
                                        class="flex items-start p-3 rounded-lg border {{ $evento->status === 'realizado' ? 'bg-green-50 border-green-200' : ($evento->status === 'cancelado' ? 'bg-red-50 border-red-200' : 'bg-white border-gray-200') }} hover:bg-gray-50 cursor-pointer transition-colors"
                                    >
                                        <div class="flex-shrink-0 mr-3">
                                            @if($evento->tipo_evento === 'entrevista')
                                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                            @elseif($evento->tipo_evento === 'sessao')
                                                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex-1">
                                            <div class="flex justify-between">
                                                <h4 class="text-sm font-medium text-gray-900">{{ $evento->titulo }}</h4>
                                                <span class="text-sm text-gray-500">
                                                    {{ $evento->hora_inicio ? \Carbon\Carbon::parse($evento->hora_inicio)->format('H:i') : '' }}
                                                    @if($evento->hora_fim)
                                                        - {{ \Carbon\Carbon::parse($evento->hora_fim)->format('H:i') }}
                                                    @endif
                                                </span>
                                            </div>

                                            @if($evento->descricao)
                                                <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $evento->descricao }}</p>
                                            @endif

                                            <div class="mt-2 flex items-center text-xs text-gray-500">
                                                @if($evento->local)
                                                    <span class="flex items-center mr-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                        {{ $evento->local }}
                                                    </span>
                                                @endif

                                                @if($evento->contato)
                                                    <span class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                        {{ $evento->contato->nome }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="ml-2">
                                            @if($evento->status === 'pendente')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Pendente
                                                </span>
                                            @elseif($evento->status === 'realizado')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Realizado
                                                </span>
                                            @elseif($evento->status === 'cancelado')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Cancelado
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="mt-4">
            {{ $eventos->links() }}
        </div>
    @endif

    {{-- Modal de Detalhes do Evento --}}
    <x-modal wire:model.live="showEventoModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-pk" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Detalhes do Evento
            </h2>

            @if($eventoId)
                <div class="space-y-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">{{ $titulo }}</h3>
                        @if($status === 'pendente')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Pendente
                            </span>
                        @elseif($status === 'realizado')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Realizado
                            </span>
                        @elseif($status === 'cancelado')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Cancelado
                            </span>
                        @endif
                    </div>

                    @if($descricao)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Descrição</h4>
                            <p class="mt-1 text-sm text-gray-600">{{ $descricao }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Data</h4>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $dataEvento ? \Carbon\Carbon::parse($dataEvento)->format('d/m/Y') : 'Não definida' }}
                            </p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Horário</h4>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $horaInicio ? \Carbon\Carbon::parse($horaInicio)->format('H:i') : '' }}
                                @if($horaFim)
                                    - {{ \Carbon\Carbon::parse($horaFim)->format('H:i') }}
                                @endif
                            </p>
                        </div>

                        @if($local)
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Local</h4>
                                <p class="mt-1 text-sm text-gray-600">{{ $local }}</p>
                            </div>
                        @endif

                        @if($contato)
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Contato</h4>
                                <p class="mt-1 text-sm text-gray-600">{{ $contato->nome }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 flex justify-between">
                        <div>
                            <x-secondary-button wire:click="fecharModal" class="mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Fechar
                            </x-secondary-button>
                        </div>

                        <div>
                            @if($status === 'pendente')
                                <x-danger-button wire:click="marcarComoCancelado" class="mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Cancelar Evento
                                </x-danger-button>

                                <x-button wire:click="marcarComoRealizado" class="bg-green-600 hover:bg-green-700 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Marcar como Realizado
                                </x-button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-modal>

    {{-- Modal de Novo Evento --}}
    <x-modal wire:model.live="showNovoEventoModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-pk" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ $eventoId ? 'Editar Evento' : 'Novo Evento' }}
            </h2>

            <form wire:submit.prevent="salvarEvento">
                <div class="space-y-4">
                    <div>
                        <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
                        <x-input wire:model="titulo" id="titulo" type="text" class="mt-1 block w-full" required />
                    </div>

                    <div>
                        <label for="tipoEvento" class="block text-sm font-medium text-gray-700">Tipo de Evento</label>
                        <x-select wire:model="tipoEvento" id="tipoEvento" class="mt-1 block w-full" required>
                            <option value="">Selecione um tipo</option>
                            <option value="entrevista">Entrevista</option>
                            <option value="sessao">Sessão</option>
                            <option value="outro">Outro</option>
                        </x-select>
                    </div>

                    <div>
                        <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                        <x-textarea wire:model="descricao" id="descricao" class="mt-1 block w-full" rows="3"></x-textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="dataEvento" class="block text-sm font-medium text-gray-700">Data</label>
                            <x-input wire:model="dataEvento" id="dataEvento" type="date" class="mt-1 block w-full" required />
                        </div>

                        <div>
                            <label for="horaInicio" class="block text-sm font-medium text-gray-700">Hora de Início</label>
                            <x-input wire:model="horaInicio" id="horaInicio" type="time" class="mt-1 block w-full" required />
                        </div>

                        <div>
                            <label for="horaFim" class="block text-sm font-medium text-gray-700">Hora de Término</label>
                            <x-input wire:model="horaFim" id="horaFim" type="time" class="mt-1 block w-full" />
                        </div>

                        <div>
                            <label for="local" class="block text-sm font-medium text-gray-700">Local</label>
                            <x-input wire:model="local" id="local" type="text" class="mt-1 block w-full" />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <x-secondary-button wire:click="fecharModal" type="button">
                            Cancelar
                        </x-secondary-button>

                        <x-button type="submit" class="bg-pk hover:bg-pk-dark">
                            {{ $eventoId ? 'Atualizar' : 'Salvar' }}
                        </x-button>
                    </div>
                </div>
            </form>
        </div>
    </x-modal>
</div>