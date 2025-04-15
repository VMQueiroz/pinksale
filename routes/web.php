<?php

use App\Livewire\Vendas\VendasIndex;
use Illuminate\Support\Facades\Route;
use App\Livewire\Agenda\AgendaIndex;
use App\Livewire\Clientes\ClienteIndex;
use App\Livewire\Produtos\ProdutoIndex;
use App\Livewire\Parceiros\ParceiroIndex;
use App\Livewire\Dashboard\DashboardIndex;
use App\Livewire\Consultores\ConsultorIndex;
use App\Livewire\Abordagens\AbordagemIndex;
use App\Http\Controllers\Api\AgendaController;

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

    // Agenda
    Route::prefix('agenda')->name('agenda.')->group(function () {
        Route::get('/', AgendaIndex::class)->name('index');
        Route::get('/api/eventos', [AgendaController::class, 'getEventos'])->name('api.eventos');
        Route::post('/api/eventos/atualizar', [AgendaController::class, 'atualizarEvento'])->name('api.eventos.atualizar');
    });

    // As funções auxiliares para cores dos eventos foram movidas para App\Services\EventColorService
});
