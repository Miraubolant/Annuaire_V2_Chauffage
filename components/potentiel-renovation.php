<?php
// $potentiel (array) — résultat de getPotentielRenovation($commune)
$pct      = $potentiel['pct_avant_1990'] ?? 0;
$niveau   = $potentiel['niveau'] ?? 'moyen';
$segment  = $potentiel['segment'] ?? 'intermédiaire';
$maisons  = $potentiel['maisons'] ?? 0;
$apparts  = $potentiel['appartements'] ?? 0;
$revenu   = $potentiel['revenu_median'] ?? 0;

$niveauColors = ['fort' => 'red', 'moyen' => 'amber', 'faible' => 'green'];
$color = $niveauColors[$niveau] ?? 'gray';

$segmentMessages = [
    'aisé'          => 'Marché aisé — priorité qualité et esthétique',
    'intermédiaire' => 'Profil mixte — équilibre budget/performance',
    'modeste'       => 'Aides prioritaires — CEE BAR-EN-101 et MaPrimeRénov\' fortement conseillés',
];
$message = $segmentMessages[$segment] ?? '';
?>

<section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">
        🏠 Potentiel de rénovation
    </h2>

    <!-- Barre de progression -->
    <div class="mb-4">
        <div class="flex justify-between items-center mb-1">
            <span class="text-sm text-gray-600">Logements construits avant 1990</span>
            <span class="font-bold text-<?= $color ?>-600 text-lg"><?= $pct ?>%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-<?= $color ?>-500 h-3 rounded-full transition-all"
                 style="width: <?= min($pct, 100) ?>%"></div>
        </div>
        <p class="text-xs text-gray-400 mt-1">
            <?= number_format($potentiel['logements_avant_1990'], 0, ',', ' ') ?> logements sur
            <?= number_format($potentiel['logements_total'], 0, ',', ' ') ?>
        </p>
    </div>

    <!-- Stats logement -->
    <div class="grid grid-cols-2 gap-3 mb-4">
        <div class="text-center p-3 bg-gray-50 rounded-xl">
            <p class="text-2xl font-bold text-gray-800"><?= number_format($maisons, 0, ',', ' ') ?></p>
            <p class="text-xs text-gray-500">Maisons</p>
        </div>
        <div class="text-center p-3 bg-gray-50 rounded-xl">
            <p class="text-2xl font-bold text-gray-800"><?= number_format($apparts, 0, ',', ' ') ?></p>
            <p class="text-xs text-gray-500">Appartements</p>
        </div>
    </div>

    <!-- Revenu médian -->
    <div class="flex items-center gap-3 p-3 bg-<?= $color ?>-50 border border-<?= $color ?>-100 rounded-xl">
        <span class="text-2xl">💰</span>
        <div>
            <p class="font-semibold text-gray-800 text-sm">
                Revenu médian : <?= number_format($revenu, 0, ',', ' ') ?> €/an
            </p>
            <p class="text-<?= $color ?>-600 text-xs"><?= htmlspecialchars($message) ?></p>
        </div>
    </div>
</section>
