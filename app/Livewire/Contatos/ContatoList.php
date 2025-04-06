<?php

namespace App\Livewire\Contatos;

use App\Models\Contatos\Contato;
use Livewire\Component;
use Livewire\WithPagination;

class ContatoList extends Component
{
    use WithPagination;

    public $search = '';
    public $papel = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'papel' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Contato::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('nome', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('telefone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->papel, function ($query) {
                $query->whereJsonContains('papeis', $this->papel);
            })
            ->orderBy('nome');

        return view('livewire.contatos.contato-list', [
            'contatos' => $query->paginate($this->perPage),
            'papeis_disponiveis' => [
                'abordagem' => 'Abordagem',
                'cliente' => 'Cliente',
                'consultor' => 'Consultor',
                'parceiro' => 'Parceiro',
            ],
        ]);
    }

    public function delete(Contato $contato)
    {
        $contato->delete();
        $this->dispatch('notify', ['message' => 'Contato excluído com sucesso!']);
    }
}