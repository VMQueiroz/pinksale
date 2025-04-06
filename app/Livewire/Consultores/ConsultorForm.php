<?php

namespace App\Livewire\Consultores;

use App\Models\Consultores\Consultor;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ConsultorForm extends Component
{
    public ?Consultor $consultor = null;
    
    // Campos básicos
    public $nome = '';
    public $email = '';
    public $telefone = '';
    public $nivel = '';
    public $status = 'ativo';
    public $data_inicio = '';
    public $meta_mensal = 0;

    public function mount(?Consultor $consultor = null)
    {
        if ($consultor) {
            $this->consultor = $consultor;
            $this->nome = $consultor->nome;
            $this->email = $consultor->email;
            $this->telefone = $consultor->telefone;
            $this->nivel = $consultor->nivel;
            $this->status = $consultor->status;
            $this->data_inicio = $consultor->data_inicio?->format('Y-m-d');
            $this->meta_mensal = $consultor->meta_mensal;
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
        ];
        
        if ($this->consultor) {
            $this->consultor->update($dados);
            $this->dispatch('notify', ['message' => 'Consultor atualizado com sucesso!']);
        } else {
            $dados['user_id'] = Auth::id();
            Consultor::create($dados);
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