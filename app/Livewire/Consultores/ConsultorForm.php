<?php

namespace App\Livewire\Consultores;

use App\Models\Contatos\Contato;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ConsultorForm extends Component
{
    public ?Contato $contato = null;
    
    // Campos básicos
    public $nome = '';
    public $email = '';
    public $telefone = '';
    public $nivel = '';
    public $status = 'ativo';
    public $data_inicio = '';
    public $meta_mensal = 0;
    
    // Campos de endereço
    public $cep = '';
    public $endereco = '';
    public $numero = '';
    public $complemento = '';
    public $cidade = '';
    public $estado = '';
    
    // Campos adicionais
    public $dia_aniversario = '';
    public $mes_aniversario = '';
    public $observacoes = '';

    public function mount(?Contato $contato = null)
    {
        if ($contato && $contato->isConsultor()) {
            $this->contato = $contato;
            $this->nome = $contato->nome;
            $this->email = $contato->email;
            $this->telefone = $contato->telefone;
            $this->nivel = $contato->nivel;
            $this->status = $contato->status;
            $this->data_inicio = $contato->data_inicio?->format('Y-m-d');
            $this->meta_mensal = $contato->meta_mensal;
            
            // Campos de endereço
            $this->cep = $contato->cep;
            $this->endereco = $contato->endereco;
            $this->numero = $contato->numero;
            $this->complemento = $contato->complemento;
            $this->cidade = $contato->cidade;
            $this->estado = $contato->estado;
            
            // Campos adicionais
            $this->dia_aniversario = $contato->dia_aniversario;
            $this->mes_aniversario = $contato->mes_aniversario;
            $this->observacoes = $contato->observacoes;
        }
    }

    protected function rules()
    {
        return [
            'nome' => 'required|min:3',
            'email' => 'required|email',
            'telefone' => 'required',
            'nivel' => 'required|in:junior,pleno,senior,master',
            'status' => 'required|in:ativo,inativo,suspenso,em_treinamento',
            'data_inicio' => 'required|date',
            'meta_mensal' => 'required|numeric|min:0',
            'cep' => 'required',
            'endereco' => 'required',
            'numero' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'dia_aniversario' => 'nullable|numeric|min:1|max:31',
            'mes_aniversario' => 'nullable|numeric|min:1|max:12',
        ];
    }

    public function save()
    {
        $this->validate();

        $dados = [
            'nome' => $this->nome,
            'email' => $this->email,
            'telefone' => $this->telefone,
            'nivel' => $this->nivel,
            'status' => $this->status,
            'data_inicio' => $this->data_inicio,
            'meta_mensal' => $this->meta_mensal,
            'cep' => $this->cep,
            'endereco' => $this->endereco,
            'numero' => $this->numero,
            'complemento' => $this->complemento,
            'cidade' => $this->cidade,
            'estado' => $this->estado,
            'dia_aniversario' => $this->dia_aniversario,
            'mes_aniversario' => $this->mes_aniversario,
            'observacoes' => $this->observacoes,
        ];
        
        if ($this->contato) {
            $this->contato->update($dados);
            $this->dispatch('notify', ['message' => 'Consultor atualizado com sucesso!']);
        } else {
            $dados['user_id'] = Auth::id();
            $dados['papeis'] = ['consultor'];
            Contato::create($dados);
            $this->dispatch('notify', ['message' => 'Consultor criado com sucesso!']);
        }

        $this->dispatch('consultor-saved');
    }

    public function render()
    {
        return view('livewire.consultores.consultor-form', [
            'niveis' => [
                'junior' => 'Júnior',
                'pleno' => 'Pleno',
                'senior' => 'Sênior',
                'master' => 'Master',
            ],
            'status_list' => [
                'ativo' => 'Ativo',
                'inativo' => 'Inativo',
                'suspenso' => 'Suspenso',
                'em_treinamento' => 'Em Treinamento',
            ],
        ]);
    }
}

