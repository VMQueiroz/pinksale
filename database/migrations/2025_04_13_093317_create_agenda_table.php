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
        Schema::create('agenda', function (Blueprint $table) {
            $table->id();

            // Usuário que criou o evento
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Tipo de evento (entrevista, sessão, urna, aniversario, etc)
            $table->string('tipo_evento');

            // Referência para o registro original (ID da entrevista, sessão, etc)
            $table->unsignedBigInteger('referencia_id')->nullable();
            $table->string('referencia_tipo')->nullable(); // Para polimorfismo

            // Dados do evento
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->date('data_evento');
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fim')->nullable();
            $table->string('local')->nullable();
            $table->string('status')->default('pendente'); // pendente, realizado, cancelado

            // Contato relacionado (cliente, consultor, parceiro, etc)
            $table->foreignId('contato_id')->nullable()->constrained('contatos')->nullOnDelete();

            $table->timestamps();

            // Índices
            $table->index(['tipo_evento', 'data_evento']);
            $table->index(['referencia_id', 'referencia_tipo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda');
    }
};
