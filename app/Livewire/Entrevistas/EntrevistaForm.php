<?php

namespace App\Livewire\Entrevistas;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Contatos\Contato;
use App\Models\Entrevistas\Entrevista;

class EntrevistaForm extends Component
{
    public ?Contato $abordagem = null;
    public ?Entrevista $entrevista = null;

    // Campos do formulário
    public $titulo = '';
    public $descricao = '';
    public $data_entrevista = '';
    public $hora_inicio = '';
    public $hora_fim = '';
    public $local = '';

    protected $listeners = [
        'close-modal' => 'resetForm'
    ];

    public function mount(?Contato $abordagem = null, ?Entrevista $entrevista = null)
    {
        $this->abordagem = $abordagem;
        $this->entrevista = $entrevista;

        // Se temos uma entrevista mas não uma abordagem, carregamos a abordagem da entrevista
        if ($entrevista && !$abordagem && $entrevista->abordagem_id) {
            $this->abordagem = Contato::find($entrevista->abordagem_id);
        }

        // Se ainda não temos abordagem, verificar se foi passada via parâmetro na URL
        if (!$this->abordagem && request()->has('abordagem_id')) {
            $abordagemId = request()->input('abordagem_id');
            $this->abordagem = Contato::find($abordagemId);
        }

        if ($entrevista) {
            $this->titulo = $entrevista->titulo;
            $this->descricao = $entrevista->descricao;
            $this->data_entrevista = $entrevista->data_entrevista ? $entrevista->data_entrevista->format('Y-m-d') : null;
            $this->hora_inicio = $entrevista->hora_inicio;
            $this->hora_fim = $entrevista->hora_fim;
            $this->local = $entrevista->local;
        } elseif ($abordagem) {
            // Preencher automaticamente com valores padrão
            $this->titulo = 'Entrevista com ' . $abordagem->nome;
            $this->descricao = $abordagem->observacoes;
            $this->data_entrevista = now()->addDays(2)->format('Y-m-d'); // Agendar para 2 dias depois
            $this->hora_inicio = '14:00'; // Horário padrão
            $this->hora_fim = '15:00';    // Duração padrão de 1 hora
        }
    }

