<main class="max-w-3xl mx-auto px-4 py-8">
    
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gerenciar Cardápio</h1>
            <p class="text-gray-600">Cadastre as opções para o almoço ou janta.</p>
        </div>
    </div>

    <?php if ($success ?? null): ?>
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded text-sm">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($error ?? null): ?>
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded text-sm">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="/admin/cardapio" method="POST" class="bg-white shadow-md rounded-lg p-6">
        
        <div class="grid grid-cols-2 gap-4 mb-6 pb-6 border-b border-gray-200">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Data</label>
                <input type="date" name="date" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Tipo</label>
                <select name="type" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                    <option value="almoco">Almoço</option>
                    <option value="janta">Jantar</option>
                </select>
            </div>
        </div>

        <h3 class="text-lg font-bold text-gray-700 mb-4">Itens Variáveis</h3>
        <div class="grid grid-cols-2 gap-4 mb-6">
            
            <div>
                <label class="block text-gray-700 font-bold mb-1 text-sm">Proteína Principal</label>
                <input type="text" name="protein" placeholder="Ex: Kafta Assada" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-1 text-sm">Opção Vegana/Vegetariana</label>
                <input type="text" name="protein_vegan" placeholder="Ex: Bolinho de Lentilha" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500" required>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1 text-sm">Guarnição (Carbo Extra)</label>
                <input type="text" name="carb_extra" placeholder="Ex: Abóbora Cozida" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-1 text-sm">Salada Extra</label>
                <input type="text" name="salad_extra" placeholder="Ex: Abobrinha Ralada" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500" required>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1 text-sm">Sobremesa</label>
                <input type="text" name="dessert" placeholder="Ex: Gelatina" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-1 text-sm">Tipo de Feijão</label>
                <select name="beans" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                    <option value="preto">Feijão Preto</option>
                    <option value="carioca">Feijão Carioca</option>
                </select>
            </div>
        </div>

        <h3 class="text-lg font-bold text-gray-700 mb-4 border-t border-gray-200 pt-4">Itens Fixos (Padrão Diário)</h3>
        <div class="grid grid-cols-3 gap-2 mb-6">
            <input type="text" value="Arroz Branco + Integral" class="bg-gray-100 text-gray-500 border border-gray-200 rounded px-3 py-2 text-sm" disabled>
            <input type="text" value="Mix de Folhas" class="bg-gray-100 text-gray-500 border border-gray-200 rounded px-3 py-2 text-sm" disabled>
            <input type="text" value="Suco ou Água" class="bg-gray-100 text-gray-500 border border-gray-200 rounded px-3 py-2 text-sm" disabled>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded hover:bg-blue-700 transition duration-200">
            Salvar Cardápio
        </button>
    </form>
</main>