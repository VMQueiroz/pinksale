<?php

namespace App\Livewire\Contatos;

use App\Models\Contatos\Contato;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ContatoForm extends Component
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
    public $estado = '';
    public $cidade = '';
    public $dia_aniversario = '';
    public $mes_aniversario = '';
    public $observacoes = '';
    
    // Campos específicos
    public $tipo_de_pele = '';
    public $tom_de_pele = '';
    public $nome_contato = '';
    public $papeis = [];
    public $habilitado_fidelidade = false;
    public $ativo = true;

    public function mount(?Contato $contato = null)
    {
        if ($contato) {
            $this->contato = $contato;
            $this->preencherDados();
        }
    }

    protected function rules()
    {
        return [
            'nome' => 'required|min:3',
            'email' => 'required|email',
            'telefone' => 'required',
            'cep' => 'required',
            'endereco' => 'required',
            'numero' => 'required',
            'estado' => 'required',
            'cidade' => 'required',
            'papeis' => 'required|array|min:1',
            'tipo_de_pele' => 'nullable',
            'tom_de_pele' => 'nullable',
            'nome_contato' => 'required_if:papeis,parceiro',
            'dia_aniversario' => 'nullable|numeric|min:1|max:31',
            'mes_aniversario' => 'nullable|numeric|min:1|max:12',
        ];
    }

    public function save()
    {
        $this->validate();

        $dados = $this->apenas_campos_preenchidos();
        
        if ($this->contato) {
            $this->contato->update($dados);
            $this->dispatch('notify', ['message' => 'Contato atualizado com sucesso!']);
        } else {
            $dados['user_id'] = Auth::id();
            Contato::create($dados);
            $this->dispatch('notify', ['message' => 'Contato criado com sucesso!']);
        }

        $this->dispatch('contato-saved');
    }

    public function render()
    {
        return view('livewire.contatos.contato-form', [
            'tipos_pele' => [
                'normal' => 'Normal',
                'seca' => 'Seca',
                'oleosa' => 'Oleosa',
                'mista' => 'Mista',
            ],
            'tons_pele' => [
                'clara' => 'Clara',
                'media' => 'Média',
                'morena' => 'Morena',
                'negra' => 'Negra',
            ],
            'papeis_disponiveis' => [
                'abordagem' => 'Abordagem',
                'cliente' => 'Cliente',
                'consultor' => 'Consultor',
                'parceiro' => 'Parceiro',
            ],
        ]);
    }

    private function preencherDados()
    {
        $this->nome = $this->contato->nome;
        $this->email = $this->contato->email;
        $this->telefone = $this->contato->telefone;
        $this->cep = $this->contato->cep;
        $this->endereco = $this->contato->endereco;
        $this->numero = $this->contato->numero;
        $this->complemento = $this->contato->complemento;
        $this->estado = $this->contato->estado;
        $this->cidade = $this->contato->cidade;
        $this->dia_aniversario = $this->contato->dia_aniversario;
        $this->mes_aniversario = $this->contato->mes_aniversario;
        $this->observacoes = $this->contato->observacoes;
        $this->tipo_de_pele = $this->contato->tipo_de_pele;
        $this->tom_de_pele = $this->contato->tom_de_pele;
        $this->nome_contato = $this->contato->nome_contato;
        $this->papeis = $this->contato->papeis ?? [];
        $this->habilitado_fidelidade = $this->contato->habilitado_fidelidade;
        $this->ativo = $this->contato->ativo;
    }

    private function apenas_campos_preenchidos()
    {
        return array_filter([
            'nome' => $this->nome,
            'email' => $this->email,
            'telefone' => $this->telefone,
            'cep' => $this->cep,
            'endereco' => $this->endereco,
            'numero' => $this->numero,
            'complemento' => $this->complemento,
            'estado' => $this->estado,
            'cidade' => $this->cidade,
            'dia_aniversario' => $this->dia_aniversario,
            'mes_aniversario' => $this->mes_aniversario,
            'observacoes' => $this->observacoes,
            'tipo_de_pele' => $this->tipo_de_pele,
            'tom_de_pele' => $this->tom_de_pele,
            'nome_contato' => $this->nome_contato,
            'papeis' => $this->papeis,
            'habilitado_fidelidade' => $this->habilitado_fidelidade,
            'ativo' => $this->ativo,
        ], function ($value) {
            return $value !== null && $value !== '';
        });
    }
}