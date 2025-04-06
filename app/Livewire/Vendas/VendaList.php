<?php

namespace App\Livewire\Vendas;

use App\Models\Vendas\Venda;
use Livewire\Component;
use Livewire\WithPagination;

class VendaList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public $selectedItems = [];
    
    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc']
    ];

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortField === $field 
            ? $this->sortDirection === 'asc' ? 'desc' : 'asc'
            : 'asc';

        $this->sortField = $field;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function deleteSelected()
    {
        Venda::whereIn('id', $this->selectedItems)->delete();
        $this->selectedItems = [];
        $this->dispatch('vendas-deleted');
    }

    public function render()
    {
        $vendas = Venda::query()
            ->when($this->search, function ($query) {
                $query->where('codigo', 'like', '%' . $this->search . '%')
                    ->orWhereHas('cliente', function ($q) {
                        $q->where('nome', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.vendas.venda-list', [
            'vendas' => $vendas
        ]);
    }
}