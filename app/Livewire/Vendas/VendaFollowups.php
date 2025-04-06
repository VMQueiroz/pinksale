<?php

namespace App\Livewire\Vendas;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Vendas\VendaFollowup;
use Illuminate\Support\Facades\DB;

class VendaFollowups extends Component
{
    public $filtroTipo = 'todos'; // todos, 2dias, 2semanas, 2meses

    public function render()
    {
        $query = VendaFollowup::query()
            ->with([
                'venda.comprador',
                'venda.itens.produto'
            ]);

        // Filtra apenas followups pendentes e com data de contato menor ou igual a hoje
        $query->where(function($q) {
            $hoje = Carbon::today();
            
            $q->where(function($q) use ($hoje) {
                $q->where('contato_2dias_realizado', false)
                    ->where('contato_2dias', '<=', $hoje);
            })
            ->orWhere(function($q) use ($hoje) {
                $q->where('contato_2semanas_realizado', false)
                    ->where('contato_2semanas', '<=', $hoje);
            })
            ->orWhere(function($q) use ($hoje) {
                $q->where('contato_2meses_realizado', false)
                    ->where('contato_2meses', '<=', $hoje);
            });
        });

        // Aplica filtro específico se selecionado
        if ($this->filtroTipo !== 'todos') {
            $coluna = 'contato_' . $this->filtroTipo . '_realizado';
            $data = 'contato_' . $this->filtroTipo;
            
            $query->where($coluna, false)
                  ->where($data, '<=', Carbon::today());
        }

        // Agrupa por cliente e ordena por data mais antiga primeiro
        $followups = $query->orderBy('data_entrega')
            ->get()
            ->groupBy('venda.comprador_id');

        return view('livewire.vendas.followups', [
            'followups' => $followups,
            'hoje' => Carbon::today()
        ]);
    }

    public function marcarContatoRealizado($followupId, $tipo)
    {
        try {
            DB::beginTransaction();

            $followup = VendaFollowup::findOrFail($followupId);
            
            // Valida o tipo de contato
            $tiposValidos = ['contato_2dias', 'contato_2semanas', 'contato_2meses'];
            if (!in_array($tipo, $tiposValidos)) {
                throw new \Exception('Tipo de contato inválido');
            }

            $followup->update([
                $tipo . '_realizado' => true,
                'updated_at' => now()
            ]);

            DB::commit();

            $this->dispatch('notify', [
                'message' => 'Contato marcado como realizado com sucesso!',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->dispatch('notify', [
                'message' => 'Erro ao marcar contato como realizado',
                'type' => 'error'
            ]);
        }
    }

    public function getStatusContato($followup, $tipo)
    {
        $dataContato = $followup->{$tipo};
        $realizado = $followup->{$tipo . '_realizado'};
        $hoje = Carbon::today();

        if ($realizado) {
            return [
                'status' => 'realizado',
                'classe' => 'bg-green-100 text-green-800'
            ];
        }

        if ($dataContato->lte($hoje)) {
            return [
                'status' => 'pendente',
                'classe' => 'bg-red-100 text-red-800'
            ];
        }

        return [
            'status' => 'agendado',
            'classe' => 'bg-gray-100 text-gray-800'
        ];
    }
}
