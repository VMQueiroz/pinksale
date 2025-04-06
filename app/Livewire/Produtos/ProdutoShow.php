<?php

namespace App\Livewire\Produtos;

use App\Models\Produtos\Produto;
use Livewire\Component;

class ProdutoShow extends Component
{
    public Produto $produto;

    public function mount(Produto $produto)
    {
        $this->produto = $produto;
    }

    public function render()
    {
        return view('livewire.produtos.produto-show');
    }
}