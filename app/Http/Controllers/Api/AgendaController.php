<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agenda\Agenda;
use App\Services\EventColorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AgendaController extends Controller
{
    public function getEventos(Request $request)
    {
        $user = Auth::user();
        $start = $request->input('start');
        $end = $request->input('end');
        $tipoEvento = $request->input('tipoEvento');
        $search = $request->input('search');

        $query = Agenda::query()
            ->where('user_id', $user->id)
            ->when($start, function ($query) use ($start) {
                $query->whereDate('data_evento', '>=', $start);
            })
            ->when($end, function ($query) use ($end) {
                $query->whereDate('data_evento', '<=', $end);
            })
            ->when($tipoEvento && $tipoEvento !== 'null', function ($query) use ($tipoEvento) {
                $query->where('tipo_evento', $tipoEvento);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('titulo', 'like', '%' . $search . '%')
                        ->orWhere('descricao', 'like', '%' . $search . '%')
                        ->orWhere('local', 'like', '%' . $search . '%');
                });
            })
            ->with('contato');

        $eventos = $query->get()->map(function ($evento) {
            return [
                'id' => $evento->id,
                'title' => $evento->titulo,
                'start' => $evento->data_evento->format('Y-m-d') .
                    ($evento->hora_inicio ? 'T' . $evento->hora_inicio : ''),
                'end' => $evento->data_evento->format('Y-m-d') .
                    ($evento->hora_fim ? 'T' . $evento->hora_fim :
                    ($evento->hora_inicio ? 'T' . Carbon::parse($evento->hora_inicio)->addHour()->format('H:i:s') : '')),
                'extendedProps' => [
                    'tipo_evento' => $evento->tipo_evento,
                    'tipoEvento' => $evento->tipo_evento, // Adicionado para compatibilidade com o JavaScript
                    'status' => $evento->status,
                    'local' => $evento->local,
                    'contato' => $evento->contato ? $evento->contato->nome : null,
                    'descricao' => $evento->descricao,
                ],
                'backgroundColor' => EventColorService::getEventColor($evento->tipo_evento, $evento->status),
                'borderColor' => EventColorService::getEventBorderColor($evento->tipo_evento, $evento->status),
                'textColor' => '#ffffff',
                'editable' => true, // Permitir arrastar e soltar
            ];
        });

        return response()->json($eventos);
    }

    /**
     * Atualiza um evento quando arrastado ou redimensionado no calendário
     */
    public function atualizarEvento(Request $request)
    {
        $user = Auth::user();
        $eventoId = $request->input('id');
        $novaData = $request->input('start');
        $novoFim = $request->input('end');

        // Verificar se o evento pertence ao usuário atual
        $evento = Agenda::where('id', $eventoId)
            ->where('user_id', $user->id)
            ->first();

        if (!$evento) {
            return response()->json(['error' => 'Evento não encontrado'], 404);
        }

        try {
            // Extrair data e hora
            $dataHoraInicio = Carbon::parse($novaData);
            $dataEvento = $dataHoraInicio->format('Y-m-d');
            $horaInicio = $dataHoraInicio->format('H:i:s');

            $horaFim = null;
            if ($novoFim) {
                $dataHoraFim = Carbon::parse($novoFim);
                $horaFim = $dataHoraFim->format('H:i:s');
            }

            // Atualizar o evento
            $evento->update([
                'data_evento' => $dataEvento,
                'hora_inicio' => $horaInicio,
                'hora_fim' => $horaFim,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Evento atualizado com sucesso!',
                'evento' => $evento
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar evento: ' . $e->getMessage()
            ], 500);
        }
    }
}
