<?php

namespace App\Livewire\Produtos;

use App\Models\Produtos\Produto;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProdutoEdit extends Component
{
    use WithFileUploads;

    public Produto $produto;
    public $codigo;
    public $nome;
    public $marca;
    public $descricao;
    public $preco;
    public $ativo;
    public $imagem;
    public $nova_imagem;

    protected function rules()
    {
        return [
            'codigo' => 'required|unique:produtos,codigo,' . $this->produto->id,
            'nome' => 'required|min:3',
            'marca' => 'required',
            'descricao' => 'nullable',
            'preco' => 'required|numeric|min:0',
            'ativo' => 'boolean',
            'nova_imagem' => 'nullable|image|max:1024'
        ];
    }

    public function mount(Produto $produto)
    {
        $this->produto = $produto;
        $this->codigo = $produto->codigo;
        $this->nome = $produto->nome;
        $this->marca = $produto->marca;
        $this->descricao = $produto->descricao;
        $this->preco = $produto->preco;
        $this->ativo = $produto->ativo;
        $this->imagem = $produto->imagem;
    }

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

        if ($this->nova_imagem) {
            $dados['imagem'] = $this->nova_imagem->store('produtos', 'public');
        }

        $this->produto->update($dados);

        $this->dispatch('notify', [
            'message' => 'Produto atualizado com sucesso!'
        ]);

        $this->redirect(route('produtos.index'));
    }

    public function render()
    {
        return view('livewire.produtos.produto-edit');
    }
}