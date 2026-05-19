<?php
// $commune (array) — données complètes de la commune
// $aides   (array) — résultat de getAidesForCommune($commune)
$zone     = $aides['zone'] ?? 'H2';
$locales  = $aides['locales'] ?? [];
$nationales = $aides['nationales'] ?? [];

$zoneColors = ['H1' => 'blue', 'H2' => 'green', 'H3' => 'orange'];
$zoneColor  = $zoneColors[$zone] ?? 'gray';
$zoneLabel  = ZONES_CLIMATIQUES[$zone]['label'] ?? 'Zone ' . $zone;
$zoneDesc   = ZONES_CLIMATIQUES[$zone]['description'] ?? '';
?>

<section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">
        💶 Aides financières disponibles
    </h2>

    <!-- Zone climatique -->
    <div class="flex items-center gap-3 mb-5 p-3 bg-<?= $zoneColor ?>-50 border border-<?= $zoneColor ?>-200 rounded-xl">
        <span class="bg-<?= $zoneColor ?>-600 text-white text-xs font-bold px-2 py-1 rounded-full"><?= htmlspecialchars($zone) ?></span>
        <div>
            <p class="font-semibold text-<?= $zoneColor ?>-800 text-sm"><?= htmlspecialchars($zoneLabel) ?></p>
            <p class="text-<?= $zoneColor ?>-600 text-xs"><?= htmlspecialchars($zoneDesc) ?></p>
        </div>
    </div>

    <!-- Badges dispositifs locaux -->
    <?php if (!empty($locales)): ?>
    <div class="flex flex-wrap gap-2 mb-5">
        <?php if (isset($locales['qpv'])): ?>
        <span class="bg-purple-100 text-purple-700 text-xs font-semibold px-3 py-1 rounded-full">
            🏙️ Quartier Prioritaire (QPV)
        </span>
        <?php endif; ?>
        <?php if (isset($locales['acv'])): ?>
        <span class="bg-amber-100 text-amber-700 text-xs font-semibold px-3 py-1 rounded-full">
            🏛️ Action Cœur de Ville
        </span>
        <?php endif; ?>
        <?php if (isset($locales['pvd'])): ?>
        <span class="bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1 rounded-full">
            🌿 Petites Villes de Demain
        </span>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Aides nationales -->
    <div class="space-y-3">
        <?php foreach ($nationales as $key => $aide): ?>
        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl">
            <span class="text-green-600 font-bold text-lg mt-0.5">✓</span>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-800 text-sm"><?= htmlspecialchars($aide['nom']) ?></p>
                <p class="text-gray-500 text-xs mt-0.5"><?= htmlspecialchars($aide['montant']) ?></p>
                <p class="text-gray-400 text-xs mt-0.5 line-clamp-2"><?= htmlspecialchars($aide['conditions']) ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Aides locales détaillées -->
    <?php foreach ($locales as $type => $locale): ?>
    <div class="mt-3 p-3 bg-purple-50 border border-purple-100 rounded-xl">
        <p class="font-semibold text-purple-800 text-sm mb-1"><?= htmlspecialchars($locale['nom']) ?></p>
        <p class="text-purple-600 text-xs"><?= htmlspecialchars($locale['description']) ?></p>
        <?php if (!empty($locale['avantages'])): ?>
        <ul class="mt-2 space-y-0.5">
            <?php foreach ($locale['avantages'] as $av): ?>
            <li class="text-purple-500 text-xs">• <?= htmlspecialchars($av) ?></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</section>

