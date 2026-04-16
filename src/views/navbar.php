<?php
$current = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

function nav_link(string $href, string $label, string $current): void
{
    $active = $current === $href ? 'text-blue-600 font-semibold' : 'text-gray-500 hover:text-gray-800';
    echo "<a href=\"{$href}\" class=\"text-sm {$active} transition-colors\">{$label}</a>";
}
?>

<nav class="bg-white border-b border-gray-200 px-4 py-3">
    <div class="max-w-4xl mx-auto flex items-center justify-between gap-4">

        <div class="flex items-center gap-1.5">
            <span class="font-semibold text-gray-800"><?= APP_NAME ?></span>
            <span class="text-xs text-gray-400"><?= APP_VERSION ?></span>
        </div>

        <div class="flex items-center gap-5">
            <?php nav_link('/', 'Avaliar', $current) ?>

            <?php if (!empty($_SESSION['is_admin'])): ?>
                <?php nav_link('/admin/cardapio',    'Cardápio',    $current) ?>
                <?php nav_link('/admin/avaliacoes', 'Avaliações',  $current) ?>
            <?php endif; ?>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-500"><?= htmlspecialchars($_SESSION['username']) ?></span>
            <form action="/logout" method="POST" class="inline">
                <button type="submit" class="text-sm text-red-500 hover:text-red-700 transition-colors cursor-pointer">
                    Sair
                </button>
            </form>
        </div>

    </div>
</nav>
