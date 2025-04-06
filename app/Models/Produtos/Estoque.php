<?php

namespace App\Models\Produtos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'produto_id',
        'quantidade',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
