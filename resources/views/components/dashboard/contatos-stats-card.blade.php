<div class="bg-white overflow-hidden shadow rounded-lg">
    <div class="p-5">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        Contatos
                    </dt>
                    <dd class="flex items-baseline">
                        <div class="text-2xl font-semibold text-gray-900">
                            {{ $contatos['total'] }}
                        </div>
                    </dd>
                </dl>
            </div>
        </div>
        <div class="mt-5 grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-500">Clientes:</span>
                <span class="font-medium">{{ $contatos['clientes'] }}</span>
            </div>
            <div>
                <span class="text-gray-500">Consultores:</span>
                <span class="font-medium">{{ $contatos['consultores'] }}</span>
            </div>
            <div>
                <span class="text-gray-500">Parceiros:</span>
                <span class="font-medium">{{ $contatos['parceiros'] }}</span>
            </div>
            <div>
                <span class="text-gray-500">Abordagens:</span>
                <span class="font-medium">{{ $contatos['abordagens'] }}</span>
            </div>
        </div>
    </div>
</div>