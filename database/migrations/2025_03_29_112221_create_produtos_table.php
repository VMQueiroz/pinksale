<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Executa as migrations.
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            
            // Campo para indicar a origem do produto: se for cadastrado pelo usuário, terá o ID do usuário; caso contrário, ficará como null
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();

            // Código principal do produto
            $table->string('codigo', 50)->nullable();
            // Códigos alternativos armazenados em formato JSON (pode armazenar um array de códigos)
            $table->json('codigos_alternativos')->nullable();

            $table->string('nome', 150); // Nome do produto
            $table->string('marca', 50)->default('Mary Kay'); // Marca, padrão Mary Kay
            $table->decimal('preco', 10, 2); // Preço do produto

            // Campos para granularidade
            $table->string('categoria', 100)->nullable();
            $table->string('subcategoria', 100)->nullable();
            $table->string('tonalidade', 50)->nullable();

            // Campo para indicar se o produto é global ou não
            $table->boolean('eh_global')->default(true);
            
            // Campo para foto do produto (armazenar caminho ou URL)
            $table->string('foto', 255)->nullable();

            // Campo para descrição do produto
            $table->text('descricao')->nullable();

            // Campo para indicar se é produto normal ou kit
            $table->boolean('eh_kit')->default(false);

            // Campo para indicar se o produto está ativo (padrão ativo)
            $table->boolean('ativo')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverte as migrations.
     */
    public function down()
    {
        Schema::dropIfExists('produtos');
    }
}
