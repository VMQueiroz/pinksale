<?php

use App\Livewire\Vendas\VendasIndex;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Agenda\AgendaIndex;
use App\Livewire\Clientes\ClienteIndex;
use App\Livewire\Produtos\ProdutoIndex;
use App\Livewire\Parceiros\ParceiroIndex;
use App\Livewire\Dashboard\DashboardIndex;
use App\Livewire\Consultores\ConsultorIndex;
use App\Livewire\Abordagens\AbordagemIndex;

// Rota pública
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rotas autenticadas
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');

    // Clientes
    Route::prefix('clientes')->name('clientes.')->group(function () {
        Route::get('/', ClienteIndex::class)->name('index');
    });

    // Consultores
    Route::prefix('consultores')->name('consultores.')->group(function () {
        Route::get('/', ConsultorIndex::class)->name('index');
    });

    // Parceiros
    Route::prefix('parceiros')->name('parceiros.')->group(function () {
        Route::get('/', ParceiroIndex::class)->name('index');
    });

    // Produtos e Estoque
    Route::prefix('produtos')->name('produtos.')->group(function () {
        Route::get('/', ProdutoIndex::class)->name('index');
    });

    // Vendas
    Route::prefix('vendas')->name('vendas.')->group(function () {
        Route::get('/', VendasIndex::class)->name('index');
    });

    // Abordagens
    Route::prefix('abordagens')->name('abordagens.')->group(function () {
        Route::get('/', AbordagemIndex::class)->name('index');
    });

    // Teste de Entrevista
    Route::get('/teste-entrevista', App\Livewire\Agenda\TesteEntrevista::class)->name('teste-entrevista');

    // Agenda
    Route::prefix('agenda')->name('agenda.')->group(function () {
        Route::get('/', AgendaIndex::class)->name('index');

        // API para eventos do calendário
        Route::get('/api/eventos', function (\Illuminate\Http\Request $request) {
            $user = Auth::user();

            $start = $request->input('start');
            $end = $request->input('end');
            $tipoEvento = $request->input('tipoEvento');
            $search = $request->input('search');

            $query = \App\Models\Agenda\Agenda::query()
                ->where('user_id', $user->id)
                ->when($start, function ($query) use ($start) {
                    $query->whereDate('data_evento', '>=', $start);
                })
                ->when($end, function ($query) use ($end) {
                    $query->whereDate('data_evento', '<=', $end);
                })
                ->when($tipoEvento, function ($query) use ($tipoEvento) {
                    $query->where('tipo_evento', $tipoEvento);
                })
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('titulo', 'like', '%' . $search . '%')
                            ->orWhere('descricao', 'like', '%' . $search . '%')
                            ->orWhere('local', 'like', '%' . $search . '%');
                    });
                })
                ->with('contato');

            $eventos = $query->get()->map(function ($evento) {
                return [
                    'id' => $evento->id,
                    'title' => $evento->titulo,
                    'start' => $evento->data_evento->format('Y-m-d') .
                        ($evento->hora_inicio ? 'T' . $evento->hora_inicio : ''),
                    'end' => $evento->data_evento->format('Y-m-d') .
                        ($evento->hora_fim ? 'T' . $evento->hora_fim :
                        ($evento->hora_inicio ? 'T' . \Carbon\Carbon::parse($evento->hora_inicio)->addHour()->format('H:i:s') : '')),
                    'tipoEvento' => $evento->tipo_evento,
                    'status' => $evento->status,
                    'local' => $evento->local,
                    'contato' => $evento->contato ? $evento->contato->nome : null,
                    'backgroundColor' => getEventColor($evento->tipo_evento, $evento->status),
                    'borderColor' => getEventBorderColor($evento->tipo_evento, $evento->status),
                    'textColor' => '#ffffff',
                ];
            });

            return response()->json($eventos);
        })->name('api.eventos');
    });

    // Funções auxiliares para cores dos eventos
    function getEventColor($tipo, $status) {
        if ($status === 'cancelado') {
            return '#ef4444'; // Vermelho
        }

        if ($status === 'realizado') {
            return '#10b981'; // Verde
        }

        switch ($tipo) {
            case 'entrevista':
                return '#3b82f6'; // Azul
            case 'sessao':
                return '#8b5cf6'; // Roxo
            default:
                return '#6b7280'; // Cinza
        }
    }

    function getEventBorderColor($tipo, $status) {
        if ($status === 'cancelado') {
            return '#dc2626'; // Vermelho escuro
        }

        if ($status === 'realizado') {
            return '#059669'; // Verde escuro
        }

        switch ($tipo) {
            case 'entrevista':
                return '#2563eb'; // Azul escuro
            case 'sessao':
                return '#7c3aed'; // Roxo escuro
            default:
                return '#4b5563'; // Cinza escuro
        }
    }
});
