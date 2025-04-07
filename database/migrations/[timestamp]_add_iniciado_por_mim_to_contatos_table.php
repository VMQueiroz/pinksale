<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contatos', function (Blueprint $table) {
            $table->boolean('iniciado_por_mim')->default(false);
        });
    }

    public function down()
    {
        Schema::table('contatos', function (Blueprint $table) {
            $table->dropColumn('iniciado_por_mim');
        });
    }
};