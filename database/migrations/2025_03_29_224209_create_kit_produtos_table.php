<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKitProdutosTable extends Migration
{
    /**
     * Executa as migrations.
     */
    public function up()
    {
        Schema::create('kit_produtos', function (Blueprint $table) {
            $table->id();
            // O kit é um produto na tabela 'produtos'
            $table->foreignId('kit_id')->constrained('produtos')->cascadeOnDelete();
            // O produto que compõe o kit
            $table->foreignId('produto_id')->constrained('produtos')->cascadeOnDelete();
            // Quantidade do produto necessária para formar o kit
            $table->integer('quantidade')->default(1);
            $table->timestamps();
            
            // Garante que não haja duplicidade para o mesmo kit e produto
            $table->unique(['kit_id', 'produto_id']);
        });
    }

    /**
     * Reverte as migrations.
     */
    public function down()
    {
        Schema::dropIfExists('kit_produtos');
    }
}
