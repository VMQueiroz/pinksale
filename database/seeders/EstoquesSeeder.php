<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstoquesSeeder extends Seeder
{
    public function run()
    {
        // Exemplo: usuário padrão com id 1
        $userId = 1;
        
        // Lista de produtos do catálogo global para os quais definiremos um estoque inicial.
        // Vamos supor que os produtos foram inseridos com os códigos MK001 até MK017 (como nos seeders anteriores).
        $produtosEstoque = [
            // Para cada produto, defina a quantidade inicial.
            // Você pode ajustar esses valores conforme necessário.
            ['codigo' => 'MK001', 'quantidade' => 20],
            ['codigo' => 'MK002', 'quantidade' => 15],
            ['codigo' => 'MK003', 'quantidade' => 30],
            ['codigo' => 'MK004', 'quantidade' => 25],
            ['codigo' => 'MK005', 'quantidade' => 18],
            ['codigo' => 'MK006', 'quantidade' => 22],
            ['codigo' => 'MK007', 'quantidade' => 28],
            ['codigo' => 'MK008', 'quantidade' => 24],
            ['codigo' => 'MK009', 'quantidade' => 20],
            ['codigo' => 'MK010', 'quantidade' => 16],
            ['codigo' => 'MK011', 'quantidade' => 10],
            ['codigo' => 'MK012', 'quantidade' => 12],
            ['codigo' => 'MK013', 'quantidade' => 18],
            ['codigo' => 'MK014', 'quantidade' => 14],
            ['codigo' => 'MK015', 'quantidade' => 30],
            ['codigo' => 'MK016', 'quantidade' => 20],
            ['codigo' => 'MK017', 'quantidade' => 15],
        ];
        
        foreach ($produtosEstoque as $produtoData) {
            // Busca o produto pelo código
            $produto = DB::table('produtos')->where('codigo', $produtoData['codigo'])->first();
            
            if ($produto) {
                DB::table('estoques')->insert([
                    'user_id'    => $userId,
                    'produto_id' => $produto->id,
                    'quantidade' => $produtoData['quantidade'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
