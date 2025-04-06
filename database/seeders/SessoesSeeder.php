<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SessoesSeeder extends Seeder
{
    public function run()
    {
        // Supondo que exista um usuário com id 1 (por exemplo, criado pelo Jetstream)
        $userId = 1;
        
        // Tenta encontrar um contato com papel "parceiro" para usar como anfitrião
        $anfitriao = DB::table('contatos')->where('papeis', 'like', '%parceiro%')->first();
        if (!$anfitriao) {
            // Se não encontrar, usamos um fallback (id 1 e dados exemplo)
            $anfitriaoId = 1;
            $anfitriaoNome = 'Parceiro Exemplo';
            $anfitriaoEmail = 'parceiro@example.com';
            $anfitriaoTelefone = '(11) 99999-9999';
            $anfitriaoEndereco = 'Rua Exemplo';
            $anfitriaoNumero = '123';
            $anfitriaoComplemento = 'Apto 101';
            $anfitriaoEstado = 'SP';
            $anfitriaoCidade = 'São Paulo';
        } else {
            $anfitriaoId = $anfitriao->id;
            $anfitriaoNome = $anfitriao->nome;
            $anfitriaoEmail = $anfitriao->email;
            $anfitriaoTelefone = $anfitriao->telefone;
            $anfitriaoEndereco = $anfitriao->endereco;
            $anfitriaoNumero = $anfitriao->numero;
            $anfitriaoComplemento = $anfitriao->complemento;
            $anfitriaoEstado = $anfitriao->estado;
            $anfitriaoCidade = $anfitriao->cidade;
        }
        
        // Insere uma sessão de exemplo
        DB::table('sessoes')->insert([
            'user_id'                    => $userId,
            'anfitriao_id'               => $anfitriaoId,
            'anfitriao_nome_snapshot'    => $anfitriaoNome,
            'anfitriao_email_snapshot'   => $anfitriaoEmail,
            'anfitriao_telefone_snapshot'=> $anfitriaoTelefone,
            'anfitriao_endereco_snapshot'=> $anfitriaoEndereco,
            'anfitriao_numero_snapshot'  => $anfitriaoNumero,
            'anfitriao_complemento_snapshot' => $anfitriaoComplemento,
            'anfitriao_estado_snapshot'  => $anfitriaoEstado,
            'anfitriao_cidade_snapshot'  => $anfitriaoCidade,
            'data_sessao'                => Carbon::now()->addDays(2)->toDateString(),
            'hora_inicio'                => '10:00:00',
            'hora_fim'                   => '12:00:00',
            'numero_participantes'       => 0,
            'valor_vendas'               => 0.00,
            'indicacoes'                 => 0,
            'inicios_efetivos'           => 0,
            'observacoes'                => 'Sessão de teste gerada pelo seeder.',
            'created_at'                 => Carbon::now(),
            'updated_at'                 => Carbon::now(),
        ]);
    }
}
