<?php

namespace App\Livewire\Produtos;

use App\Models\Produtos\Produto;
use Livewire\Component;
use Livewire\WithPagination;

class ProdutoIndex extends Component
{
    use WithPagination;

    // Filtros e Ordenação
    public $search = '';
    public $perPage = 10;
    public $sortField = 'nome';
    public $sortDirection = 'asc';
    public $filtroStatus = 'todos';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortField === $field 
            ? $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc'
            : 'asc';

        $this->sortField = $field;
    }

    public function render()
    {
        $query = Produto::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nome', 'like', '%' . $this->search . '%')
                      ->orWhere('codigo', 'like', '%' . $this->search . '%')
                      ->orWhere('marca', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filtroStatus !== 'todos', function ($query) {
                $query->where('ativo', $this->filtroStatus === 'ativos');
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.produtos.produto-index', [
            'produtos' => $query->paginate($this->perPage)
        ]);
    }

    public function delete($id)
    {
        $produto = Produto::findOrFail($id);
        $produto->delete();

        $this->dispatch('notify', [
            'message' => 'Produto removido com sucesso!'
        ]);
    }
}