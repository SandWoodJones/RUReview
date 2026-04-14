<?php
require_once __DIR__ . '/../models/avaliar_model.php';

function avaliar_page(): void {
    $refeicoes = get_refeicoes_hoje();
    require_once __DIR__ . '/../views/avaliar_view.php';
}
