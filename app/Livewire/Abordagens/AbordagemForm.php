<?php

namespace App\Livewire\Abordagens;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Models\Contatos\Contato;
use Illuminate\Support\Facades\Auth;

class AbordagemForm extends Component
{
    public ?Contato $contato = null;

    // Campos básicos
    public $nome = '';
    public $email = '';
    public $telefone = '';
    public $cep = '';
    public $endereco = '';
    public $numero = '';
    public $complemento = '';
    public $cidade = '';
    public $estado = '';
    public $observacoes = '';

    // Campos específicos de abordagem
    public $tipo_abordagem = 'cliente'; // 'cliente' ou 'inicio'
    public $indicado_por = null;
    public $data_retorno = null;
    public $ultimo_contato = null;

    public function mount(?Contato $contato = null)
    {
        if ($contato && $contato->isAbordagem()) {
            $this->contato = $contato;
            $this->nome = $contato->nome;
            $this->email = $contato->email;
            $this->telefone = $contato->telefone;

            // Campos de endereço
            $this->cep = $contato->cep;
            $this->endereco = $contato->endereco;
            $this->numero = $contato->numero;
            $this->complemento = $contato->complemento;
            $this->cidade = $contato->cidade;
            $this->estado = $contato->estado;

            // Campos específicos
            $this->observacoes = $contato->observacoes;
            $this->tipo_abordagem = $contato->tipo_abordagem;
            $this->indicado_por = $contato->indicado_por;
            $this->data_retorno = $contato->data_retorno?->format('Y-m-d');
            $this->ultimo_contato = $contato->ultimo_contato?->format('Y-m-d');
        }
    }

    protected function rules()
    {
        $rules = [
            'nome' => 'required|min:3',
            'email' => [
                'nullable',
                'email',
                Rule::unique('contatos', 'email')
                    ->ignore($this->contato?->id)
                    ->where(function ($query) {
                        $query->whereNotNull('email');
                    }),
            ],
            'telefone' => [
                'required',
                Rule::unique('contatos', 'telefone')->ignore($this->contato?->id)
            ],
            'cep' => 'nullable',
            'endereco' => 'nullable',
            'numero' => 'nullable',
            'cidade' => 'nullable',
            'estado' => 'nullable|size:2',
            'tipo_abordagem' => 'required|in:cliente,inicio',
            'indicado_por' => 'nullable',
            'data_retorno' => 'nullable|date',
            'ultimo_contato' => 'nullable|date',
        ];

        return $rules;
    }

    protected $listeners = [
        'close-modal' => 'resetForm'
    ];

    // Atualiza a interface quando o tipo de abordagem muda
    public function updatedTipoAbordagem()
    {
        // Este método é chamado automaticamente quando o valor de tipo_abordagem é alterado
        // Podemos adicionar lógica adicional aqui se necessário
    }

    public function resetForm()
    {
        $this->reset();
        $this->contato = null;
    }

    public function cancel()
    {
        if ($this->contato) {
            $this->mount($this->contato);
            $this->dispatch('close-modal', modal: 'editar-abordagem');
        } else {
            $this->resetForm();
            $this->dispatch('close-modal', modal: 'nova-abordagem');
        }
    }

    public function buscarCep()
    {
        $cepService = app(\App\Services\CepService::class);

        $dados = $cepService->buscarDados($this->cep);

        if ($dados) {
            $this->endereco = $dados['logradouro'] ?? '';
            $this->cidade   = $dados['localidade'] ?? '';
            $this->estado   = $dados['uf'] ?? '';
        }
    }

