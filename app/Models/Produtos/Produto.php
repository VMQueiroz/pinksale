<?php

namespace App\Models\Produtos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'codigo',
        'codigos_alternativos',
        'nome',
        'marca',
        'preco',
        'categoria',
        'subcategoria',
        'tonalidade',
        'eh_kit',
        'foto',
        'descricao',
        'ativo',
    ];

    public function kitComponentes()
    {
        return $this->belongsToMany(Produto::class, 'kit_produtos', 'kit_id', 'produto_id')
                    ->withPivot('quantidade')
                    ->withTimestamps();
    }
}

