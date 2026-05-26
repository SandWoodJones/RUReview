<?php

$meal_labels = [
    'almoco' => 'Almoço',
    'janta'  => 'Jantar',
];


function render_stars(int $rating): string
{
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        $type  = $i <= $rating ? 'fa-solid' : 'fa-regular';
        $html .= "<i class=\"{$type} fa-star\"></i>";
    }
    return $html;
}

function fmt_date(string $date): string
{
    return date('d/m/Y', strtotime($date));
}
?>

<main class="max-w-4xl mx-auto px-4 py-8">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Avaliações</h1>

        <select
            id="sort-select"
            class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
            <option value="recent">Mais recentes</option>
            <option value="low">Menores notas</option>
            <option value="high">Maiores notas</option>
        </select>
    </div>

    <?php if (empty($reviews)): ?>
        <div class="text-center py-16 text-gray-400">
            <i class="fa-regular fa-comment-dots text-3xl mb-3"></i>
            <p class="text-sm">Nenhuma avaliação registrada ainda.</p>
        </div>
    <?php else: ?>
        <div id="reviews-list" class="grid gap-4">
            <?php foreach ($reviews as $review): ?>
                <div
                    class="review-card bg-white border border-gray-200 rounded-lg p-5 shadow-sm"
                    data-rating="<?= (int) $review['rating'] ?>"
                    data-date="<?= htmlspecialchars($review['created_at']) ?>">

                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-bold text-gray-800">
                                <?= htmlspecialchars($review['username']) ?>
                            </p>
                            <p class="text-xs text-gray-500">
                                Refeição:
                                <?= htmlspecialchars($meal_labels[$review['meal_type']] ?? $review['meal_type']) ?>
                                — <?= fmt_date($review['meal_date']) ?>
                            </p>
                        </div>

                        <div class="text-yellow-500 text-sm flex gap-0.5">
                            <?= render_stars((int) $review['rating']) ?>
                        </div>
                    </div>

                    <?php if (!empty($review['comment'])): ?>
                        <p class="text-gray-700 mt-2 text-sm">
                            <?= htmlspecialchars($review['comment']) ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($review['has_image']): ?>
                        <?php
                        $img_stmt = \App\Database\Connection::get()->prepare(
                            'SELECT id FROM image WHERE review_id = :rid LIMIT 1'
                        );
                        $img_stmt->execute([':rid' => $review['id']]);
                        $img = $img_stmt->fetch();
                        ?>
                        <?php if ($img): ?>
                            <div class="mt-3">
                                <button
                                    type="button"
                                    class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1"
                                    onclick="toggleImage(this, <?= (int) $img['id'] ?>)">
                                    <i class="fa-solid fa-image"></i> Ver foto
                                </button>
                                <img
                                    data-img-id="<?= (int) $img['id'] ?>"
                                    src=""
                                    alt="Foto da avaliação"
                                    class="hidden mt-2 max-h-64 rounded-lg border border-gray-200 object-contain">
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <p class="text-xs text-gray-400 mt-3">
                        <?= htmlspecialchars(date('d/m/Y H:i', strtotime($review['created_at']))) ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<script>
    const list = document.getElementById('reviews-list');

    document.getElementById('sort-select')?.addEventListener('change', function () {
        if (!list) return;
        const cards = [...list.querySelectorAll('.review-card')];

        cards.sort((a, b) => {
            const ra = parseInt(a.dataset.rating);
            const rb = parseInt(b.dataset.rating);
            const da = a.dataset.date;
            const db = b.dataset.date;

            if (this.value === 'low')    return ra - rb;
            if (this.value === 'high')   return rb - ra;
            return da < db ? 1 : -1; 
        });

        cards.forEach(c => list.appendChild(c));
    });

    function toggleImage(btn, imgId) {
        const img = btn.parentElement.querySelector('img');
        if (img.classList.contains('hidden')) {
            if (!img.src || img.src === window.location.href) {
                img.src = `/imagem/${imgId}`;
            }
            img.classList.remove('hidden');
            btn.innerHTML = '<i class="fa-solid fa-eye-slash"></i> Ocultar foto';
        } else {
            img.classList.add('hidden');
            btn.innerHTML = '<i class="fa-solid fa-image"></i> Ver foto';
        }
    }
</script>