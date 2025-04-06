<?php

namespace App\Livewire\Produtos;

use App\Models\Produtos\Produto;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProdutoCreate extends Component
{
    use WithFileUploads;

    public $codigo;
    public $nome;
    public $marca;
    public $descricao;
    public $preco;
    public $ativo = true;
    public $imagem;

    protected $rules = [
        'codigo' => 'required|unique:produtos,codigo',
        'nome' => 'required|min:3',
        'marca' => 'required',
        'descricao' => 'nullable',
        'preco' => 'required|numeric|min:0',
        'ativo' => 'boolean',
        'imagem' => 'nullable|image|max:1024'
    ];

    public function save()
    {
        $this->validate();

        $dados = [
            'codigo' => $this->codigo,
            'nome' => $this->nome,
            'marca' => $this->marca,
            'descricao' => $this->descricao,
            'preco' => $this->preco,
            'ativo' => $this->ativo
        ];

        if ($this->imagem) {
            $dados['imagem'] = $this->imagem->store('produtos', 'public');
        }

        Produto::create($dados);

        $this->dispatch('notify', [
            'message' => 'Produto criado com sucesso!'
        ]);

        $this->redirect(route('produtos.index'));
    }

    public function render()
    {
        return view('livewire.produtos.produto-create');
    }
}