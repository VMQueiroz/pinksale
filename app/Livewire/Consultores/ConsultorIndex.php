<?php

namespace App\Livewire\Consultores;

use App\Models\Contatos\Contato;
use Livewire\Component;
use Livewire\WithPagination;

class ConsultorIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'nome';
    public $sortDirection = 'asc';
    public $filtroStatus = 'todos';
    public $filtroNivel = 'todos';
    public $consultorEmEdicao;
    public $consultorToDelete; // Adicionando a propriedade
    
    protected $listeners = [
        'consultor-saved' => 'handleConsultorSaved'
    ];

    public function handleConsultorSaved()
    {
        $this->reset('consultorEmEdicao');
    }

    public function edit($consultorId)
    {
        $this->consultorEmEdicao = Contato::find($consultorId);
        
        if ($this->consultorEmEdicao) {
            $this->dispatch('open-modal', 'editar-consultor');
        }
    }

    public function create()
    {
        $this->dispatch('open-modal', 'novo-consultor');
    }

    public function confirmDelete($id)
    {
        $this->consultorToDelete = $id;
        $this->dispatch('open-modal', 'confirmar-exclusao');
    }

    public function deleteConsultor()
    {
        $consultor = Contato::find($this->consultorToDelete);
        
        if ($consultor->vendas()->exists()) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Não é possível excluir um consultor com vendas vinculadas'
            ]);
            return;
        }

        $consultor->delete();
        $this->dispatch('notify', ['message' => 'Consultor excluído com sucesso']);
        $this->dispatch('close-modal', 'confirmar-exclusao');
    }

    public function render()
    {
        $query = Contato::query()
            ->whereJsonContains('papeis', 'consultor')
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('nome', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('telefone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filtroStatus !== 'todos', function ($query) {
                $query->where('status', $this->filtroStatus);
            })
            ->when($this->filtroNivel !== 'todos', function ($query) {
                $query->where('nivel', $this->filtroNivel);
            });

        $consultores = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.consultores.consultor-index', [
            'consultores' => $consultores,
            'totalConsultores' => Contato::whereJsonContains('papeis', 'consultor')->count(),
            'niveis' => [
                'junior' => 'Júnior',
                'pleno' => 'Pleno',
                'senior' => 'Sênior',
                'master' => 'Master',
            ],
        ]);
    }
}



