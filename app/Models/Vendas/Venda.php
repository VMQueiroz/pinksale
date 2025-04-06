<?php

namespace App\Models\Vendas;

use App\Models\Sessoes\Sessao;
use App\Models\Contatos\Contato;
use App\Models\Vendas\VendaItem;
use App\Models\Vendas\VendaFollowup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Venda extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'comprador_id',
        'consultor_id',
        'parceiro_id',
        'sessao_id',
        'data_venda',
        'valor_total',
        'desconto',
        'status',
        'observacoes',
    ];

    // Relacionamento com o comprador (Contato)
    public function comprador()
    {
        return $this->belongsTo(Contato::class, 'comprador_id');
    }

    // Relacionamento com o consultor (Contato)
    public function consultor()
    {
        return $this->belongsTo(Contato::class, 'consultor_id');
    }

    // Relacionamento com o parceiro (Contato)
    public function parceiro()
    {
        return $this->belongsTo(Contato::class, 'parceiro_id');
    }

    // Relacionamento com a sessão
    public function sessao()
    {
        return $this->belongsTo(Sessao::class, 'sessao_id');
    }

    // Relacionamento com os itens da venda
    public function itens()
    {
        return $this->hasMany(VendaItem::class, 'venda_id');
    }

    public function followup()
    {
        return $this->hasOne(VendaFollowup::class);
    }
}


