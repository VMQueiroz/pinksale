<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendaItensTable extends Migration
{
    /**
     * Executa as migrations.
     */
    public function up()
    {
        Schema::create('venda_itens', function (Blueprint $table) {
            $table->id();
            
            // Relaciona o item à venda
            $table->foreignId('venda_id')->constrained('vendas')->cascadeOnDelete();
            
            // Produto (ou kit) utilizado na venda, referenciando a tabela de produtos
            $table->foreignId('produto_id')->constrained('produtos')->cascadeOnDelete();
            
            // Quantidade vendida
            $table->integer('quantidade');
            
            // Preço unitário no momento da venda
            $table->decimal('preco_unitario', 10, 2);
            
            // Desconto aplicado no item, se houver
            $table->decimal('desconto', 10, 2)->default(0);
            
            // Subtotal (calculado como: quantidade * preco_unitario - desconto)
            $table->decimal('subtotal', 10, 2);

            // Novo campo para controle de entrega
            $table->boolean('entregue')->default(false);
            $table->date('data_entrega')->nullable();
            
            $table->timestamps();
        });
    }
    
    /**
     * Reverte as migrations.
     */
    public function down()
    {
        Schema::dropIfExists('venda_itens');
    }
}
