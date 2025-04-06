<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrnasTable extends Migration
{
    /**
     * Executa as migrations.
     */
    public function up()
    {
        Schema::create('urnas', function (Blueprint $table) {
            $table->id();
            
            // Parceiro, que agora é um contato na tabela 'contatos'
            $table->foreignId('parceiro_id')->constrained('contatos')->cascadeOnDelete();
            
            // Snapshot dos dados do parceiro selecionado
            $table->string('parceiro_nome_snapshot', 150)->nullable();
            // Campo adicional para o Nome de Contato, exclusivo para parceiros
            $table->string('parceiro_nome_contato_snapshot', 150)->nullable();
            $table->string('parceiro_email_snapshot', 100)->nullable();
            $table->string('parceiro_telefone_snapshot', 20)->nullable();
            $table->string('parceiro_cep_snapshot', 9)->nullable();
            $table->string('parceiro_endereco_snapshot', 150)->nullable();
            $table->string('parceiro_numero_snapshot', 20)->nullable();
            $table->string('parceiro_complemento_snapshot', 100)->nullable();
            $table->string('parceiro_estado_snapshot', 2)->nullable();
            $table->string('parceiro_cidade_snapshot', 100)->nullable();
            
            // Datas
            $table->date('data_inicio')->nullable();          // Data de cadastro/início da urna
            $table->date('data_retirar_em')->nullable();        // Data prevista para retirar a urna
            $table->date('data_retirada')->nullable();          // Data efetiva em que a urna foi retirada
            
            // Status da urna: por exemplo, 'ATIVA' ou 'RETIRADA'
            $table->string('status', 20)->default('ATIVA');
            
            // Campo para observações (textarea)
            $table->text('observacoes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverte as migrations.
     */
    public function down()
    {
        Schema::dropIfExists('urnas');
    }
}
