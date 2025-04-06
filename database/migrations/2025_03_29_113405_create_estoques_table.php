<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstoquesTable extends Migration
{
    /**
     * Executa as migrations.
     */
    public function up()
    {
        Schema::create('estoques', function (Blueprint $table) {
            $table->id();
            // Relaciona o estoque ao consultor (usuário)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // Relaciona o estoque ao produto do catálogo global
            $table->foreignId('produto_id')->constrained('produtos')->cascadeOnDelete();
            // Quantidade disponível para o consultor
            $table->integer('quantidade')->default(0);
            $table->timestamps();

            // Garante que para cada consultor, cada produto apareça apenas uma vez
            $table->unique(['user_id', 'produto_id']);
        });
    }

    /**
     * Reverte as migrations.
     */
    public function down()
    {
        Schema::dropIfExists('estoques');
    }
}
