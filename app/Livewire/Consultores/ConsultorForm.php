<?php

namespace App\Livewire\Consultores;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Models\Contatos\Contato;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ConsultorForm extends Component
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
    
    // Campos adicionais
    public $iniciado_por_mim = false;
    public $data_inicio = null;

    public function mount(?Contato $contato = null)
    {
        if ($contato && $contato->isConsultor()) {
            $this->contato = $contato;
            $this->nome = $contato->nome;
            $this->email = $contato->email;
            $this->telefone = $contato->telefone;
            $this->data_inicio = $contato->data_inicio?->format('Y-m-d');
            
            // Campos de endereço
            $this->cep = $contato->cep;
            $this->endereco = $contato->endereco;
            $this->numero = $contato->numero;
            $this->complemento = $contato->complemento;
            $this->cidade = $contato->cidade;
            $this->estado = $contato->estado;
            
            // Campos adicionais
            $this->observacoes = $contato->observacoes;
            $this->iniciado_por_mim = $contato->iniciado_por_mim;
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
            'estado' => 'nullable',
            'iniciado_por_mim' => 'boolean',
        ];

        // Adiciona validação condicional para data_inicio
        if ($this->iniciado_por_mim) {
            $rules['data_inicio'] = 'required|date';
        } else {
            $rules['data_inicio'] = 'nullable|date';
        }

        return $rules;
    }

    protected $listeners = ['close' => 'resetForm'];

    public function resetForm()
    {
        $this->reset();
        $this->contato = null;
    }

    public function buscarCep()
    {
        $cep = preg_replace('/[^0-9]/', '', $this->cep);

        if (strlen($cep) === 8) {
            try {
                $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");
                if ($response->successful()) {
                    $dados = $response->json();
                    if (!isset($dados['erro'])) {
                        $this->endereco = $dados['logradouro'];
                        $this->cidade = $dados['localidade'];
                        $this->estado = $dados['uf'];
                    }
                }
            } catch (\Exception $e) {
                // Silently fail
            }
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
                'iniciado_por_mim' => $this->iniciado_por_mim,
                'data_inicio' => $this->data_inicio,
            ];

            if ($this->contato && $this->contato->exists) {
                $this->contato->update($dados);
                $message = 'Consultor atualizado com sucesso!';
            } else {
                $dados['user_id'] = Auth::id();
                $dados['papeis'] = ['consultor'];
                Contato::create($dados);
                $message = 'Consultor criado com sucesso!';
            }

            $this->dispatch('notify', type: 'success', message: $message);
            $this->dispatch('consultor-saved');
            $this->dispatch('close');
            $this->resetForm();
            
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Erro ao salvar consultor: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.consultores.consultor-form');
    }

    // Adicionar um método para atualizar a validação quando o checkbox mudar
    public function updatedIniciadoPorMim()
    {
        if (!$this->iniciado_por_mim) {
            $this->data_inicio = null;
        }
        $this->validateOnly('data_inicio');
    }
}




