<?php

namespace App\Livewire\Agenda;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Contatos\Contato;
use App\Models\Agenda\Agenda;

class EntrevistaForm extends Component
{
    public ?Contato $abordagem = null;
    public ?Agenda $evento = null;

    // Campos do formulário
    public $titulo = '';
    public $descricao = '';
    public $data_evento = '';
    public $hora_inicio = '';
    public $hora_fim = '';
    public $local = '';

    protected $listeners = [
        'close-modal' => 'resetForm'
    ];

    public function mount(?Contato $abordagem = null, ?Agenda $evento = null)
    {
        $this->abordagem = $abordagem;
        $this->evento = $evento;

        // Se temos um evento mas não uma abordagem, tentamos carregar a abordagem do evento
        if ($evento && !$abordagem && $evento->contato_id) {
            $this->abordagem = Contato::find($evento->contato_id);
        }

        // Se ainda não temos abordagem, verificar se foi passada via parâmetro na URL
        if (!$this->abordagem && request()->has('abordagem_id')) {
            $abordagemId = request()->input('abordagem_id');
            $this->abordagem = Contato::find($abordagemId);
        }

        if ($evento) {
            $this->titulo = $evento->titulo;
            $this->descricao = $evento->descricao;
            $this->data_evento = $evento->data_evento ? $evento->data_evento->format('Y-m-d') : null;
            $this->hora_inicio = $evento->hora_inicio;
            $this->hora_fim = $evento->hora_fim;
            $this->local = $evento->local;
        } elseif ($abordagem) {
            // Preencher automaticamente com valores padrão
            $this->titulo = 'Entrevista com ' . $abordagem->nome;
            $this->data_evento = now()->addDay()->format('Y-m-d');  // Agendar para amanhã por padrão
            $this->hora_inicio = '14:00';  // Horário padrão
            $this->hora_fim = '15:00';    // Duração padrão de 1 hora
        }
    }

