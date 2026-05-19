<?php
// $dept        (array) — {code, nom, slug, artisans_chauffage, communes_avec_artisans}
// $regionSlug  (string)
$url  = urlDepartement($regionSlug, $dept['slug']);
$nb   = $dept['artisans_chauffage'] ?? 0;
$com  = $dept['communes_avec_artisans'] ?? 0;
?>
<a href="<?= htmlspecialchars($url) ?>"
   class="block bg-white rounded-xl border border-gray-100 p-4 hover:border-blue-300 hover:shadow-sm transition-all">
    <div class="flex items-center gap-3 mb-2">
        <span class="bg-gray-100 text-gray-600 font-bold text-sm px-2 py-1 rounded-lg">
            <?= htmlspecialchars($dept['code']) ?>
        </span>
        <p class="font-semibold text-gray-800 text-sm"><?= htmlspecialchars($dept['nom']) ?></p>
    </div>
    <div class="flex items-center justify-between text-xs text-gray-500">
        <span><?= number_format($nb, 0, ',', ' ') ?> <?= METIER_PLURIEL ?></span>
        <span><?= $com ?> communes</span>
    </div>
</a>
