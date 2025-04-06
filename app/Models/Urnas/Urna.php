<?php

namespace App\Models\Urnas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contatos\Contato;

class Urna extends Model
{
    use HasFactory;

    protected $fillable = [
        'parceiro_id',
        'data_inicio',
        'data_retirar_em',
        'data_retirada',
        'status',
        'observacoes',
        'parceiro_nome_snapshot',
        'parceiro_nome_contato_snapshot',
        'parceiro_email_snapshot',
        'parceiro_telefone_snapshot',
        'parceiro_cep_snapshot',
        'parceiro_endereco_snapshot',
        'parceiro_numero_snapshot',
        'parceiro_complemento_snapshot',
        'parceiro_estado_snapshot',
        'parceiro_cidade_snapshot',
    ];

    public function parceiro()
    {
        return $this->belongsTo(Contato::class, 'parceiro_id');
    }
}
