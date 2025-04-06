<x-slot name="header">
    <h2 class="font-semibold text-xl text-white leading-tight">
        Vendas
    </h2>
</x-slot>

<div>
    
    <!-- Filtros -->
    <div class="mb-4 flex space-x-4">
        <select wire:model="statusFiltro" class="border rounded p-2">
            <option value="TODOS">Todos</option>
            <option value="EM ABERTO">Em Aberto</option>
            <option value="PAGO">Pago</option>
            <option value="CANCELADA">Cancelada</option>
        </select>
        <input type="date" wire:model="dataInicio" class="border rounded p-2">
        <input type="date" wire:model="dataFim" class="border rounded p-2">
    </div>

    <!-- Tabela de Vendas -->
    <table class="min-w-full border-collapse border">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">ID</th>
                <th class="border p-2">Data</th>
                <th class="border p-2">Comprador</th>
                <th class="border p-2">Valor Total</th>
                <th class="border p-2">Status</th>
                <th class="border p-2">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vendas as $venda)
                <tr>
                    <td class="border p-2">{{ $venda->id }}</td>
                    <td class="border p-2">{{ $venda->data_venda }}</td>
                    <td class="border p-2">{{ $venda->comprador->nome ?? 'N/A' }}</td>
                    <td class="border p-2">R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</td>
                    <td class="border p-2">
                        <span class="mr-2">{{ $venda->status }}</span>
                        @if($venda->itens->where('entregue', false)->count() > 0)
                            <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded text-xs">
                                Pendente Entrega
                            </span>
                        @endif
                    </td>
                    <td class="border p-2">
                        <button class="bg-blue-500 text-white px-2 py-1 rounded">Visualizar</button>
                        <button class="bg-green-500 text-white px-2 py-1 rounded">Editar</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $vendas->links() }}
    </div>
</div>

