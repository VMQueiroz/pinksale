<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Vendas\Venda;
use App\Models\Sessoes\Sessao;
use App\Models\Contatos\Contato;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardIndex extends Component
{
    public function render()
    {
        return view('livewire.dashboard.dashboard-index', [
            'totalVendas' => $this->getTotalVendas(),
            'totalSessoes' => $this->getTotalSessoes(),
            'totalContatos' => $this->getTotalContatos(),
        ]);
    }

    private function getTotalVendas()
    {
        return Cache::remember('dashboard_vendas_' . Carbon::now()->format('Y-m'), 3600, function () {
            return Venda::whereMonth('data_venda', Carbon::now()->month)
                ->whereYear('data_venda', Carbon::now()->year)
                ->sum('valor_total');
        });
    }

    private function getTotalSessoes()
    {
        return Cache::remember('dashboard_sessoes_' . Carbon::now()->format('Y-m'), 3600, function () {
            return Sessao::whereMonth('data_sessao', Carbon::now()->month)
                ->whereYear('data_sessao', Carbon::now()->year)
                ->count();
        });
    }

    private function getTotalContatos()
    {
        return Cache::remember('dashboard_contatos', 3600, function () {
            return Contato::whereJsonContains('papeis', 'cliente')->count();
        });
    }

    private function getContagemContatos()
    {
        return Cache::remember('dashboard_contatos_detalhado', 3600, function () {
            return [
                'total' => Contato::count(),
                'clientes' => Contato::whereJsonContains('papeis', 'cliente')->count(),
                'consultores' => Contato::whereJsonContains('papeis', 'consultor')->count(),
                'parceiros' => Contato::whereJsonContains('papeis', 'parceiro')->count(),
                'abordagens' => Contato::whereJsonContains('papeis', 'abordagem')->count(),
            ];
        });
    }
}
