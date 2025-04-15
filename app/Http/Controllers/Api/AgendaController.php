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
            ];
        });

        return response()->json($eventos);
    }
}
