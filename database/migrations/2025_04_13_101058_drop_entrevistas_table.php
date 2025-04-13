<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remover a tabela entrevistas, pois agora usaremos apenas a tabela agenda
        Schema::dropIfExists('entrevistas');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recriar a tabela entrevistas se precisarmos reverter
        Schema::create('entrevistas', function (Blueprint $table) {
            $table->id();

            // Relacionamentos
            $table->foreignId('abordagem_id')->constrained('contatos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Usuário que criou a entrevista

            // Dados da entrevista
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->date('data_entrevista');
            $table->time('hora_inicio');
            $table->time('hora_fim')->nullable();
            $table->string('local')->nullable();
            $table->string('status')->default('pendente'); // pendente, realizada, cancelada

            // Resultados da entrevista
            $table->boolean('convertido_consultor')->default(false);
            $table->text('observacoes_resultado')->nullable();
            $table->date('data_realizacao')->nullable(); // Data em que a entrevista foi efetivamente realizada

            $table->timestamps();
        });
    }
};
