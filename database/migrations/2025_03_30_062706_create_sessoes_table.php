<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessoesTable extends Migration
{
    /**
     * Executa as migrations.
     */
    public function up()
    {
        Schema::create('sessoes', function (Blueprint $table) {
            $table->id();
            
            // Relaciona a sessão ao usuário (consultor) que a registrou
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            // Relaciona o anfitrião da sessão à tabela unificada de contatos
            $table->foreignId('anfitriao_id')->constrained('contatos')->cascadeOnDelete();
            
            // Campos de snapshot do anfitrião (opcionais) para preservar os dados no momento do cadastro
            $table->string('anfitriao_nome_snapshot', 150)->nullable();
            $table->string('anfitriao_email_snapshot', 100)->nullable();
            $table->string('anfitriao_telefone_snapshot', 20)->nullable();
            $table->string('anfitriao_endereco_snapshot', 150)->nullable();
            $table->string('anfitriao_numero_snapshot', 20)->nullable();
            $table->string('anfitriao_complemento_snapshot', 100)->nullable();
            $table->string('anfitriao_estado_snapshot', 2)->nullable();
            $table->string('anfitriao_cidade_snapshot', 100)->nullable();
            
            // Dados da Sessão
            $table->date('data_sessao');
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fim')->nullable();
            $table->integer('numero_participantes')->nullable();

            // Indicadores – poderão ser atualizados posteriormente
            $table->decimal('valor_vendas', 10, 2)->default(0); // Valor total de vendas originárias da sessão
            $table->integer('indicacoes')->default(0);          // Quantidade de abordagens indicadas pela sessão
            $table->integer('inicios_efetivos')->default(0);      // Quantidade de inícios efetivos originados pela sessão

            // Observações (textarea)
            $table->text('observacoes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverte as migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sessoes');
    }
}
