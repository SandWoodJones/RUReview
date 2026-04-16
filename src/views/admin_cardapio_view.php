<?php
[$year, $mon] = array_map('intval', explode('-', $month));

$first_ts  = mktime(0, 0, 0, $mon, 1, $year);
$days      = (int) date('t', $first_ts);
$start_dow = (int) date('N', $first_ts); // 1 = Seg … 7 = Dom

$months_pt   = [
    '',
    'Janeiro',
    'Fevereiro',
    'Março',
    'Abril',
    'Maio',
    'Junho',
    'Julho',
    'Agosto',
    'Setembro',
    'Outubro',
    'Novembro',
    'Dezembro'
];
$month_label = $months_pt[$mon] . ' de ' . $year;

$prev_m = date('Y-m', mktime(0, 0, 0, $mon - 1, 1, $year));
$next_m = date('Y-m', mktime(0, 0, 0, $mon + 1, 1, $year));
$today  = date('Y-m-d');
?>

<main class="max-w-6xl mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gerenciar Cardápio</h1>
        <p class="text-sm text-gray-500">Clique em um dia para ver, editar ou cadastrar refeições.</p>
    </div>

    <?php if ($success ?? null): ?>
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded-lg text-sm">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($error ?? null): ?>
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded-lg text-sm">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="flex gap-6 items-stretch">
        <div class="w-96 shrink-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden h-full flex flex-col">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
                    <a href="?month=<?= $prev_m ?>" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
                        <i class="fa-solid fa-chevron-left text-sm"></i>
                    </a>
                    <span class="font-semibold text-gray-800"><?= $month_label ?></span>
                    <a href="?month=<?= $next_m ?>" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
                        <i class="fa-solid fa-chevron-right text-sm"></i>
                    </a>
                </div>

                <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-100 shrink-0">
                    <?php foreach (['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'] as $wd): ?>
                        <div class="text-center text-xs font-medium text-gray-400 py-2"><?= $wd ?></div>
                    <?php endforeach; ?>
                </div>

                <div class="grid grid-cols-7 flex-1" style="grid-auto-rows: 1fr">
                    <?php for ($i = 1; $i < $start_dow; $i++): ?>
                        <div class="border-b border-r border-gray-100"></div>
                    <?php endfor; ?>

                    <?php for ($d = 1; $d <= $days; $d++):
                        $ds       = sprintf('%04d-%02d-%02d', $year, $mon, $d);
                        $dow      = (int) date('N', mktime(0, 0, 0, $mon, $d, $year));
                        $is_today = $ds === $today;
                        $has_a    = isset($month_meals[$ds]['almoco']);
                        $has_j    = isset($month_meals[$ds]['janta']);
                    ?>
                        <div
                            data-date="<?= $ds ?>"
                            onclick="selectDay('<?= $ds ?>')"
                            class="day-cell relative h-14 p-1.5 border-b border-gray-100 <?= $dow < 7 ? 'border-r' : '' ?> cursor-pointer hover:bg-blue-50 transition-colors <?= $is_today ? 'bg-blue-50' : '' ?>">
                            <?php if ($is_today): ?>
                                <span class="w-6 h-6 inline-flex items-center justify-center rounded-full bg-blue-600 text-white text-xs font-semibold"><?= $d ?></span>
                            <?php else: ?>
                                <span class="text-sm text-gray-700"><?= $d ?></span>
                            <?php endif; ?>
                            <div class="flex gap-1 mt-1">
                                <?php if ($has_a): ?><span class="w-1.5 h-1.5 rounded-full bg-blue-400" title="Almoço"></span><?php endif; ?>
                                <?php if ($has_j): ?><span class="w-1.5 h-1.5 rounded-full bg-indigo-400" title="Janta"></span><?php endif; ?>
                            </div>
                        </div>
                    <?php endfor; ?>

                    <?php
                    $last_dow = (int) date('N', mktime(0, 0, 0, $mon, $days, $year));
                    for ($i = $last_dow + 1; $i <= 7; $i++):
                    ?>
                        <div class="h-14 border-b border-gray-100 <?= $i < 7 ? 'border-r' : '' ?>"></div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <div id="day-panel" class="flex-1 hidden"></div>

        <div id="day-placeholder" class="flex-1 flex flex-col items-center justify-center text-center text-gray-400 py-20 gap-3">
            <i class="fa-regular fa-calendar-days text-4xl"></i>
            <p class="text-sm">Selecione um dia no calendário para gerenciar as refeições.</p>
        </div>
    </div>

    <div class="flex gap-5 text-xs text-gray-400 px-1">
        <span class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-blue-400 inline-block"></span> Almoço
        </span>
        <span class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-indigo-400 inline-block"></span> Janta
        </span>
    </div>
</main>

