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
    public $filtroStatus = 'ativos';
    public $abordagemEmEdicao;
    public $abordagemParaEntrevista;

    protected $listeners = [
        'abordagem-saved' => 'handleAbordagemSaved',
        'entrevista-saved' => 'handleEntrevistaSaved',
        'abordagem-transferida' => 'handleAbordagemTransferida',
        'close-modal' => 'handleCloseModal',
        'abrir-modal-entrevista' => 'abrirModalEntrevista'
    ];

    public function handleAbordagemSaved()
    {
        // Reset após salvar
    }

    public function handleCloseModal()
    {
        $this->reset(['abordagemEmEdicao', 'abordagemParaEntrevista']);
    }

    public function handleEntrevistaSaved()
    {
        // Atualizar a lista após salvar uma entrevista
    }

    public function handleAbordagemTransferida()
    {
        // Atualizar a lista após transferir uma abordagem
    }

    public function abrirModalEntrevista($abordagemId)
    {
        $this->abordagemParaEntrevista = Contato::find($abordagemId);

        if (!$this->abordagemParaEntrevista) {
            $this->dispatch('notify', type: 'error', message: 'Abordagem não encontrada.');
        }

        // Não precisamos disparar o evento open-modal aqui, pois o Alpine.js já está fazendo isso no botão
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

    public function criarEntrevista($abordagemId)
    {
        $this->abrirModalEntrevista($abordagemId);
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
            })
            ->when($this->filtroTipo !== 'todos', function ($query) {
                $query->where('tipo_abordagem', $this->filtroTipo);
            })
            ->when($this->filtroStatus === 'ativos', function ($query) {
                $query->whereNull('convertido_de'); // Apenas abordagens não convertidas
            })
            ->when($this->filtroStatus === 'convertidos', function ($query) {
                $query->whereNotNull('convertido_de'); // Apenas abordagens convertidas
            });

        $abordagens = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Estatísticas
        $totalAbordagens = Contato::whereJsonContains('papeis', 'abordagem')->count();
        $totalInicios = Contato::whereJsonContains('papeis', 'abordagem')->where('tipo_abordagem', 'inicio')->count();
        $totalClientes = Contato::whereJsonContains('papeis', 'abordagem')->where('tipo_abordagem', 'cliente')->count();

        // Estatísticas de conversão
        $totalConvertidos = Contato::where('convertido_de', 'abordagem')->count();
        $totalConvertidosConsultor = Contato::where('convertido_de', 'abordagem')
            ->whereJsonContains('papeis', 'consultor')
            ->count();
        $totalConvertidosCliente = Contato::where('convertido_de', 'abordagem')
            ->whereJsonContains('papeis', 'cliente')
            ->count();

        return view('livewire.abordagens.abordagem-index', [
            'abordagens' => $abordagens,
            'totalAbordagens' => $totalAbordagens,
            'totalInicios' => $totalInicios,
            'totalClientes' => $totalClientes,
            'totalConvertidos' => $totalConvertidos,
            'totalConvertidosConsultor' => $totalConvertidosConsultor,
            'totalConvertidosCliente' => $totalConvertidosCliente,
        ]);
    }
}
