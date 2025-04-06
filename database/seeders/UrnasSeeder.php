<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UrnasSeeder extends Seeder
{
    public function run()
    {
        // Tenta encontrar um contato com papel "parceiro" para usar como parceiro da urna
        $parceiro = DB::table('contatos')->where('papeis', 'like', '%parceiro%')->first();
        if (!$parceiro) {
            $parceiroId = 1;
            $parceiroNome = 'Parceiro Exemplo';
            $parceiroNomeContato = 'Contato Parceiro Exemplo';
            $parceiroEmail = 'parceiro@example.com';
            $parceiroTelefone = '(11) 99999-9999';
            $parceiroCep = '00000-000';
            $parceiroEndereco = 'Rua Exemplo';
            $parceiroNumero = '123';
            $parceiroComplemento = 'Apto 101';
            $parceiroEstado = 'SP';
            $parceiroCidade = 'São Paulo';
        } else {
            $parceiroId = $parceiro->id;
            $parceiroNome = $parceiro->nome;
            $parceiroNomeContato = $parceiro->nome_contato;
            $parceiroEmail = $parceiro->email;
            $parceiroTelefone = $parceiro->telefone;
            $parceiroCep = $parceiro->cep;
            $parceiroEndereco = $parceiro->endereco;
            $parceiroNumero = $parceiro->numero;
            $parceiroComplemento = $parceiro->complemento;
            $parceiroEstado = $parceiro->estado;
            $parceiroCidade = $parceiro->cidade;
        }
        
        // Insere uma urna de exemplo
        DB::table('urnas')->insert([
            'parceiro_id'                     => $parceiroId,
            'parceiro_nome_snapshot'          => $parceiroNome,
            'parceiro_nome_contato_snapshot'  => $parceiroNomeContato,
            'parceiro_email_snapshot'         => $parceiroEmail,
            'parceiro_telefone_snapshot'      => $parceiroTelefone,
            'parceiro_cep_snapshot'           => $parceiroCep,
            'parceiro_endereco_snapshot'      => $parceiroEndereco,
            'parceiro_numero_snapshot'        => $parceiroNumero,
            'parceiro_complemento_snapshot'   => $parceiroComplemento,
            'parceiro_estado_snapshot'        => $parceiroEstado,
            'parceiro_cidade_snapshot'        => $parceiroCidade,
            'data_inicio'                     => Carbon::now()->toDateString(),
            'data_retirar_em'                 => Carbon::now()->addDays(30)->toDateString(),
            'data_retirada'                   => null,
            'status'                          => 'ATIVA',
            'observacoes'                     => 'Urna de teste gerada pelo seeder.',
            'created_at'                      => Carbon::now(),
            'updated_at'                      => Carbon::now(),
        ]);
    }
}
