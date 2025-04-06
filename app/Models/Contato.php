<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contato extends Model
{
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cep',
        'endereco',
        'numero',
        'complemento',
        'estado',
        'cidade',
        'dia_aniversario',
        'mes_aniversario',
        'observacoes',
        'tipo_de_pele',
        'tom_de_pele',
        'habilitado_fidelidade',
        'ativo',
        'papeis',
        'user_id',
    ];

    protected $casts = [
        'habilitado_fidelidade' => 'boolean',
        'ativo' => 'boolean',
        'papeis' => 'array',
    ];
}