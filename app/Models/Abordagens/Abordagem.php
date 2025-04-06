<?php

namespace App\Models\Abordagens;

use App\Models\Clientes\Cliente;
use App\Models\Consultores\Consultor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Abordagem extends Model
{
    use HasFactory;

    protected $table = 'abordagens';

    protected $fillable = [
        'cliente_id',
        'consultor_id',
        'data_abordagem',
        'tipo',
        'status',
        'descricao',
        'proxima_acao',
        'data_proxima_acao'
    ];

    protected $casts = [
        'data_abordagem' => 'datetime',
        'data_proxima_acao' => 'datetime'
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function consultor(): BelongsTo
    {
        return $this->belongsTo(Consultor::class);
    }
}