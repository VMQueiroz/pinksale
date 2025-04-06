<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContatosTable extends Migration
{
    /**
     * Executa as migrations.
     */
    public function up()
    {
        Schema::create('contatos', function (Blueprint $table) {
            $table->id();
            
            // Relaciona o contato ao consultor (usuário) que é dono dos dados
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            // Dados básicos
            $table->string('nome', 150);
            $table->string('email', 100)->nullable()->unique();
            $table->string('telefone', 20)->nullable();

            // Endereço
            $table->string('cep', 9)->nullable();
            $table->string('endereco', 150)->nullable();
            $table->string('numero', 20)->nullable();
            $table->string('complemento', 100)->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('cidade', 100)->nullable();

            // Data de aniversário (somente dia e mês)
            $table->tinyInteger('dia_aniversario')->nullable();
            $table->tinyInteger('mes_aniversario')->nullable();

            // Observações (textarea)
            $table->text('observacoes')->nullable();

            // Dados específicos para clientes e consultores
            $table->string('tipo_de_pele', 50)->nullable();
            $table->string('tom_de_pele', 50)->nullable();

            // Campo específico para parceiros
            $table->string('nome_contato', 150)->nullable();

            // Armazena os papéis (por exemplo: ["abordagem", "cliente", "consultor", "parceiro"])
            $table->json('papeis')->nullable();

            // Indica se o contato está habilitado para participar do cartão fidelidade
            $table->boolean('habilitado_fidelidade')->default(false);

            // Status do registro (ativo ou não)
            $table->boolean('ativo')->default(true);

            $table->unsignedBigInteger('indicacao_id')->nullable();
            $table->string('indicacao_type')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverte as migrations.
     */
    public function down()
    {
        Schema::dropIfExists('contatos');
    }
}