    public function save()
    {
        $this->validate();

        try {
            $dados = [
                'nome' => $this->nome,
                'email' => $this->email,
                'telefone' => $this->telefone,
                'cep' => $this->cep,
                'endereco' => $this->endereco,
                'numero' => $this->numero,
                'complemento' => $this->complemento,
                'cidade' => $this->cidade,
                'estado' => $this->estado,
                'observacoes' => $this->observacoes,
                'tipo_abordagem' => $this->tipo_abordagem,
                'indicado_por' => $this->indicado_por,
                'data_retorno' => !empty($this->data_retorno) ? $this->data_retorno : null,
                'ultimo_contato' => !empty($this->ultimo_contato) ? $this->ultimo_contato : null,
            ];

            if ($this->contato && $this->contato->exists) {
                $this->contato->update($dados);
                $message = 'Abordagem atualizada com sucesso!';
                $nomemodal = 'editar-abordagem';
            } else {
                $dados['user_id'] = Auth::id();
                $dados['papeis'] = ['abordagem'];
                Contato::create($dados);
                $message = 'Abordagem criada com sucesso!';
                $nomemodal = 'nova-abordagem';
            }

            $this->dispatch('notify', type: 'success', message: $message);
            $this->dispatch('abordagem-saved');
            $this->dispatch('close-modal', modal: $nomemodal);
            $this->resetForm();

        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Erro ao salvar abordagem: ' . $e->getMessage());
        }
    }

    public function criarEntrevista()
    {
        if (!$this->contato || !$this->contato->exists) {
            $this->dispatch('notify', type: 'error', message: 'Você precisa salvar a abordagem antes de agendar uma entrevista.');
            return;
        }

        // Verificar se a abordagem é do tipo 'inicio'
        if ($this->contato->tipo_abordagem !== 'inicio') {
            $this->dispatch('notify', type: 'error', message: 'Apenas abordagens do tipo "Início" podem ter entrevistas agendadas.');
            return;
        }

        // Fechar o modal de edição de abordagem
        $this->dispatch('close-modal', modal: 'editar-abordagem');

        // Emitir evento para o componente AbordagemIndex
        $this->dispatch('abrir-modal-entrevista', $this->contato->id);
    }

    public function transferirParaCliente()
    {
        if (!$this->contato || !$this->contato->exists) {
            $this->dispatch('notify', type: 'error', message: 'Você precisa salvar a abordagem antes de transferir para cliente.');
            return;
        }

        try {
            // Modificar os papéis do contato
            $papeis = $this->contato->papeis ?? [];

            // Remover o papel de abordagem
            $papeis = array_filter($papeis, function($papel) {
                return $papel !== 'abordagem';
            });

            // Adicionar o papel de cliente
            if (!in_array('cliente', $papeis)) {
                $papeis[] = 'cliente';
            }

            // Atualizar o contato
            $this->contato->update([
                'papeis' => array_values($papeis), // Reindexar o array
                'ativo' => true,
                'habilitado_fidelidade' => true,
                'convertido_de' => 'abordagem',
                'data_conversao' => now(),
                'origem_conversao' => 'transferencia_direta',
            ]);

            $this->dispatch('notify', type: 'success', message: $this->nome . ' foi transferido(a) para o cadastro de clientes com sucesso!');
            $this->dispatch('close-modal', modal: 'editar-abordagem');
            $this->dispatch('abordagem-transferida');

        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Erro ao transferir para cliente: ' . $e->getMessage());
        }
    }

    public function transferirParaConsultor()
    {
        if (!$this->contato || !$this->contato->exists) {
            $this->dispatch('notify', type: 'error', message: 'Você precisa salvar a abordagem antes de transferir para consultor.');
            return;
        }

        try {
            // Modificar os papéis do contato
            $papeis = $this->contato->papeis ?? [];

            // Remover o papel de abordagem
            $papeis = array_filter($papeis, function($papel) {
                return $papel !== 'abordagem';
            });

            // Adicionar o papel de consultor
            if (!in_array('consultor', $papeis)) {
                $papeis[] = 'consultor';
            }

            // Atualizar o contato
            $this->contato->update([
                'papeis' => array_values($papeis), // Reindexar o array
                'ativo' => true,
                'iniciado_por_mim' => true,
                'data_inicio' => now(),
                'convertido_de' => 'abordagem',
                'data_conversao' => now(),
                'origem_conversao' => 'transferencia_direta',
            ]);

            $this->dispatch('notify', type: 'success', message: $this->nome . ' foi transferido(a) para o cadastro de consultores com sucesso!');
            $this->dispatch('close-modal', modal: 'editar-abordagem');
            $this->dispatch('abordagem-transferida');

        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Erro ao transferir para consultor: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.abordagens.abordagem-form');
    }
}
