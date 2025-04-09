<?php

namespace App\Livewire\Parceiros;

use App\Models\Contatos\Contato;
use Livewire\Component;
use Livewire\WithPagination;

class ParceiroIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'nome';
    public $sortDirection = 'asc';
    public $parceiroEmEdicao;
    
    protected $listeners = [
        'parceiro-saved' => 'handleParceiroSaved',
        'close-modal' => 'handleCloseModal'
    ];

    public function handleParceiroSaved()
    {
        //$this->reset('parceiroEmEdicao');
    }

    public function handleCloseModal()
    {
        $this->reset('parceiroEmEdicao');
    }

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortField === $field 
            ? $this->sortDirection === 'asc' ? 'desc' : 'asc'
            : 'asc';

        $this->sortField = $field;
    }

    public function edit($parceiroId)
    {
        $this->parceiroEmEdicao = Contato::find($parceiroId);
        
        if ($this->parceiroEmEdicao) {
            $this->dispatch('open-modal', 'editar-parceiro');
        }
    }

    public function create()
    {
        $this->dispatch('open-modal', 'novo-parceiro');
    }

    public function delete($id)
    {
        $parceiro = Contato::find($id);
        if ($parceiro) {
            $parceiro->delete();
            $this->dispatch('notify', type: 'success', message: 'Parceiro excluído com sucesso!');
        }
    }

    public function render()
    {
        $query = Contato::query()
            ->where('papeis', 'like', '%parceiro%')
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('nome', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('telefone', 'like', '%' . $this->search . '%')
                        ->orWhere('nome_contato', 'like', '%' . $this->search . '%');
                });
            });

        $parceiros = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.parceiros.parceiro-index', [
            'parceiros' => $parceiros,
        ]);
    }
}
