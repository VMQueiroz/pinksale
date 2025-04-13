<?php

namespace App\Livewire\Abordagens;

use Livewire\Component;
use App\Models\Contatos\Contato;
use Livewire\WithPagination;

class AbordagemIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'nome';
    public $sortDirection = 'asc';
    public $filtroTipo = 'todos';
    public $abordagemEmEdicao;
    
    protected $listeners = [
        'abordagem-saved' => 'handleAbordagemSaved',
        'close-modal' => 'handleCloseModal'
    ];

    public function handleAbordagemSaved()
    {
        // Reset após salvar
    }

    public function handleCloseModal()
    {
        $this->reset('abordagemEmEdicao');
    }

    public function edit($abordagemId)
    {
        $this->abordagemEmEdicao = Contato::find($abordagemId);
        
        if ($this->abordagemEmEdicao) {
            $this->dispatch('open-modal', 'editar-abordagem');
        }
    }

    public function create()
    {
        $this->dispatch('open-modal', 'nova-abordagem');
    }

    public function delete($abordagemId)
    {
        $abordagem = Contato::find($abordagemId);
        
        if ($abordagem) {
            try {
                $abordagem->delete();
                $this->dispatch('notify', type: 'success', message: 'Abordagem excluída com sucesso!');
            } catch (\Exception $e) {
                $this->dispatch('notify', type: 'error', message: 'Erro ao excluir abordagem: ' . $e->getMessage());
            }
        }
    }

    public function render()
    {
        $query = Contato::query()
            ->whereJsonContains('papeis', 'abordagem')
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('nome', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('telefone', 'like', '%' . $this->search . '%');
                });
            })->when($this->filtroTipo !== 'todos', function ($query) {
                $query->where('tipo_abordagem', $this->filtroTipo);
            });

        $abordagens = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.abordagens.abordagem-index', [
            'abordagens' => $abordagens,
            'totalAbordagens' => Contato::whereJsonContains('papeis', 'abordagem')->count(),
            'totalInicios' => Contato::whereJsonContains('papeis', 'abordagem')->where('tipo_abordagem', 'inicio')->count(),
            'totalClientes' => Contato::whereJsonContains('papeis', 'abordagem')->where('tipo_abordagem', 'cliente')->count(),
        ]);
    }
}
