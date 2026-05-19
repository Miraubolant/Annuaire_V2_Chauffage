<?php
// $artisan  (array) — {id, nom, slug, telephone, site_web, adresse, note, avis, type}
// $commune  (array) — pour construire l'URL de la fiche
// $regionSlug, $deptSlug (string)
$note  = (float) ($artisan['note'] ?? 0);
$avis  = (int)   ($artisan['avis'] ?? 0);
$stars = min(5, max(0, round($note)));
$ficheUrl = urlArtisan($regionSlug, $deptSlug, $commune['slug'], $commune['code_postal'], $artisan['slug']);
?>
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow p-5">
    <!-- En-tête -->
    <div class="flex items-start justify-between gap-3 mb-3">
        <div>
            <h3 class="font-bold text-gray-800 text-base leading-tight">
                <a href="<?= htmlspecialchars($ficheUrl) ?>" class="hover:text-blue-600 transition-colors">
                    <?= htmlspecialchars($artisan['nom']) ?>
                </a>
            </h3>
            <?php if (!empty($artisan['type'])): ?>
            <p class="text-gray-400 text-xs mt-0.5"><?= htmlspecialchars($artisan['type']) ?></p>
            <?php endif; ?>
        </div>
        <?php if ($note > 0): ?>
        <div class="flex-shrink-0 text-right">
            <div class="flex gap-0.5" aria-label="Note <?= $note ?>/5">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                <span class="text-<?= $i <= $stars ? 'yellow' : 'gray' ?>-400 text-sm">★</span>
                <?php endfor; ?>
            </div>
            <p class="text-xs text-gray-500 mt-0.5"><?= formatNote($note) ?> · <?= $avis ?> avis</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Adresse -->
    <?php if (!empty($artisan['adresse'])): ?>
    <p class="text-gray-500 text-xs mb-3 flex items-center gap-1">
        <span>📍</span>
        <?= htmlspecialchars(truncate($artisan['adresse'], 60)) ?>
    </p>
    <?php endif; ?>

    <!-- Actions -->
    <div class="flex flex-wrap gap-2 mt-3">
        <?php if (!empty($artisan['telephone'])): ?>
        <a href="tel:<?= preg_replace('/\s/', '', $artisan['telephone']) ?>"
           class="flex items-center gap-1 bg-slate-50 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-full hover:bg-slate-100 transition-colors">
            📞 <?= htmlspecialchars(formatPhone($artisan['telephone'])) ?>
        </a>
        <?php endif; ?>

        <?php if (!empty($artisan['site_web'])): ?>
        <a href="<?= htmlspecialchars($artisan['site_web']) ?>"
           class="flex items-center gap-1 bg-gray-100 text-gray-600 text-xs font-semibold px-3 py-1.5 rounded-full hover:bg-gray-200 transition-colors"
           target="_blank" rel="noopener nofollow">
            🌐 Site web
        </a>
        <?php endif; ?>

        <a href="<?= htmlspecialchars($ficheUrl) ?>"
           class="flex items-center gap-1 bg-blue-50 text-blue-600 text-xs font-semibold px-3 py-1.5 rounded-full hover:bg-blue-100 transition-colors ml-auto">
            Voir la fiche →
        </a>
    </div>
</div>
