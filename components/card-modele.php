<?php
// $modele      (array) — {slug, nom, emoji, vud_cat}
// $commune     (array) — données commune
// $regionSlug, $deptSlug (string)
$url = urlModele($regionSlug, $deptSlug, $commune['slug'], $commune['code_postal'], $modele['slug']);
?>
<a href="<?= htmlspecialchars($url) ?>"
   class="flex items-center gap-3 bg-white rounded-xl border border-gray-100 p-3 hover:border-blue-300 hover:shadow-sm transition-all group">
    <span class="text-2xl flex-shrink-0"><?= $modele['emoji'] ?></span>
    <span class="text-sm font-medium text-gray-700 group-hover:text-blue-600 transition-colors">
        <?= htmlspecialchars($modele['nom']) ?>
    </span>
    <span class="ml-auto text-gray-300 group-hover:text-blue-400 transition-colors text-sm">→</span>
</a>
