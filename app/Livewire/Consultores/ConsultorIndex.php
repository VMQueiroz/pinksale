<?php

namespace App\Livewire\Consultores;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Contatos\Contato;

class ConsultorIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'nome';
    public $sortDirection = 'asc';
    public $filtroStatus = 'todos';
    public $consultorEmEdicao;
    
    protected $listeners = [
        'consultor-saved' => 'handleConsultorSaved',
        'close-modal' => 'handleCloseModal'
    ];

    public function handleConsultorSaved()
    {
        //$this->reset('consultorEmEdicao');
    }

    public function handleCloseModal()
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortField === $field 
            ? $this->sortDirection === 'asc' ? 'desc' : 'asc'
            : 'asc';

        $this->sortField = $field;
    }

    public function delete($consultorId)
    {
        $consultor = Contato::find($consultorId);
        if ($consultor) {
            if ($consultor->vendasComoConsultor()->exists()) {
                $this->dispatch('notify', type: 'error', message: 'Não é possível excluir um consultor com vendas vinculadas!');
                return;
            }

            $consultor->delete();
            $this->dispatch('notify', type: 'success', message: 'Consultor excluído com sucesso!');
        }
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
            })->when($this->filtroStatus !== 'todos', function ($query) {
                $query->where('iniciado_por_mim', $this->filtroStatus === 'inicios');
            });

        $consultores = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.consultores.consultor-index', [
            'consultores' => $consultores,
            'totalConsultores' => Contato::whereJsonContains('papeis', 'consultor')->count(),
            'totalInicios' => Contato::whereJsonContains('papeis', 'consultor')->where('iniciado_por_mim', true)->count(),
            'totalNormais' => Contato::whereJsonContains('papeis', 'consultor')->where('iniciado_por_mim', false)->count(),
        ]);
    }
}