<?php

namespace App\Livewire\Vendas;

use Livewire\Component;
use App\Models\Vendas\Venda;
use App\Models\Vendas\VendaItem;
use App\Models\Produtos\Produto;
use Livewire\Attributes\Rule;

class VendaForm extends Component
{
    #[Rule('required')]
    public $comprador_id;
    
    #[Rule('nullable')]
    public $consultor_id;
    
    #[Rule('nullable')]
    public $parceiro_id;
    
    #[Rule('nullable')]
    public $sessao_id;
    
    public $status = 'EM_ABERTO';
    public $items = [];
    public $total = 0;
    public $desconto = 0;
    
    public function addItem()
    {
        $this->items[] = [
            'produto_id' => '',
            'quantidade' => 1,
            'preco_unitario' => 0,
            'desconto' => 0,
            'subtotal' => 0
        ];
    }
    
    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotal();
    }
    
    public function calculateTotal()
    {
        $this->total = collect($this->items)->sum('subtotal') - $this->desconto;
    }
    
    public function save()
    {
        $this->validate();
        
        $venda = Venda::create([
            'comprador_id' => $this->comprador_id,
            'consultor_id' => $this->consultor_id,
            'parceiro_id' => $this->parceiro_id,
            'sessao_id' => $this->sessao_id,
            'status' => $this->status,
            'total' => $this->total,
            'desconto' => $this->desconto
        ]);
        
        foreach ($this->items as $item) {
            VendaItem::create([
                'venda_id' => $venda->id,
                'produto_id' => $item['produto_id'],
                'quantidade' => $item['quantidade'],
                'preco_unitario' => $item['preco_unitario'],
                'desconto' => $item['desconto'],
                'subtotal' => $item['subtotal']
            ]);
            
            // Deduz do estoque
            $produto = Produto::find($item['produto_id']);
            $produto->deductFromStock($item['quantidade']);
        }
        
        $this->dispatch('venda-saved');
    }
    
    public function render()
    {
        return view('livewire.vendas.venda-form');
    }
}

