<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendasTable extends Migration
{
    /**
     * Executa as migrations.
     */
    public function up()
    {
        Schema::create('vendas', function (Blueprint $table) {
            $table->id();
            
            // Usuário que registra a venda (por exemplo, o consultor responsável)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            // Comprador: o contato que realizou a compra
            $table->foreignId('comprador_id')->constrained('contatos')->cascadeOnDelete();
            
            // Campos opcionais para associar, se aplicável, consultor e parceiro na venda
            $table->foreignId('consultor_id')->nullable()->constrained('contatos')->nullOnDelete();
            $table->foreignId('parceiro_id')->nullable()->constrained('contatos')->nullOnDelete();
            
            // Caso a venda seja realizada durante uma sessão, associar a sessão
            $table->foreignId('sessao_id')->nullable()->constrained('sessoes')->nullOnDelete();
            
            // Dados da venda
            $table->date('data_venda');
            $table->decimal('valor_total', 10, 2);
            $table->decimal('desconto', 10, 2)->default(0);  // Desconto aplicado (valor ou porcentagem convertida)
            $table->string('status', 20)->default('EM ABERTO');  // Por exemplo: EM ABERTO, PAGO, CANCELADA
            $table->text('observacoes')->nullable();
            
            $table->timestamps();
        });
    }
    
    /**
     * Reverte as migrations.
     */
    public function down()
    {
        Schema::dropIfExists('vendas');
    }
}
