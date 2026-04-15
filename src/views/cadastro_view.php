<div class="flex items-center justify-center mt-12">

    <div class="bg-white p-8 rounded-lg shadow-md w-96">

        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Criar conta</h2>

        <?php if (isset($_SESSION['success'])) : ?>
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded text-sm">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if ($error ?? null) : ?>
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="/cadastrar" method="POST">

            <div class="mb-4">
                <label for="username" class="block text-gray-700 font-bold mb-2">Seu nome completo</label>
                <input type="text" id="username" name="username" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-green-500" required>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">Crie uma senha</label>
                <input type="password" id="password" name="password" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-green-500" required>
            </div>

            <div class="mb-6">
                <label for="password_confirm" class="block text-gray-700 font-bold mb-2">Confirme a senha</label>
                <input type="password" id="password_confirm" name="password_confirm" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-green-500" required>
            </div>

            <button type="submit" class="w-full bg-green-600 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition duration-200">
                Cadastrar
            </button>

        </form>

        <p class="mt-4 text-center text-gray-600">
            Já tem uma conta?
            <a href="/login" class="text-green-600 hover:underline">Faça login</a>
        </p>

    </div>
</div>