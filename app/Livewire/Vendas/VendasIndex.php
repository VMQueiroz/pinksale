<?php

namespace App\Livewire\Vendas;

use Livewire\Component;
use App\Models\Vendas\Venda;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;

class VendasIndex extends Component
{
    use WithPagination;
    
    public $statusFiltro = 'TODOS';
    public $dataInicio;
    public $dataFim;
    
    // Adicionar debounce para evitar múltiplas consultas
    protected $updatesQueryString = ['statusFiltro', 'dataInicio', 'dataFim'];
    protected $queryString = ['statusFiltro', 'dataInicio', 'dataFim'];

    public function render()
    {
        return view('livewire.vendas.vendas-index', [
            'vendas' => $this->getVendas()
        ]);
    }

    private function getVendas()
    {
        return Venda::query()
            ->when($this->statusFiltro !== 'TODOS', function (Builder $query) {
                $query->where('status', $this->statusFiltro);
            })
            ->when($this->dataInicio, function (Builder $query) {
                $query->where('data_venda', '>=', $this->dataInicio);
            })
            ->when($this->dataFim, function (Builder $query) {
                $query->where('data_venda', '<=', $this->dataFim);
            })
            ->orderBy('data_venda', 'desc')
            ->with(['comprador', 'items']) // Eager loading para evitar N+1
            ->paginate(10);
    }

    // Resetar paginação quando filtros mudam
    public function updatedStatusFiltro()
    {
        $this->resetPage();
    }

    public function updatedDataInicio()
    {
        $this->resetPage();
    }

    public function updatedDataFim()
    {
        $this->resetPage();
    }
}