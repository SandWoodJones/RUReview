<main class="max-w-5xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Avaliar cardápio</h1>
        <p class="text-sm text-gray-500"><?= date('d/m/Y') ?></p>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 flex flex-col gap-1">
            <?php foreach ($errors as $erro): ?>
                <span><?= htmlspecialchars($erro) ?></span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($refeicoes)): ?>
        <div class="text-center py-16 text-gray-400">
            <i class="fa-solid fa-utensils text-3xl mb-3"></i>
            <p class="text-sm text-gray-500">Nenhuma refeição cadastrada para hoje.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 group">
            <?php
            $tipos = [
                'almoco' => ['label' => 'Almoço',  'icon' => 'fa-sun',  'color' => 'blue'],
                'janta'  => ['label' => 'Jantar',   'icon' => 'fa-moon', 'color' => 'indigo'],
            ];
            foreach ($tipos as $tipo => $meta):
                if (!isset($refeicoes[$tipo])) continue;
                $refeicao = $refeicoes[$tipo];
                $jaAvaliou = review_exists($_SESSION['user_id'], $refeicao['id']);
                $color = $meta['color'];
            ?>

                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm flex flex-col overflow-hidden transition-all group-hover:brightness-90 hover:!brightness-100">
                    <div class="bg-<?= $color ?>-50 border-b border-<?= $color ?>-100 px-5 py-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-<?= $color ?>-100 flex items-center justify-center">
                            <i class="fa-solid <?= $meta['icon'] ?> text-<?= $color ?>-500 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800"><?= $meta['label'] ?></p>
                            <p class="text-xs text-gray-400">Cardápio de hoje</p>
                        </div>
                        <?php if ($jaAvaliou): ?>
                            <span class="ml-auto text-xs font-medium text-green-600 bg-green-50 border border-green-200 rounded-full px-2 py-0.5 flex items-center gap-1">
                                <i class="fa-solid fa-check text-[10px]"></i> Avaliado
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="px-5 py-4 border-b border-gray-100">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Cardápio</p>
                        <ul class="flex flex-col gap-2 text-sm text-gray-700">
                            <li class="flex items-start gap-2">
                                <i class="fa-solid fa-drumstick-bite text-gray-400 mt-0.5 w-4 shrink-0"></i>
                                <span><span class="font-medium">Proteína:</span> <?= htmlspecialchars($refeicao['protein']) ?></span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fa-solid fa-leaf text-green-400 mt-0.5 w-4 shrink-0"></i>
                                <span><span class="font-medium">Vegano/Veg.:</span> <?= htmlspecialchars($refeicao['protein_vegan']) ?></span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fa-solid fa-bowl-food text-gray-400 mt-0.5 w-4 shrink-0"></i>
                                <span><span class="font-medium">Feijão:</span> <?= $refeicao['beans'] === 'preto' ? 'Feijão Preto' : 'Feijão Carioca' ?></span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fa-solid fa-plate-wheat text-amber-400 mt-0.5 w-4 shrink-0"></i>
                                <span><span class="font-medium">Guarnição:</span> <?= htmlspecialchars($refeicao['carb_extra']) ?></span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fa-solid fa-seedling text-green-500 mt-0.5 w-4 shrink-0"></i>
                                <span><span class="font-medium">Salada:</span> <?= htmlspecialchars($refeicao['salad_extra']) ?></span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fa-solid fa-ice-cream text-pink-400 mt-0.5 w-4 shrink-0"></i>
                                <span><span class="font-medium">Sobremesa:</span> <?= htmlspecialchars($refeicao['dessert']) ?></span>
                            </li>
                        </ul>
                        <div class="mt-3 pt-3 border-t border-dashed border-gray-100 flex flex-wrap gap-2">
                            <?php foreach (['Arroz Branco + Integral', 'Mix de Folhas', 'Suco ou Água'] as $fixo): ?>
                                <span class="text-xs bg-gray-100 text-gray-500 rounded-full px-2.5 py-1"><?= $fixo ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php if ($jaAvaliou): ?>
                        <div class="px-5 py-6 flex-1 flex flex-col items-center justify-center text-center text-gray-400 gap-2">
                            <i class="fa-solid fa-circle-check text-2xl text-green-400"></i>
                            <p class="text-sm">Você já enviou sua avaliação para esta refeição.</p>
                        </div>
                    <?php else: ?>
                        <form action="/" method="POST" enctype="multipart/form-data" class="px-5 py-4 flex flex-col gap-4 flex-1">
                            <input type="hidden" name="meal_id" value="<?= $refeicao['id'] ?>">

                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Sua nota</p>
                                <div class="flex gap-2" data-star-group>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="rating" value="<?= $i ?>" class="sr-only" required>
                                            <i class="fa-solid fa-star text-2xl transition-colors star-btn" data-value="<?= $i ?>" style="color: #d1d5db;"></i>
                                        </label>
                                    <?php endfor; ?>
                                </div>
                                <p class="rating-label text-xs text-gray-400 mt-1">Clique para avaliar</p>
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1 block">
                                    Comentário <span class="font-normal normal-case">(opcional)</span>
                                </label>
                                <textarea name="comment" rows="3" maxlength="500"
                                    placeholder="Descreva sua experiência..."
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 resize-none focus:outline-none focus:ring-1 focus:ring-<?= $color ?>-400"></textarea>
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1 block">
                                    Foto <span class="font-normal normal-case">(opcional)</span>
                                </label>
                                <label class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-gray-300 text-sm text-gray-600 bg-white cursor-pointer hover:bg-gray-50 transition-colors">
                                    <i class="fa-solid fa-upload text-gray-400 text-xs"></i>
                                    <span class="file-label">Escolher imagem</span>
                                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="sr-only file-input">
                                </label>
                                <p class="text-xs text-gray-400 mt-1">JPG, PNG ou WEBP · máx. 2 MB</p>
                            </div>

                            <button type="submit"
                                class="mt-auto w-full py-2.5 rounded-xl text-sm font-semibold text-white bg-<?= $color ?>-600 hover:bg-<?= $color ?>-700 active:scale-95 transition-all cursor-pointer">
                                Enviar avaliação
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<script>
    const labels = ['', 'Péssimo', 'Ruim', 'Regular', 'Bom', 'Ótimo'];

    document.querySelectorAll('[data-star-group]').forEach(group => {
        const stars = group.querySelectorAll('.star-btn');
        const ratingLabel = group.closest('form').querySelector('.rating-label');
        let selected = 0;

        function paint(upTo, hover) {
            stars.forEach(s => {
                const v = parseInt(s.dataset.value);
                s.style.color = v <= upTo ? (hover ? '#b45309' : '#f59e0b') : (v <= selected ? '#f59e0b' : '#d1d5db');
            });
        }

        stars.forEach(star => {
            star.addEventListener('mouseover', () => paint(parseInt(star.dataset.value), true));
            star.addEventListener('mouseout', () => paint(selected, false));
            star.addEventListener('click', () => {
                selected = parseInt(star.dataset.value);
                star.previousElementSibling.checked = true;
                paint(selected, false);
                ratingLabel.textContent = labels[selected];
            });
        });
    });

    document.querySelectorAll('.file-input').forEach(input => {
        input.addEventListener('change', function() {
            this.closest('label').querySelector('.file-label').textContent =
                this.files.length ? this.files[0].name : 'Escolher imagem';
        });
    });
</script>
