<div class="p-6 bg-white rounded-lg shadow">
    <form wire:submit="save">
        <!-- Header -->
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900">Nova Venda</h3>
        </div>

        <!-- Conteúdo Principal -->
        <div class="space-y-6">
            <!-- Informações Básicas -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Comprador</label>
                    <x-ui.select wire:model="comprador_id" :error="$errors->has('comprador_id')">
                        <option value="">Selecione o comprador</option>
                        @foreach($compradores as $comprador)
                            <option value="{{ $comprador->id }}">{{ $comprador->nome }}</option>
                        @endforeach
                    </x-ui.select>
                    @error('comprador_id') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Consultor</label>
                    <x-ui.select wire:model="consultor_id">
                        <option value="">Selecione o consultor</option>
                        @foreach($consultores as $consultor)
                            <option value="{{ $consultor->id }}">{{ $consultor->nome }}</option>
                        @endforeach
                    </x-ui.select>
                </div>
            </div>

            <!-- Items da Venda -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-medium text-gray-900">Items</h4>
                    <x-ui.button 
                        variant="secondary"
                        size="sm"
                        wire:click="addItem"
                        type="button">
                        Adicionar Item
                    </x-ui.button>
                </div>

                <div class="space-y-3">
                    @foreach($items as $index => $item)
                        <div class="grid grid-cols-12 gap-3 p-3 bg-gray-50 rounded-lg">
                            <div class="col-span-4">
                                <select wire:model="items.{{ $index }}.produto_id" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-pk focus:ring-pk sm:text-sm">
                                    <option value="">Selecione o produto</option>
                                    @foreach($produtos as $produto)
                                        <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-span-2">
                                <input type="number" 
                                       wire:model="items.{{ $index }}.quantidade"
                                       min="1"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-pk focus:ring-pk sm:text-sm"
                                       placeholder="Qtd">
                            </div>

                            <div class="col-span-2">
                                <input type="number" 
                                       wire:model="items.{{ $index }}.preco_unitario"
                                       step="0.01"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-pk focus:ring-pk sm:text-sm"
                                       placeholder="Preço">
                            </div>

                            <div class="col-span-2">
                                <input type="number" 
                                       wire:model="items.{{ $index }}.desconto"
                                       step="0.01"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-pk focus:ring-pk sm:text-sm"
                                       placeholder="Desconto">
                            </div>

                            <div class="col-span-2 flex items-center justify-end">
                                <button type="button" 
                                        wire:click="removeItem({{ $index }})"
                                        class="text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Totais -->
            <div class="flex justify-end">
                <div class="w-64 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium">R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Desconto:</span>
                        <span class="font-medium">R$ {{ number_format($desconto, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t">
                        <span class="font-medium">Total:</span>
                        <span class="font-bold text-pk">R$ {{ number_format($total, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 flex justify-end">
            <x-ui.button type="submit">
                Salvar Venda
            </x-ui.button>
        </div>
    </form>
</div>




