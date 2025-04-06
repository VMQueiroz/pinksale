<?php

namespace App\Models\Clientes;

use App\Models\Vendas\Venda;
use App\Models\Abordagens\Abordagem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'endereco',
        'cidade',
        'estado',
        'cep',
        'data_nascimento',
        'observacoes'
    ];

    protected $casts = [
        'data_nascimento' => 'date'
    ];

    public function vendas(): HasMany
    {
        return $this->hasMany(Venda::class);
    }

    public function abordagens(): HasMany
    {
        return $this->hasMany(Abordagem::class);
    }
}