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
        Schema::table('contatos', function (Blueprint $table) {
            $table->string('tipo_abordagem')->nullable()->comment('Tipo de abordagem: cliente ou inicio');
            $table->string('indicado_por')->nullable()->comment('Referência a quem indicou a abordagem');
            $table->date('data_retorno')->nullable()->comment('Data para retornar contato');
            $table->date('ultimo_contato')->nullable()->comment('Data do último contato realizado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contatos', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_abordagem',
                'indicado_por',
                'data_retorno',
                'ultimo_contato'
            ]);
        });
    }
};
