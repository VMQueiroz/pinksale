
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Painel Geral
        </h2>
    </x-slot>

    <div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-blue-100 rounded shadow">
                <h2 class="text-xl font-semibold">Total de Vendas</h2>
                <p class="text-2xl">R$ {{ number_format($totalVendas, 2, ',', '.') }}</p>
            </div>
            <div class="p-4 bg-green-100 rounded shadow">
                <h2 class="text-xl font-semibold">Sessões Agendadas</h2>
                <p class="text-2xl">{{ $totalSessoes }}</p>
            </div>
            <div class="p-4 bg-yellow-100 rounded shadow">
                <h2 class="text-xl font-semibold">Clientes Cadastrados</h2>
                <p class="text-2xl">{{ $totalContatos }}</p>
            </div>
        </div>
    </div>


