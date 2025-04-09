<?php

namespace App\Livewire\Clientes;

use Livewire\Component;
use App\Models\Contatos\Contato;
use Livewire\WithPagination;

class ClienteIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'nome';
    public $sortDirection = 'asc';
    public $filtroStatus = 'todos';
    public $clienteEmEdicao;

    public ?Contato $contato = null;

    // Campos básicos
    public $nome = '';
    public $email = '';
    public $telefone = '';
    public $cep = '';
    public $endereco = '';
    public $numero = '';
    public $complemento = '';
    public $estado = '';
    public $cidade = '';
    public $dia_aniversario = '';
    public $mes_aniversario = '';
    public $observacoes = '';
    
    // Campos específicos de cliente
    public $tipo_de_pele = '';
    public $tom_de_pele = '';
    public $habilitado_fidelidade = true;
    public $ativo = true;
    
    protected $listeners = [
        'cliente-saved' => 'handleClienteSaved',
        'close-modal' => 'handleCloseModal'
    ];

    public function handleClienteSaved()
    {
        //$this->reset('clienteEmEdicao');
    }

    public function handleCloseModal()
    {
        $this->reset('clienteEmEdicao');
    }

    public function edit($clienteId)
    {
        $this->clienteEmEdicao = Contato::find($clienteId);
        
        if ($this->clienteEmEdicao) {
            $this->dispatch('open-modal', 'editar-cliente');
        }
    }

    public function create()
    {
        $this->dispatch('open-modal', 'novo-cliente');
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

    public function delete($clienteId)
    {
        $cliente = Contato::find($clienteId);
        if ($cliente) {
            if ($cliente->vendasComoCliente()->exists()) {
                $this->dispatch('notify', type: 'error', message: 'Não é possível excluir um cliente com vendas vinculadas!');
                return;
            }

            $cliente->delete();
            $this->dispatch('notify', type: 'success', message: 'Cliente excluído com sucesso!');
        }
    }

    public function render()
    {
        $query = Contato::query()
            ->whereJsonContains('papeis', 'cliente')
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('nome', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('telefone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filtroStatus !== 'todos', function ($query) {
                $query->where('ativo', $this->filtroStatus === 'ativos');
            });

        $clientes = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.clientes.cliente-index', [
            'clientes' => $clientes,
            'totalClientes' => Contato::whereJsonContains('papeis', 'cliente')->count(),
            'totalAtivos' => Contato::whereJsonContains('papeis', 'cliente')->where('ativo', true)->count(),
            'totalInativos' => Contato::whereJsonContains('papeis', 'cliente')->where('ativo', false)->count(),
        ]);
    }
}
