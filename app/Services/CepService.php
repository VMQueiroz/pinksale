<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CepService
{
    /**
     * Busca os dados de um CEP utilizando a API do ViaCEP.
     *
     * @param string $cep
     * @return array|null
     */
    public function buscarDados(string $cep): ?array
    {
        // Remove qualquer caractere que não seja dígito
        $cepLimpo = preg_replace('/[^0-9]/', '', $cep);

        // Valida se o CEP possui 8 dígitos
        if (strlen($cepLimpo) !== 8) {
            return null;
        }

        try {
            $response = Http::get("https://viacep.com.br/ws/{$cepLimpo}/json/");

            if ($response->successful()) {
                $dados = $response->json();
                // Verifica se houve erro na consulta (ex.: CEP não encontrado)
                if (isset($dados['erro'])) {
                    return null;
                }
                return $dados;
            }
        } catch (\Exception $e) {
            // Opcional: registre o erro para fins de debug
            // \Log::error('Erro ao buscar CEP: ' . $e->getMessage());
        }

        return null;
    }
}