    protected function rules()
    {
        return [
            'titulo' => 'required|min:5',
            'descricao' => 'nullable',
            'data_entrevista' => 'required|date|after_or_equal:today',
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
            'data_entrevista',
            'hora_inicio',
            'hora_fim',
            'local'
        ]);
        $this->abordagem = null;
        $this->entrevista = null;
    }

    public function cancel()
    {
        $this->dispatch('close-modal', modal: 'modal-entrevista');
        $this->resetForm();
    }

    public function save()
    {
        $this->validate();

        try {
            $dados = [
                'titulo' => $this->titulo,
                'descricao' => $this->descricao,
                'data_entrevista' => $this->data_entrevista,
                'hora_inicio' => $this->hora_inicio,
                'hora_fim' => $this->hora_fim,
                'local' => $this->local,
                'status' => 'pendente',
            ];

            if ($this->entrevista) {
                // Sempre definir o user_id para garantir que esteja atualizado
                $dados['user_id'] = Auth::id();

                // Garantir que o abordagem_id esteja definido
                if ($this->abordagem) {
                    $dados['abordagem_id'] = $this->abordagem->id;
                } elseif ($this->entrevista->abordagem_id) {
                    // Manter o abordagem_id existente
                    $dados['abordagem_id'] = $this->entrevista->abordagem_id;
                }

                // Atualizar entrevista existente
                $this->entrevista->update($dados);

                // Sincronizar com a agenda
                $this->entrevista->sincronizarComAgenda();

                $message = 'Entrevista atualizada com sucesso!';
            } else {
                // Criar nova entrevista
                $dados['user_id'] = Auth::id();

                // Verificar se temos uma abordagem válida
                if (!$this->abordagem || !$this->abordagem->id) {
                    // Tentar buscar a última abordagem do usuário
                    $abordagem = Contato::whereJsonContains('papeis', 'abordagem')
                        ->where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->first();

                    if ($abordagem) {
                        $this->abordagem = $abordagem;
                    } else {
                        throw new \Exception('Não foi possível encontrar uma abordagem. Por favor, selecione uma abordagem antes de criar uma entrevista.');
                    }
                }

                // Garantir que temos um abordagem_id válido
                $dados['abordagem_id'] = $this->abordagem->id;

                // Log para depuração
                \Illuminate\Support\Facades\Log::info('Tentando criar entrevista com dados:', $dados);

                try {
                    // Criar a entrevista
                    $entrevista = Entrevista::create($dados);

                    // Log do ID da entrevista criada
                    \Illuminate\Support\Facades\Log::info('Entrevista criada com ID: ' . $entrevista->id);

                    // Atribuir a entrevista criada à propriedade do componente
                    $this->entrevista = $entrevista;

                    // Sincronizar com a agenda
                    $entrevista->sincronizarComAgenda();
                } catch (\Exception $createException) {
                    \Illuminate\Support\Facades\Log::error('Erro ao criar entrevista: ' . $createException->getMessage());
                    throw $createException;
                }

                // Atualizar a data de último contato da abordagem
                $this->abordagem->update([
                    'ultimo_contato' => now()->format('Y-m-d')
                ]);

                $message = 'Entrevista agendada com sucesso!';
            }

            $this->dispatch('notify', type: 'success', message: $message);
            $this->dispatch('entrevista-saved');
            $this->dispatch('close-modal', modal: 'modal-entrevista');
            $this->resetForm();

        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Erro ao salvar entrevista: ' . $e->getMessage());
        }
    }

    public function marcarComoRealizada($convertido = false)
    {
        if (!$this->entrevista) {
            $this->dispatch('notify', type: 'error', message: 'Você precisa salvar a entrevista antes de marcá-la como realizada.');
            return;
        }

        try {
            // Sempre definir o user_id para garantir que esteja atualizado
            $this->entrevista->user_id = Auth::id();

            // Garantir que o abordagem_id esteja definido
            if ($this->abordagem && $this->abordagem->id) {
                $this->entrevista->abordagem_id = $this->abordagem->id;
            }
            // Se ainda não temos abordagem_id e não temos abordagem, tentar buscar a última abordagem do usuário
            elseif (!$this->entrevista->abordagem_id) {
                // Buscar a última abordagem do usuário atual
                $abordagem = Contato::whereJsonContains('papeis', 'abordagem')
                    ->where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($abordagem) {
                    $this->abordagem = $abordagem;
                    $this->entrevista->abordagem_id = $abordagem->id;
                } else {
                    // Se não encontrarmos nenhuma abordagem, não podemos continuar
                    $this->dispatch('notify', type: 'error', message: 'Não foi possível identificar uma abordagem para esta entrevista. Por favor, edite a entrevista e selecione uma abordagem.');
                    return;
                }
            }

            // Verificar se temos abordagem_id antes de salvar
            if (!$this->entrevista->abordagem_id) {
                $this->dispatch('notify', type: 'error', message: 'Não foi possível identificar uma abordagem para esta entrevista. Por favor, edite a entrevista e selecione uma abordagem.');
                return;
            }

            // Salvar as alterações
            $this->entrevista->save();

            // Atualizar a entrevista
            $this->entrevista->update([
                'status' => 'realizada',
                'data_realizacao' => now(),
                'convertido_consultor' => $convertido,
            ]);

            // Sincronizar com a agenda
            $this->entrevista->sincronizarComAgenda();

            // Se a entrevista resultou em conversão para consultor
            if ($convertido) {
                $abordagem = $this->entrevista->abordagem;

                if (!$abordagem) {
                    throw new \Exception('Abordagem não encontrada.');
                }

                // Modificar os papéis do contato
                $papeis = $abordagem->papeis ?? [];

                // Remover o papel de abordagem
                $papeis = array_filter($papeis, function($papel) {
                    return $papel !== 'abordagem';
                });

                // Adicionar o papel de consultor
                if (!in_array('consultor', $papeis)) {
                    $papeis[] = 'consultor';
                }

                // Atualizar o contato
                $abordagem->update([
                    'papeis' => array_values($papeis), // Reindexar o array
                    'ativo' => true,
                    'iniciado_por_mim' => true,
                    'data_inicio' => now(),
                    'convertido_de' => 'abordagem',
                    'data_conversao' => now(),
                    'origem_conversao' => 'entrevista',
                ]);

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

        return view('livewire.entrevistas.entrevista-form', [
            'avisoAbordagem' => $avisoAbordagem
        ]);
    }
}