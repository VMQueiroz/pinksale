<?php

namespace App\Livewire\Agenda;

use Livewire\Component;
use App\Models\Agenda\Agenda;
use App\Models\Contatos\Contato;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TesteEntrevista extends Component
{
    public $titulo = 'Entrevista de Teste';
    public $data_evento;
    public $hora_inicio = '14:00';
    public $abordagemId;

    public function mount()
    {
        $this->data_evento = now()->format('Y-m-d');
    }

    public function salvarEntrevista()
    {
        Log::info('Tentando salvar entrevista de teste');

        try {
            // Buscar a primeira abordagem disponível
            $abordagem = Contato::whereJsonContains('papeis', 'abordagem')
                ->first();

            if (!$abordagem) {
                session()->flash('message', 'Erro: Nenhuma abordagem encontrada.');
                return;
            }

            // Criar o evento na agenda
            $evento = Agenda::create([
                'user_id' => Auth::id(),
                'tipo_evento' => 'entrevista',
                'titulo' => $this->titulo,
                'data_evento' => $this->data_evento,
                'hora_inicio' => $this->hora_inicio,
                'hora_fim' => '15:00',
                'local' => 'Local de teste',
                'status' => 'pendente',
                'contato_id' => $abordagem->id,
            ]);

            Log::info('Entrevista de teste salva com sucesso', ['id' => $evento->id]);
            session()->flash('message', 'Entrevista salva com sucesso! ID: ' . $evento->id);

        } catch (\Exception $e) {
            Log::error('Erro ao salvar entrevista de teste', ['error' => $e->getMessage()]);
            session()->flash('error', 'Erro: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.agenda.teste-entrevista');
    }
}
