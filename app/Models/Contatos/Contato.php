<?php

namespace App\Models\Contatos;

use App\Models\User;
use App\Models\Vendas\Venda;
use App\Models\Sessoes\Sessao;
use App\Models\Urnas\Urna;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contato extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cep',
        'endereco',
        'numero',
        'complemento',
        'cidade',
        'estado',
        'dia_aniversario',
        'mes_aniversario',
        'tipo_de_pele',
        'tom_de_pele',
        'observacoes',
        'habilitado_fidelidade',
        'ativo',
        'user_id',
        'papeis'
    ];

    protected $casts = [
        'papeis' => 'array',
        'habilitado_fidelidade' => 'boolean',
        'ativo' => 'boolean',
    ];

    // Relacionamento com o usuário (consultor) que cadastrou o contato
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relacionamento com vendas onde este contato é o comprador
    public function vendasComoCliente(): HasMany
    {
        return $this->hasMany(Venda::class, 'comprador_id');
    }

    // Relacionamento com vendas onde este contato é o consultor
    public function vendasComoConsultor(): HasMany
    {
        return $this->hasMany(Venda::class, 'consultor_id');
    }

    // Relacionamento com vendas onde este contato é o parceiro
    public function vendasComoParceiro(): HasMany
    {
        return $this->hasMany(Venda::class, 'parceiro_id');
    }

    // Relacionamento com sessões onde este contato é o anfitrião
    public function sessoesComoAnfitriao(): HasMany
    {
        return $this->hasMany(Sessao::class, 'anfitriao_id');
    }

    // Relacionamento com urnas onde este contato é o parceiro
    public function urnasComoParceiro(): HasMany
    {
        return $this->hasMany(Urna::class, 'parceiro_id');
    }

    // Métodos auxiliares para verificar papéis
    public function isCliente(): bool
    {
        return in_array('cliente', $this->papeis ?? []);
    }

    public function isConsultor(): bool
    {
        return in_array('consultor', $this->papeis ?? []);
    }

    public function isParceiro(): bool
    {
        return in_array('parceiro', $this->papeis ?? []);
    }

    public function isAbordagem(): bool
    {
        return in_array('abordagem', $this->papeis ?? []);
    }

    // Método para adicionar um papel
    public function adicionarPapel(string $papel): void
    {
        $papeis = $this->papeis ?? [];
        if (!in_array($papel, $papeis)) {
            $papeis[] = $papel;
            $this->papeis = $papeis;
            $this->save();
        }
    }

    // Método para remover um papel
    public function removerPapel(string $papel): void
    {
        $papeis = $this->papeis ?? [];
        $this->papeis = array_diff($papeis, [$papel]);
        $this->save();
    }
}



