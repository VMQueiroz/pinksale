<?php

namespace App\Models\Consultores;

use App\Models\User;
use App\Models\Vendas\Venda;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consultor extends Model
{
    use HasFactory;

    protected $table = 'consultores';

    protected $fillable = [
        'user_id',
        'nome',
        'email',
        'telefone',
        'nivel',
        'status',
        'data_inicio',
        'meta_mensal'
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'meta_mensal' => 'decimal:2'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendas(): HasMany
    {
        return $this->hasMany(Venda::class);
    }
}