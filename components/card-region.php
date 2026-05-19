<?php
// $region (array) — {region:{slug,nom}, stats:{communes_total,artisans_chauffage}, departements:[]}
$url = urlRegion($region['region']['slug']);
$nbDepts = count($region['departements'] ?? []);
$nbArtisans = $region['stats']['artisans_chauffage'] ?? 0;
$nbCommunes = $region['stats']['communes_avec_artisans'] ?? 0;
?>
<a href="<?= htmlspecialchars($url) ?>"
   class="block bg-white rounded-xl border border-gray-100 p-5 hover:border-blue-300 hover:shadow-md transition-all">
    <h3 class="font-bold text-gray-800 mb-2"><?= htmlspecialchars($region['region']['nom']) ?></h3>
    <div class="space-y-1 text-sm text-gray-500">
        <p>🗺️ <?= $nbDepts ?> département<?= $nbDepts > 1 ? 's' : '' ?></p>
        <p>🏘️ <?= number_format($nbCommunes, 0, ',', ' ') ?> communes couvertes</p>
        <p class="font-semibold text-blue-600">
            <?= number_format($nbArtisans, 0, ',', ' ') ?> <?= METIER_PLURIEL ?>
        </p>
    </div>
    <p class="text-blue-500 text-xs mt-3">Voir les chauffagistes →</p>
</a>
