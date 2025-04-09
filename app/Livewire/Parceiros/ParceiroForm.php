<?php

namespace App\Livewire\Parceiros;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Models\Contatos\Contato;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ParceiroForm extends Component
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
    
    // Campo adicional
    public $nome_contato = '';

    public function mount(?Contato $contato = null)
    {
        if ($contato && $contato->isParceiro()) {
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
            
            // Campos adicionais
            $this->observacoes = $contato->observacoes;
            $this->nome_contato = $contato->nome_contato;
        }
    }

    protected function rules()
    {
        return [
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
            'estado' => 'nullable',
            'nome_contato' => 'nullable|min:3',
        ];
    }

    protected $listeners = ['close-modal' => 'resetForm'];

    public function resetForm()
    {
        $this->reset([
            'nome', 'email', 'telefone',
            'cep', 'endereco', 'numero', 'complemento', 'cidade', 'estado',
            'observacoes', 'nome_contato'
        ]);
        $this->contato = null;
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

    public function cancel()  // Adicionado método cancel
    {
        if ($this->contato) {
            $this->mount($this->contato);
            $this->dispatch('close-modal', modal: 'editar-parceiro');
        } else {
            $this->resetForm();
            $this->dispatch('close-modal', modal: 'novo-parceiro');
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
                'nome_contato' => $this->nome_contato,
            ];

            if ($this->contato && $this->contato->exists) {
                $this->contato->update($dados);
                $message = 'Parceiro atualizado com sucesso!';
            } else {
                $dados['user_id'] = Auth::id();
                $dados['papeis'] = ['parceiro'];
                Contato::create($dados);
                $message = 'Parceiro criado com sucesso!';
            }

            $this->dispatch('notify', type: 'success', message: $message);
            $this->dispatch('parceiro-saved');
            $this->dispatch('close-modal', modal: 'editar-parceiro');  // Alterado para usar modal específico
            $this->resetForm();
            
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Erro ao salvar parceiro: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.parceiros.parceiro-form');
    }
}

