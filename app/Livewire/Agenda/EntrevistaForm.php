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

    public function mount(?Contato $abordagem = null)
    {
        $this->abordagem = $abordagem;
        if ($this->abordagem){     // Preencher automaticamente com valores padrão
            $this->titulo = 'Entrevista';
            $this->descricao = 'Entrevista com ' . $this->abordagem->nome;
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

                // Criar um novo evento
                $evento = Agenda::create($dados);
                $this->evento = $evento;
                \Illuminate\Support\Facades\Log::info('Novo evento criado com sucesso', ['id' => $evento->id]);
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
