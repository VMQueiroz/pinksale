<?php

namespace App\Models\Entrevistas;

use App\Models\User;
use App\Models\Agenda\Agenda;
use App\Models\Contatos\Contato;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entrevista extends Model
{
    use HasFactory;

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Antes de criar uma entrevista, garantir que temos os campos obrigatórios
        static::creating(function ($entrevista) {
            // Log para depuração
            \Illuminate\Support\Facades\Log::info('Evento creating da entrevista', [
                'user_id' => $entrevista->user_id,
                'abordagem_id' => $entrevista->abordagem_id,
                'titulo' => $entrevista->titulo,
                'data_entrevista' => $entrevista->data_entrevista,
            ]);

            // Definir user_id se não estiver definido
            if (!$entrevista->user_id) {
                $entrevista->user_id = \Illuminate\Support\Facades\Auth::id();
                \Illuminate\Support\Facades\Log::info('Definindo user_id para: ' . $entrevista->user_id);
            }

            // Verificar se temos abordagem_id
            if (!$entrevista->abordagem_id) {
                \Illuminate\Support\Facades\Log::error('Tentativa de criar entrevista sem abordagem_id');
                throw new \Exception('Não é possível criar uma entrevista sem abordagem_id');
            }
        });

        // Após criar uma entrevista
        static::created(function ($entrevista) {
            \Illuminate\Support\Facades\Log::info('Entrevista criada com sucesso', [
                'id' => $entrevista->id,
                'user_id' => $entrevista->user_id,
                'abordagem_id' => $entrevista->abordagem_id,
            ]);
        });
    }

    protected $fillable = [
        'abordagem_id',
        'user_id',
        'titulo',
        'descricao',
        'data_entrevista',
        'hora_inicio',
        'hora_fim',
        'local',
        'status',
        'convertido_consultor',
        'observacoes_resultado',
        'data_realizacao',
    ];

    protected $casts = [
        'data_entrevista' => 'date',
        'data_realizacao' => 'date',
        'convertido_consultor' => 'boolean',
    ];

    // Relacionamento com a abordagem
    public function abordagem(): BelongsTo
    {
        return $this->belongsTo(Contato::class, 'abordagem_id');
    }

    // Relacionamento com o usuário que criou a entrevista
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Verifica se a entrevista está pendente
    public function isPendente(): bool
    {
        return $this->status === 'pendente';
    }

    // Verifica se a entrevista foi realizada
    public function isRealizada(): bool
    {
        return $this->status === 'realizada';
    }

    // Verifica se a entrevista foi cancelada
    public function isCancelada(): bool
    {
        return $this->status === 'cancelada';
    }

    // Relacionamento polimórfico com a agenda
    public function agenda(): MorphOne
    {
        return $this->morphOne(Agenda::class, 'referencia');
    }

    // Método para sincronizar com a agenda
    public function sincronizarComAgenda(): void
    {
        // Verificar se temos os campos obrigatórios
        if (!$this->abordagem_id) {
            \Illuminate\Support\Facades\Log::warning('Tentativa de sincronizar entrevista sem abordagem_id', ['entrevista_id' => $this->id]);
            return; // Sair silenciosamente sem sincronizar
        }

        // Garantir que o user_id esteja definido
        if (!$this->user_id) {
            $this->user_id = \Illuminate\Support\Facades\Auth::id();
            // Não chamar save() aqui para evitar problemas com abordagem_id
            $this->forceFill(['user_id' => $this->user_id])->save();
        }

        // Verifica se já existe um evento na agenda para esta entrevista
        $eventoExistente = Agenda::where('referencia_id', $this->id)
            ->where('referencia_tipo', get_class($this))
            ->first();

        if ($eventoExistente) {
            // Atualiza o evento existente
            Agenda::atualizarDeEntrevista($this);
        } else {
            // Cria um novo evento
            Agenda::criarDeEntrevista($this);
        }
    }
}
