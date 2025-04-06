<?php

namespace App\Models\Sessoes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contatos\Contato;

class Sessao extends Model
{
    use HasFactory;

    protected $table = 'sessoes';

    protected $fillable = [
        'user_id',
        'anfitriao_id',
        'data_sessao',
        'hora_inicio',
        'hora_fim',
        'numero_participantes',
        'valor_vendas',
        'indicacoes',
        'inicios_efetivos',
        'observacoes',
    ];

    public function anfitriao()
    {
        return $this->belongsTo(Contato::class, 'anfitriao_id');
    }
}
