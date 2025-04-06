<?php

namespace App\Livewire\Consultores;

use App\Models\Consultores\Consultor;
use Livewire\Component;
use Livewire\WithPagination;

class ConsultorList extends Component
{
    use WithPagination;

    public $search = '';
    public $nivel = '';
    public $status = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'nivel' => ['except' => ''],
        'status' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Consultor::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('nome', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('telefone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->nivel, function ($query) {
                $query->where('nivel', $this->nivel);
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->orderBy('nome');

        return view('livewire.consultores.consultor-list', [
            'consultores' => $query->paginate($this->perPage),
            'niveis' => [
                'junior' => 'Júnior',
                'pleno' => 'Pleno',
                'senior' => 'Sênior',
                'master' => 'Master',
            ],
            'status_list' => [
                'ativo' => 'Ativo',
                'inativo' => 'Inativo',
                'suspenso' => 'Suspenso',
                'em_treinamento' => 'Em Treinamento',
            ],
        ]);
    }
}