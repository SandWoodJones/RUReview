<main class="max-w-2xl mx-auto px-4 py-6">

    <div class="mb-4">
    <h1 class="text-lg font-medium text-gray-800">Avaliar cardápio</h1>
    <p class="text-sm text-gray-500"><?= date('d/m/Y') ?></p>
    </div>

    <?php if ($success): ?>
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">
            Avaliação enviada com sucesso!
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 flex flex-col gap-1">
            <?php foreach ($errors as $erro): ?>
                <span><?= htmlspecialchars($erro) ?></span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($refeicoes)): ?>
        <p class="text-sm text-gray-500">Nenhuma refeição cadastrada para hoje.</p>
    <?php else: ?>
        <form action="/" method="POST" enctype="multipart/form-data" class="bg-white border border-gray-200 rounded-x1 p-5 flex flex-col gap-5">
            <fieldset>
                <legend class="text-sm font-medium text-gray-700 mb-2">Refeição</legend>
                <div class="flex gap-3">
                    <?php foreach ($refeicoes as $tipo => $refeicao): ?>
                        <label class="cursor-pointer">
                            <input type="radio" name="meal_id" value="<?= $refeicao['id'] ?>" class="sr-only meal-radio" required>
                            <span class="px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-600 meal-btn transition-colors">
                                <?= $tipo === 'almoco' ? 'Almoço' : 'Janta' ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <fieldset>
                <legend class="text-sm font-medium text-gray-700 mb-2">Nota Geral</legend>
                <div class="flex gap-3" id="star-rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="<?= $i ?>" class="sr-only" required>
                            <i class="fa-solid fa-star text-2xl transition-colors star-btn" data-value="<?= $i ?>"></i>
                        </label>
                    <?php endfor; ?>
                </div>
                <p id="rating-label" class="text-xs text-gray-400 mt-1">Clique para avaliar</p>
            </fieldset>

            <div>
                <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">
                    Comentário <span class="font-normal text-gray-400">(opcional)</span>
                </label>
                <textarea id="comment" name="comment" rows="3" placeholder="Descreva sua experiência..." maxlength="500"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 resize-none focus:outline-none focus:ring-1 focus:ring-green-500"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Foto <span class="font-normal text-gray-400">(opcional)</span>
                </label>
                <label for="image" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-600 bg-white cursor-pointer hover:bg-gray-50 hover:border-gray-400 transition-colors">
                    <i class="fa-solid fa-upload text-gray-500"></i>
                    <span id="file-label">Escolher imagem</span>
                </label>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp" class="sr-only">
                <p class="text-xs text-gray-400 mt-2">JPG, PNG ou WEBP • máx. 2 MB</p>
            </div>

            <button type="submit" class="w-full py-2 rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700 active:scale-95 transition-all cursor-pointer">
                Enviar
            </button>
        </form>
    <?php endif; ?>
</main>

<script>
    document.querySelectorAll('.meal-radio').forEach(radio => {
        radio.addEventListener('change', () => {
            document.querySelectorAll('.meal-btn').forEach(btn => {
                btn.classList.remove('bg-green-50', 'border-green-500', 'text-green-700');
            });
            radio.nextElementSibling.classList.add('bg-green-50', 'border-green-500', 'text-green-700');
        });
    });

    const stars = document.querySelectorAll('.star-btn');
    const ratingLabel = document.getElementById('rating-label');
    const labels = ['', 'Péssimo', 'Ruim', 'Regular', 'Bom', 'Ótimo'];
    let selected = 0;

    function paint(upTo, isHover) {
        stars.forEach(s => {
            const val = parseInt(s.dataset.value);
            if (val <= upTo) {
                s.style.color = isHover ? '#b45309' : '#f59e0b';
            } else {
                s.style.color = selected >= val ? '#f59e0b' : '#d1d5db';
            }
        });
    }

    stars.forEach(star => {
        star.style.color = '#d1d5db';
        star.addEventListener('mouseover', () => paint(parseInt(star.dataset.value), true));
        star.addEventListener('mouseout', () => paint(selected, false));
        star.addEventListener('click', () => {
            selected = parseInt(star.dataset.value);
            star.previousElementSibling.checked = true;
            paint(selected, false);
            ratingLabel.textContent = labels[selected];
        });
    });

    document.getElementById('image').addEventListener('change', function() {
        document.getElementById('file-label').textContent =
            this.files.length ? this.files[0].name : 'Escolher imagem';
    });
</script>
