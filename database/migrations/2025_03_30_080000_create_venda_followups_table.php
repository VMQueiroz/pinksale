<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendaFollowupsTable extends Migration
{
    /**
     * Executa as migrations.
     */
    public function up()
    {
        Schema::create('venda_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venda_id')->constrained('vendas')->cascadeOnDelete();
            $table->date('data_entrega');
            $table->date('contato_2dias');
            $table->date('contato_2semanas');
            $table->date('contato_2meses');
            $table->boolean('contato_2dias_realizado')->default(false);
            $table->boolean('contato_2semanas_realizado')->default(false);
            $table->boolean('contato_2meses_realizado')->default(false);
            $table->timestamps();
        });
    }
    
    /**
     * Reverte as migrations.
     */
    public function down()
    {
        Schema::dropIfExists('venda_followups');
    }
}