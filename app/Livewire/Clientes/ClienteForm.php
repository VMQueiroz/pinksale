<?php

namespace App\Livewire\Clientes;

use Livewire\Component;
use App\Models\Contatos\Contato;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class ClienteForm extends Component
{
    public ?Contato $contato = null;
    public $nome;
    public $email;
    public $telefone;
    public $cep;
    public $endereco;
    public $numero;
    public $complemento;
    public $cidade;
    public $estado;
    public $dia_aniversario;
    public $mes_aniversario;
    public $tipo_de_pele;
    public $tom_de_pele;
    public $observacoes;
    public $habilitado_fidelidade = true;
    public $ativo = true;

    protected $listeners = ['close' => 'resetForm'];

    public function resetForm()
    {
        $this->reset([
            'nome', 'email', 'telefone', 'cep', 'endereco', 
            'numero', 'complemento', 'cidade', 'estado',
            'dia_aniversario', 'mes_aniversario', 'tipo_de_pele',
            'tom_de_pele', 'observacoes', 'habilitado_fidelidade', 'ativo'
        ]);
        $this->contato = null;
    }

    

    protected function rules()
    {
        return [
            'nome' => 'required|min:3',
            'email' => [
                'nullable',
                'email',
                Rule::unique('contatos', 'email')->ignore($this->contato?->id)
            ],
            'telefone' => [
                'required',
                Rule::unique('contatos', 'telefone')->ignore($this->contato?->id)
            ],
            'cep' => 'nullable',
            'endereco' => 'nullable',
            'numero' => 'nullable',
            'estado' => 'nullable|size:2',
            'cidade' => 'nullable',
            'dia_aniversario' => ['nullable', 'min:1', 'max:31', 'digits_between:1,2'],
            'mes_aniversario' => ['nullable', 'min:1', 'max:12', 'digits_between:1,2'],
            'tipo_de_pele' => 'nullable|in:normal,seca,oleosa,mista,sensivel',
            'tom_de_pele' => 'nullable',
        ];
    }

    public function mount(?Contato $contato = null)
    {
        if ($contato && $contato->exists) {
            $this->contato = $contato;
            $this->nome = $contato->nome;
            $this->email = $contato->email;
            $this->telefone = $contato->telefone;
            $this->cep = $contato->cep;
            $this->endereco = $contato->endereco;
            $this->numero = $contato->numero;
            $this->complemento = $contato->complemento;
            $this->cidade = $contato->cidade;
            $this->estado = $contato->estado;
            $this->dia_aniversario = $contato->dia_aniversario;
            $this->mes_aniversario = $contato->mes_aniversario;
            $this->tipo_de_pele = $contato->tipo_de_pele;
            $this->tom_de_pele = $contato->tom_de_pele;
            $this->observacoes = $contato->observacoes;
            $this->habilitado_fidelidade = $contato->habilitado_fidelidade;
            $this->ativo = $contato->ativo;
        }
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
                'dia_aniversario' => $this->dia_aniversario,
                'mes_aniversario' => $this->mes_aniversario,
                'tipo_de_pele' => $this->tipo_de_pele,
                'tom_de_pele' => $this->tom_de_pele,
                'observacoes' => $this->observacoes,
                'habilitado_fidelidade' => $this->habilitado_fidelidade,
                'ativo' => $this->ativo
            ];

            if ($this->contato && $this->contato->exists) {
                $this->contato->update($dados);
                $message = 'Cliente atualizado com sucesso!';
            } else {
                $dados['user_id'] = Auth::id();
                $dados['papeis'] = ['cliente'];
                Contato::create($dados);
                $message = 'Cliente criado com sucesso!';
            }

            $this->dispatch('notify', type: 'success', message: $message);
            $this->dispatch('cliente-saved');
            $this->dispatch('close');
            $this->resetForm();
            
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Erro ao salvar cliente: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.clientes.cliente-form');
    }
}














