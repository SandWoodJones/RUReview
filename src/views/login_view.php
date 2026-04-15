<div class="flex items-center justify-center mt-12">

    <div class="bg-white p-8 rounded-lg shadow-md w-96">

        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Login</h2>

        <?php if ($error ?? null) : ?>
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST">

            <div class="mb-4">
                <label for="username" class="block text-gray-700 font-bold mb-2">Nome</label>
                <input type="text" id="username" name="username" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-bold mb-2">Senha</label>
                <input type="password" id="password" name="password" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition duration-200">
                Entrar
            </button>

        </form>

        <p class="mt-4 text-center text-gray-600">
            Não tem uma conta?
            <a href="/cadastro" class="text-blue-600 hover:underline">Cadastre-se</a>
        </p>

    </div>
</div>