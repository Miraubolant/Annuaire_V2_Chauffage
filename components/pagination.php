<?php
// $page      (int) — page courante (1-indexed)
// $totalPages (int)
// $baseUrl   (string) — URL de base sans paramètre page
$page       ??= 1;
$totalPages ??= 1;
$baseUrl    ??= '';

if ($totalPages <= 1) return;

// Noindex sur pages > 2
if ($page > 2): ?>
<?php endif; ?>

<nav aria-label="Pagination" class="flex items-center justify-center gap-2 mt-8">
    <?php if ($page > 1): ?>
    <a href="<?= htmlspecialchars($baseUrl . ($page > 2 ? '?page=' . ($page - 1) : '')) ?>"
       class="px-4 py-2 rounded-lg border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
        ← Précédent
    </a>
    <?php endif; ?>

    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
    <a href="<?= htmlspecialchars($baseUrl . ($i > 1 ? '?page=' . $i : '')) ?>"
       style="<?= $i === $page ? 'background:var(--forest);color:#fff;border:1px solid var(--forest);' : '' ?>"
       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
              <?= $i === $page ? '' : 'border border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
        <?= $i ?>
    </a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
    <a href="<?= htmlspecialchars($baseUrl . '?page=' . ($page + 1)) ?>"
       class="px-4 py-2 rounded-lg border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
        Suivant →
    </a>
    <?php endif; ?>
</nav>
