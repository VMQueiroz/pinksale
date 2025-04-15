<?php

namespace App\Services;

class EventColorService
{
    public static function getEventColor($tipo, $status)
    {
        // Definir as cores base por tipo de evento
        $coresPorTipo = [
            'entrevista' => '#3b82f6', // Azul
            'sessao' => '#8b5cf6',    // Roxo
            'urna' => '#f59e0b',      // Laranja
            'outro' => '#ec4899',     // Rosa
            'default' => '#6b7280',   // Cinza
        ];

        // Obter a cor base para o tipo de evento
        $corBase = $coresPorTipo[$tipo] ?? $coresPorTipo['default'];

        // Modificar a cor com base no status
        if ($status === 'cancelado') {
            // Tornar a cor mais clara para eventos cancelados
            // Convertendo para RGB e adicionando transparência
            $hex = substr($corBase, 1); // Remover o #
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            return "rgba($r, $g, $b, 0.5)"; // 50% de opacidade
        }

        // Retornar a cor base para eventos pendentes
        return $corBase;
    }

    public static function getEventBorderColor($tipo, $status)
    {
        // Definir as cores de borda por tipo de evento
        $coresBordaPorTipo = [
            'entrevista' => '#2563eb', // Azul escuro
            'sessao' => '#7c3aed',    // Roxo escuro
            'urna' => '#d97706',      // Laranja escuro
            'outro' => '#db2777',     // Rosa escuro
            'default' => '#db2777',   // Cinza escuro
        ];

        // Obter a cor de borda base para o tipo de evento
        $corBordaBase = $coresBordaPorTipo[$tipo] ?? $coresBordaPorTipo['default'];

        // Modificar a cor com base no status
        if ($status === 'cancelado') {
            // Tornar a borda mais clara para eventos cancelados
            // Convertendo para RGB e adicionando transparência
            $hex = substr($corBordaBase, 1); // Remover o #
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            return "rgba($r, $g, $b, 0.7)"; // 70% de opacidade
        }

        // Retornar a cor de borda base para eventos pendentes
        return $corBordaBase;
    }
}
