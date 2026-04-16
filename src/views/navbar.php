<nav class="bg-white border-b border-gray-200 px-4 py-3">
    <div class="max-w-2xl mx-auto flex items-center justify-between">
        <div>
            <span class="font-medium text-gray-800"><?= APP_NAME ?></span>
            <span class="text-xs text-gray-500"><?= APP_VERSION ?></span>
        </div>
        <span class="text-sm text-gray-500">Olá, <?= htmlspecialchars($_SESSION['username']) ?></span>

        <form action="/logout" method="POST" class="inline">
            <button type="submit" class="text-sm text-red-500 hover:underline ml-4 cursor-pointer">Sair</button>
        </form>
    </div>
</nav>
