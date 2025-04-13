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
            // Campos para rastreamento de conversão
            $table->string('convertido_de')->nullable()->comment('Tipo original antes da conversão (ex: abordagem)');
            $table->date('data_conversao')->nullable()->comment('Data em que o contato foi convertido');
            $table->string('origem_conversao')->nullable()->comment('Origem da conversão (ex: entrevista, indicação)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contatos', function (Blueprint $table) {
            $table->dropColumn([
                'convertido_de',
                'data_conversao',
                'origem_conversao'
            ]);
        });
    }
};