    protected function rules()
    {
        return [
            'titulo' => 'required|min:5',
            'descricao' => 'nullable',
            'data_evento' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required',
            'hora_fim' => 'nullable|after:hora_inicio',
            'local' => 'nullable',
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'titulo',
            'descricao',
            'data_evento',
            'hora_inicio',
            'hora_fim',
            'local'
        ]);
        $this->abordagem = null;
        $this->evento = null;
    }

    public function cancel()
    {
        $this->dispatch('close-modal', modal: 'modal-entrevista');
        $this->resetForm();
    }

    public function save()
    {
        \Illuminate\Support\Facades\Log::info('Iniciando método save() do EntrevistaForm');

        $validatedData = $this->validate();
        \Illuminate\Support\Facades\Log::info('Dados validados', $validatedData);

        try {
            // Log para depuração
            \Illuminate\Support\Facades\Log::info('Tentando salvar entrevista');

            // Verificar se temos uma abordagem válida
            if (!$this->abordagem || !$this->abordagem->id) {
                // Tentar buscar a última abordagem do usuário
                $abordagem = Contato::whereJsonContains('papeis', 'abordagem')
                    ->where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($abordagem) {
                    $this->abordagem = $abordagem;
                    \Illuminate\Support\Facades\Log::info('Abordagem encontrada automaticamente', ['id' => $abordagem->id]);
                } else {
                    throw new \Exception('Não foi possível encontrar uma abordagem. Por favor, selecione uma abordagem antes de criar uma entrevista.');
                }
            }

            // Preparar os dados para salvar
            $dados = [
                'user_id' => Auth::id(),
                'tipo_evento' => 'entrevista',
                'titulo' => $this->titulo,
                'descricao' => $this->descricao,
                'data_evento' => $this->data_evento,
                'hora_inicio' => $this->hora_inicio,
                'hora_fim' => $this->hora_fim,
                'local' => $this->local,
                'status' => 'pendente',
                'contato_id' => $this->abordagem->id,
            ];

            \Illuminate\Support\Facades\Log::info('Dados preparados para salvar', $dados);

            // Verificar se temos um evento válido para atualizar
            \Illuminate\Support\Facades\Log::info('Verificando evento existente', [
                'evento_exists' => isset($this->evento),
                'evento_id' => $this->evento ? $this->evento->id : null,
                'evento_is_model' => $this->evento instanceof \Illuminate\Database\Eloquent\Model,
            ]);

            // Sempre criar um novo evento para garantir que seja salvo
            $evento = Agenda::create($dados);
            $this->evento = $evento;
            \Illuminate\Support\Facades\Log::info('Evento criado com sucesso', ['id' => $evento->id]);
            $message = 'Entrevista agendada com sucesso!';

            // Atualizamos apenas a data de último contato da abordagem quando a entrevista é agendada
            // Não atualizamos a data de retorno, pois são conceitos diferentes
            if ($this->abordagem) {
                $this->abordagem->update([
                    'ultimo_contato' => now()->format('Y-m-d'),
                ]);
                \Illuminate\Support\Facades\Log::info('Abordagem atualizada apenas com data de último contato', [
                    'abordagem_id' => $this->abordagem->id,
                    'ultimo_contato' => now()->format('Y-m-d')
                ]);
            }

            $this->dispatch('notify', type: 'success', message: $message);
            $this->dispatch('entrevista-saved');
            $this->dispatch('close-modal', modal: 'modal-entrevista');
            $this->resetForm();

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erro ao salvar entrevista', ['error' => $e->getMessage()]);
            $this->dispatch('notify', type: 'error', message: 'Erro ao salvar entrevista: ' . $e->getMessage());
        }
    }

    public function marcarComoRealizada($convertido = false)
    {
        if (!$this->evento) {
            $this->dispatch('notify', type: 'error', message: 'Você precisa salvar a entrevista antes de marcá-la como realizada.');
            return;
        }

        try {
            // Log para depuração
            \Illuminate\Support\Facades\Log::info('Tentando marcar entrevista como realizada', [
                'evento_id' => $this->evento->id,
                'convertido' => $convertido
            ]);

            // Atualizar o evento
            $this->evento->update([
                'status' => 'realizado',
            ]);

            \Illuminate\Support\Facades\Log::info('Evento atualizado para realizado');

            // Não atualizamos automaticamente nenhum campo da abordagem quando a entrevista é realizada
            // A atualização de campos como ultimo_contato deve ser feita manualmente pelo usuário
            // quando necessário, conforme a lógica de negócio
            \Illuminate\Support\Facades\Log::info('Entrevista marcada como realizada sem atualizar campos da abordagem');

            // Se a entrevista resultou em conversão para consultor
            if ($convertido) {
                $abordagem = Contato::find($this->evento->contato_id);

                if (!$abordagem) {
                    throw new \Exception('Abordagem não encontrada.');
                }

                \Illuminate\Support\Facades\Log::info('Abordagem encontrada para conversão', ['id' => $abordagem->id]);

                // Obter os papéis atuais
                $papeis = $abordagem->papeis ?? [];

                // Adicionar o papel de consultor
                if (!in_array('consultor', $papeis)) {
                    $papeis[] = 'consultor';
                }

                // Atualizar o contato
                $abordagem->update([
                    'papeis' => array_values($papeis), // Reindexar o array
                    'ativo' => true,
                    'convertido_de' => 'abordagem',
                    'data_conversao' => now(),
                    'origem_conversao' => 'entrevista',
                ]);

                \Illuminate\Support\Facades\Log::info('Abordagem convertida para consultor com sucesso');

                $this->dispatch('notify', type: 'success', message: 'Entrevista realizada e abordagem convertida para consultor com sucesso!');
            } else {
                $this->dispatch('notify', type: 'success', message: 'Entrevista marcada como realizada com sucesso!');
            }

            $this->dispatch('entrevista-atualizada');
            $this->dispatch('close-modal', modal: 'modal-entrevista');

        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Erro ao atualizar entrevista: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Verificar se temos uma abordagem válida
        $avisoAbordagem = null;
        if (!$this->abordagem) {
            $avisoAbordagem = 'Atenção: Nenhuma abordagem selecionada. Selecione uma abordagem antes de criar a entrevista.';
        }

        return view('livewire.agenda.entrevista-form', [
            'avisoAbordagem' => $avisoAbordagem
        ]);
    }
}
