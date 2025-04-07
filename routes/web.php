<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Produtos\ProdutoIndex;
use App\Livewire\Dashboard\DashboardIndex;
use App\Livewire\Clientes\ClienteIndex;
use App\Livewire\Consultores\ConsultorIndex;
use App\Livewire\Vendas\VendasIndex;

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

    // Produtos e Estoque
    Route::prefix('produtos')->name('produtos.')->group(function () {
        Route::get('/', ProdutoIndex::class)->name('index');
    });

    // Vendas
    Route::prefix('vendas')->name('vendas.')->group(function () {
        Route::get('/', VendasIndex::class)->name('index');
    });
});