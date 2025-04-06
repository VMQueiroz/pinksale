<?php

namespace App\Models\Vendas;

use Illuminate\Database\Eloquent\Model;
use App\Models\Vendas\Venda;

class VendaFollowup extends Model
{
    protected $table = 'venda_followups';

    protected $fillable = [
        'venda_id',
        'data_entrega',
        'contato_2dias',
        'contato_2semanas',
        'contato_2meses',
        'contato_2dias_realizado',
        'contato_2semanas_realizado',
        'contato_2meses_realizado'
    ];

    protected $casts = [
        'data_entrega' => 'date',
        'contato_2dias' => 'date',
        'contato_2semanas' => 'date',
        'contato_2meses' => 'date',
        'contato_2dias_realizado' => 'boolean',
        'contato_2semanas_realizado' => 'boolean',
        'contato_2meses_realizado' => 'boolean'
    ];

    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }
}