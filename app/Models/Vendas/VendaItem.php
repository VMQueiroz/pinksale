<?php

namespace App\Models\Vendas;

use App\Models\Produtos\Produto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class VendaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'venda_id',
        'produto_id',
        'quantidade',
        'preco_unitario',
        'desconto',
        'subtotal',
        'entregue',
        'data_entrega'
    ];

    protected $casts = [
        'entregue' => 'boolean',
        'data_entrega' => 'date'
    ];

    // Relacionamento com a venda
    public function venda()
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }

    // Relacionamento com o produto
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
