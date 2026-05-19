<?php
// $ville       (array) — données commune
// $regionSlug  (string)
// $deptSlug    (string)
$nb   = getCompteurArtisans($ville);
$zone = $ville['aides_etat']['zone_climatique'] ?? '';
$url  = urlVille($regionSlug, $deptSlug, $ville['slug'], $ville['code_postal']);
$zoneColors = ['H1' => 'blue', 'H2' => 'green', 'H3' => 'orange'];
$zc = $zoneColors[$zone] ?? 'gray';
?>
<a href="<?= htmlspecialchars($url) ?>"
   class="block bg-white rounded-xl border border-gray-100 p-4 hover:border-blue-300 hover:shadow-sm transition-all">
    <div class="flex items-start justify-between gap-2">
        <div>
            <p class="font-semibold text-gray-800 text-sm"><?= htmlspecialchars($ville['nom']) ?></p>
            <p class="text-gray-400 text-xs"><?= htmlspecialchars($ville['code_postal']) ?></p>
        </div>
        <?php if ($zone): ?>
        <span class="bg-<?= $zc ?>-100 text-<?= $zc ?>-700 text-xs font-bold px-2 py-0.5 rounded-full flex-shrink-0">
            <?= htmlspecialchars($zone) ?>
        </span>
        <?php endif; ?>
    </div>
    <div class="flex items-center justify-between mt-3">
        <span class="text-<?= $nb > 0 ? 'blue' : 'gray' ?>-600 font-bold text-sm">
            <?= getCompteurLabel($nb) ?>
        </span>
        <span class="text-gray-400 text-xs">
            <?= number_format($ville['population'] ?? 0, 0, ',', ' ') ?> hab.
        </span>
    </div>
</a>
