<?php

namespace App\Models\Agenda;

use App\Models\User;
use App\Models\Contatos\Contato;
use App\Models\Entrevistas\Entrevista;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agenda extends Model
{
    use HasFactory;

    protected $table = 'agenda';

    protected $fillable = [
        'user_id',
        'tipo_evento',
        'referencia_id',
        'referencia_tipo',
        'titulo',
        'descricao',
        'data_evento',
        'hora_inicio',
        'hora_fim',
        'local',
        'status',
        'contato_id',
    ];

    // Log para depuração
    protected static function booted()
    {
        static::creating(function ($agenda) {
            \Illuminate\Support\Facades\Log::info('Criando evento na agenda', [
                'user_id' => $agenda->user_id,
                'tipo_evento' => $agenda->tipo_evento,
                'titulo' => $agenda->titulo,
                'data_evento' => $agenda->data_evento,
            ]);
        });
    }

    protected $casts = [
        'data_evento' => 'date',
    ];

    // Relacionamento com o usuário que criou o evento
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relacionamento com o contato relacionado
    public function contato(): BelongsTo
    {
        return $this->belongsTo(Contato::class, 'contato_id');
    }

    // Relacionamento polimórfico com a referência
    public function referencia(): MorphTo
    {
        return $this->morphTo('referencia');
    }

    // Método para criar um evento de agenda a partir de uma entrevista
    public static function criarDeEntrevista(Entrevista $entrevista): self
    {
        // Sempre usar o usuário autenticado atual para garantir que temos um user_id válido
        $userId = \Illuminate\Support\Facades\Auth::id() ?: $entrevista->user_id;

        // Se ainda não temos um user_id, usar um valor padrão (admin)
        if (!$userId) {
            $userId = 1; // Assumindo que o ID 1 é um administrador ou usuário do sistema
            \Illuminate\Support\Facades\Log::warning('Usando user_id padrão para evento de agenda', ['entrevista_id' => $entrevista->id]);
        }

        return self::create([
            'user_id' => $userId,
            'tipo_evento' => 'entrevista',
            'referencia_id' => $entrevista->id,
            'referencia_tipo' => get_class($entrevista),
            'titulo' => $entrevista->titulo,
            'descricao' => $entrevista->descricao,
            'data_evento' => $entrevista->data_entrevista,
            'hora_inicio' => $entrevista->hora_inicio,
            'hora_fim' => $entrevista->hora_fim,
            'local' => $entrevista->local,
            'status' => $entrevista->status,
            'contato_id' => $entrevista->abordagem_id,
        ]);
    }

    // Método para atualizar um evento de agenda a partir de uma entrevista
    public static function atualizarDeEntrevista(Entrevista $entrevista): void
    {
        // Verificar se o evento existe
        $evento = self::where('referencia_id', $entrevista->id)
            ->where('referencia_tipo', get_class($entrevista))
            ->first();

        if ($evento) {
            // Atualizar o evento existente
            $evento->update([
                'titulo' => $entrevista->titulo,
                'descricao' => $entrevista->descricao,
                'data_evento' => $entrevista->data_entrevista,
                'hora_inicio' => $entrevista->hora_inicio,
                'hora_fim' => $entrevista->hora_fim,
                'local' => $entrevista->local,
                'status' => $entrevista->status,
            ]);
        } else {
            // Criar um novo evento se não existir
            self::criarDeEntrevista($entrevista);
        }
    }
}
