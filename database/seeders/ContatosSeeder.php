<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContatosSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('contatos')->insert([
            // Registro 1: Cliente / Abordagem
            [
                'user_id'            => 1, // ID do usuário (consultor) que cadastrou o contato
                'nome'               => fake()->name(),
                'email'              => fake()->email(),
                'telefone'           => '(11) 91234-5678',
                'cep'                => '12345-678',
                'endereco'           => 'Rua das Flores',
                'numero'             => '100',
                'complemento'        => 'Apto 101',
                'estado'             => 'SP',
                'cidade'             => 'São Paulo',
                'dia_aniversario'    => 15,
                'mes_aniversario'    => 6,
                'observacoes'        => 'Cliente interessado em produtos de beleza.',
                'tipo_de_pele'       => 'Normal',
                'tom_de_pele'        => 'Neutro',
                'nome_contato'       => null,
                'iniciado_por_mim'   => true,
                'data_inicio'        => now(),
                'papeis'             => json_encode(['abordagem', 'cliente']),
                'habilitado_fidelidade' => false,
                'ativo'              => true,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            // Registro 2: Consultor (que também pode ser cliente)
            [
                'user_id'            => 1,
                'nome'               => fake()->name(),
                'email'              => fake()->email(),
                'telefone'           => '(11) 98765-4321',
                'cep'                => '23456-789',
                'endereco'           => 'Av. Paulista',
                'numero'             => '2000',
                'complemento'        => 'Sala 101',
                'estado'             => 'SP',
                'cidade'             => 'São Paulo',
                'dia_aniversario'    => 20,
                'mes_aniversario'    => 8,
                'observacoes'        => 'Consultora experiente e ativa.',
                'tipo_de_pele'       => 'Oleosa',
                'tom_de_pele'        => 'Claro',
                'nome_contato'       => null,
                'papeis'             => json_encode(['consultor', 'cliente']),
                'habilitado_fidelidade' => true,
                'ativo'              => true,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            // Registro 3: Parceiro
            [
                'user_id'            => 1,
                'nome'               => fake()->name(),
                'email'              => fake()->email(),
                'telefone'           => '(11) 99876-5432',
                'cep'                => '34567-890',
                'endereco'           => 'Rua dos Parceiros',
                'numero'             => '50',
                'complemento'        => 'Loja 3',
                'estado'             => 'SP',
                'cidade'             => 'São Paulo',
                'dia_aniversario'    => null,
                'mes_aniversario'    => null,
                'observacoes'        => 'Parceiro para eventos e sessões.',
                'tipo_de_pele'       => null,
                'tom_de_pele'        => null,
                'nome_contato'       => 'Carlos Pereira',
                'papeis'             => json_encode(['parceiro']),
                'habilitado_fidelidade' => false,
                'ativo'              => true,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
        ]);
    }
}

