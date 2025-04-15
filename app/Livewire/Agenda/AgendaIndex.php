<?php

namespace App\Livewire\Agenda;

use App\Models\Agenda\Agenda;
use App\Models\Contatos\Contato;
use App\Services\EventColorService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AgendaIndex extends Component
{
    public $search = '';
    public $tipoEventoSelected = '';
    public $dataInicio;
    public $dataFim;
    public $eventoSelecionado = null;
    public $viewMode = 'calendar'; // 'calendar' ou 'list'

    // Propriedades para o modal de evento
    public $eventoId;
    public $titulo;
    public $descricao;
    public $dataEvento;
    public $horaInicio;
    public $horaFim;
    public $local;
    public $status;
    public $contatoId;
    public $contato;
    public $tipoEvento;

    public function mount()
    {
        // Definir data início como o início do mês atual
        $this->dataInicio = Carbon::now()->startOfMonth()->format('Y-m-d');

        // Definir data fim como o fim do mês atual
        $this->dataFim = Carbon::now()->endOfMonth()->format('Y-m-d');

        // Garantir que o modo de visualização inicial seja o calendário
        $this->viewMode = 'calendar';
    }

    public function hydrate()
    {
        // Após a hidratação do componente, forçar a atualização do calendário
        if ($this->viewMode === 'calendar') {
            $this->dispatch('refreshCalendar');
        }
    }

    public function verEvento($id)
    {
        $evento = Agenda::findOrFail($id);
        $this->eventoId = $evento->id;
        $this->titulo = $evento->titulo;
        $this->descricao = $evento->descricao;
        $this->dataEvento = $evento->data_evento ? $evento->data_evento->format('Y-m-d') : null;
        $this->horaInicio = $evento->hora_inicio;
        $this->horaFim = $evento->hora_fim;
        $this->local = $evento->local;
        $this->status = $evento->status;
        $this->contatoId = $evento->contato_id;
        $this->tipoEvento = $evento->tipo_evento;

        if ($evento->contato_id) {
            $this->contato = Contato::find($evento->contato_id);
        } else {
            $this->contato = null;
        }

        $this->dispatch('open-modal', 'ver-evento');
    }

    public function editarEvento()
    {
        // Já temos os dados do evento carregados, apenas abrimos o modal de edição
        $this->dispatch('close-modal', ['modal' => 'ver-evento']);
        $this->dispatch('open-modal', 'novo-evento');
    }

    public function prepararNovoEvento($data = null)
    {
        $this->resetExcept(['search', 'tipoEventoSelected', 'dataInicio', 'dataFim', 'viewMode']);

        if ($data) {
            $this->dataEvento = $data;
        } else {
            $this->dataEvento = Carbon::now()->format('Y-m-d');
        }

        $this->horaInicio = '09:00';
        $this->horaFim = '10:00';
        $this->status = 'pendente';

        $this->dispatch('open-modal', 'novo-evento');
    }

    public function salvarEvento()
    {
        $this->validate([
            'titulo' => 'required|string|max:255',
            'dataEvento' => 'required|date',
            'horaInicio' => 'required',
            'tipoEvento' => 'required|string',
        ]);

        $dados = [
            'user_id' => Auth::id(),
            'tipo_evento' => $this->tipoEvento,
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'data_evento' => $this->dataEvento,
            'hora_inicio' => $this->horaInicio,
            'hora_fim' => $this->horaFim,
            'local' => $this->local,
            'status' => $this->status,
        ];

        if ($this->contatoId) {
            $dados['contato_id'] = $this->contatoId;
        }

        if ($this->eventoId) {
            // Atualizar evento existente
            $evento = Agenda::findOrFail($this->eventoId);
            $evento->update($dados);
            $message = 'Evento atualizado com sucesso!';

        } else {
            // Criar novo evento
            Agenda::create($dados);
            $message = 'Evento criado com sucesso!';
        }

        $this->dispatch('notify', type: 'success', message: $message);
        $this->dispatch('close-modal', modal: 'novo-evento');
        $this->resetExcept(['search', 'tipoEventoSelected', 'dataInicio', 'dataFim', 'viewMode']);
        $this->dispatch('refreshCalendar');
    }

    public function fecharModal()
    {
        $this->dispatch('close-modal', ['modal' => 'ver-evento']);
        $this->dispatch('close-modal', ['modal' => 'novo-evento']);
        $this->resetExcept(['search', 'tipoEventoSelected', 'dataInicio', 'dataFim', 'viewMode']);
    }

    // Método substituído pelo atualizarStatus

    public function marcarComoCancelado()
    {
        $this->atualizarStatus('cancelado');
    }

    public function marcarComoRealizado()
    {
        $this->atualizarStatus('realizado');
    }

    public function atualizarStatus($novoStatus)
    {
        $evento = Agenda::findOrFail($this->eventoId);
        $evento->update([
            'status' => $novoStatus
        ]);

        $mensagens = [
            'pendente' => 'Evento marcado como pendente com sucesso!',
            'realizado' => 'Evento marcado como realizado com sucesso!',
            'cancelado' => 'Evento marcado como cancelado com sucesso!'
        ];

        $this->status = $novoStatus; // Atualiza o status no componente
        $this->dispatch('notify', type: 'success', message: $mensagens[$novoStatus]);
        $this->dispatch('refreshCalendar');
    }

    public function excluirEvento()
    {
        $evento = Agenda::findOrFail($this->eventoId);
        $evento->delete();

        $this->dispatch('notify', type: 'success', message: 'Evento excluído com sucesso!');
        $this->dispatch('close-modal', ['modal' => 'ver-evento']);
        $this->resetExcept(['search', 'tipoEventoSelected', 'dataInicio', 'dataFim', 'viewMode']);
        $this->dispatch('refreshCalendar');
    }

    public function alterarVisualizacao($modo)
    {
        $this->viewMode = $modo;
        $this->dispatch('viewModeChanged', $modo);

        // Se mudar para o modo calendário, forçar a atualização do calendário
        if ($modo === 'calendar') {
            $this->dispatch('refreshCalendar');
        }
    }

    public function getEventosCalendarioProperty()
    {
        return Agenda::query()
            ->where('user_id', Auth::id())
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('titulo', 'like', '%' . $this->search . '%')
                        ->orWhere('descricao', 'like', '%' . $this->search . '%')
                        ->orWhere('local', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->tipoEventoSelected, function ($query) {
                $query->where('tipo_evento', $this->tipoEventoSelected);
            })
            ->with('contato')
            ->get()
            ->map(function ($evento) {
                return [
                    'id' => $evento->id,
                    'title' => $evento->titulo,
                    'start' => $evento->data_evento->format('Y-m-d') .
                        ($evento->hora_inicio ? 'T' . $evento->hora_inicio : ''),
                    'end' => $evento->data_evento->format('Y-m-d') .
                        ($evento->hora_fim ? 'T' . $evento->hora_fim :
                        ($evento->hora_inicio ? 'T' . Carbon::parse($evento->hora_inicio)->addHour()->format('H:i:s') : '')),
                    'tipoEvento' => $evento->tipo_evento,
                    'status' => $evento->status,
                    'local' => $evento->local,
                    'contato' => $evento->contato ? $evento->contato->nome : null,
                    'backgroundColor' => EventColorService::getEventColor($evento->tipo_evento, $evento->status),
                    'borderColor' => EventColorService::getEventBorderColor($evento->tipo_evento, $evento->status),
                    'textColor' => '#ffffff',
                ];
            });
    }

    // Métodos de cores movidos para App\Services\EventColorService

    public function render()
    {
        $query = Agenda::query()
            ->where('user_id', Auth::id())
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('titulo', 'like', '%' . $this->search . '%')
                        ->orWhere('descricao', 'like', '%' . $this->search . '%')
                        ->orWhere('local', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->tipoEventoSelected, function ($query) {
                $query->where('tipo_evento', $this->tipoEventoSelected);
            })
            ->when($this->dataInicio, function ($query) {
                $query->whereDate('data_evento', '>=', $this->dataInicio);
            })
            ->when($this->dataFim, function ($query) {
                $query->whereDate('data_evento', '<=', $this->dataFim);
            })
            ->orderBy('data_evento', 'asc')
            ->orderBy('hora_inicio', 'asc');

        $eventos = $query->paginate(10);

        // Agrupar eventos por data
        $eventosPorData = [];
        foreach ($eventos as $evento) {
            $data = $evento->data_evento->format('Y-m-d');
            if (!isset($eventosPorData[$data])) {
                $eventosPorData[$data] = [];
            }
            $eventosPorData[$data][] = $evento;
        }

        // Obter tipos de eventos únicos para o filtro
        $tiposEventos = Agenda::where('user_id', Auth::id())
            ->select('tipo_evento')
            ->distinct()
            ->pluck('tipo_evento')
            ->toArray();

        return view('livewire.agenda.agenda-index', [
            'eventos' => $eventos,
            'eventosPorData' => $eventosPorData,
            'tiposEventos' => $tiposEventos
        ]);
    }
}