<script>
    const allMeals = <?= json_encode($month_meals, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    const todayDate = '<?= $today ?>';
    const currentMonth = '<?= sprintf('%04d-%02d', $year, $mon) ?>';
    const autoSelect = <?= json_encode($selected ?? null) ?>;

    const MONTHS_PT = ['', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
    ];

    function fmtDate(s) {
        const [y, m, d] = s.split('-');
        return `${parseInt(d)} de ${MONTHS_PT[parseInt(m)]} de ${y}`;
    }

    function esc(s) {
        return s == null ? '' : String(s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function textInput(name, placeholder, value = '') {
        return `<input type="text" name="${name}" value="${esc(value)}" placeholder="${esc(placeholder)}"
        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400" required>`;
    }

    function beansSelect(current) {
        const opt = (v, l) => `<option value="${v}"${current === v ? ' selected' : ''}>${l}</option>`;
        return `<select name="beans" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400">
        ${opt('preto','Feijão Preto')}${opt('carioca','Feijão Carioca')}
    </select>`;
    }

    function buildFields(meal) {
        return `<div class="flex flex-col gap-2">
        ${textInput('protein',       'Proteína',        meal?.protein)}
        ${textInput('protein_vegan', 'Proteína Vegana/Vegetariana', meal?.protein_vegan)}
        ${beansSelect(meal?.beans)}
        ${textInput('carb_extra',    'Guarnição',      meal?.carb_extra)}
        ${textInput('salad_extra',   'Salada',    meal?.salad_extra)}
        ${textInput('dessert',       'Sobremesa',            meal?.dessert)}
    </div>`;
    }

    function buildMealCard(meal, label, icon, bgColor, textColor, btnColor, btnHover) {
        return `
        <div class="p-5 flex flex-col gap-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full ${bgColor} flex items-center justify-center shrink-0">
                    <i class="fa-solid ${icon} ${textColor} text-xs"></i>
                </div>
                <span class="font-semibold text-gray-800">${label}</span>
                <span class="text-xs text-green-600 bg-green-50 border border-green-200 rounded-full px-2 py-0.5 flex items-center gap-1">
                    <i class="fa-solid fa-check text-[10px]"></i> Cadastrado
                </span>
                <form method="POST" action="/admin/cardapio/delete" class="ml-auto"
                    onsubmit="return confirm('Tem certeza que deseja remover o ${label.toLowerCase()}?')">
                    <input type="hidden" name="meal_id" value="${meal.id}">
                    <button type="submit"
                        class="w-7 h-7 flex items-center justify-center rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 transition-colors cursor-pointer"
                        title="Remover ${label.toLowerCase()}">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </form>
            </div>
            <form method="POST" action="/admin/cardapio/update" class="flex flex-col gap-3">
                <input type="hidden" name="meal_id" value="${meal.id}">
                ${buildFields(meal)}
                <button type="submit" class="w-full py-2 rounded-lg text-sm font-semibold text-white ${btnColor} ${btnHover} transition-colors cursor-pointer">
                    Salvar alterações
                </button>
            </form>
        </div>`;
    }

    function buildEmptyCard(date, type, label, icon, bgColor, textColor, btnColor, btnHover) {
        return `
        <div class="p-5 flex flex-col gap-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                    <i class="fa-solid ${icon} text-gray-400 text-xs"></i>
                </div>
                <span class="font-semibold text-gray-800">${label}</span>
                <span class="ml-auto text-xs text-gray-400 bg-gray-50 border border-gray-200 rounded-full px-2 py-0.5">
                    Não cadastrado
                </span>
            </div>
            <form method="POST" action="/admin/cardapio" class="flex flex-col gap-3">
                <input type="hidden" name="date" value="${date}">
                <input type="hidden" name="type" value="${type}">
                ${buildFields(null)}
                <button type="submit" class="w-full py-2 rounded-lg text-sm font-semibold text-white ${btnColor} ${btnHover} transition-colors cursor-pointer">
                    Cadastrar ${label}
                </button>
            </form>
        </div>`;
    }

    function selectDay(date) {
        document.querySelectorAll('.day-cell').forEach(c =>
            c.classList.remove('ring-2', 'ring-inset', 'ring-blue-500'));

        const cell = document.querySelector(`[data-date="${date}"]`);
        if (cell) cell.classList.add('ring-2', 'ring-inset', 'ring-blue-500');

        const meals = allMeals[date] || {};

        const types = [{
                key: 'almoco',
                label: 'Almoço',
                icon: 'fa-sun',
                bg: 'bg-blue-100',
                text: 'text-blue-500',
                btn: 'bg-blue-600',
                hover: 'hover:bg-blue-700'
            },
            {
                key: 'janta',
                label: 'Jantar',
                icon: 'fa-moon',
                bg: 'bg-indigo-100',
                text: 'text-indigo-500',
                btn: 'bg-indigo-600',
                hover: 'hover:bg-indigo-700'
            },
        ];

        const cards = types.map(t => {
            const meal = meals[t.key];
            return meal ?
                buildMealCard(meal, t.label, t.icon, t.bg, t.text, t.btn, t.hover) :
                buildEmptyCard(date, t.key, t.label, t.icon, t.bg, t.text, t.btn, t.hover);
        }).join('');

        const panel = document.getElementById('day-panel');
        const placeholder = document.getElementById('day-placeholder');

        panel.classList.remove('hidden');
        placeholder.classList.add('hidden');

        panel.innerHTML = `
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden h-full flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
                <h3 class="font-semibold text-gray-800">
                    <i class="fa-regular fa-calendar text-gray-400 mr-2"></i>${fmtDate(date)}
                </h3>
                <button type="button" onclick="closePanel()"
                    class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors cursor-pointer">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            <div class="grid grid-cols-2 divide-x divide-gray-100 overflow-y-auto">
                ${cards}
            </div>
        </div>`;
    }

    function closePanel() {
        const panel = document.getElementById('day-panel');
        const placeholder = document.getElementById('day-placeholder');

        panel.classList.add('hidden');
        placeholder.classList.remove('hidden');

        document.querySelectorAll('.day-cell').forEach(c =>
            c.classList.remove('ring-2', 'ring-inset', 'ring-blue-500'));
    }

    if (autoSelect) {
        selectDay(autoSelect);
    } else if (currentMonth === todayDate.slice(0, 7)) {
        selectDay(todayDate);
    }
</script>
