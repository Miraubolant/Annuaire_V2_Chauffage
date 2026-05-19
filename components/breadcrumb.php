<?php
// $trail = [['name' => '...', 'url' => '...'], ...]
// Dernier élément = page courante (sans lien)
?>
<nav aria-label="Fil d'ariane">
    <ol style="display:flex;flex-wrap:wrap;align-items:center;gap:0;list-style:none;margin:0;padding:0;">
        <?php foreach ($trail as $i => $crumb): ?>
        <?php $isLast = ($i === count($trail) - 1); ?>
        <li style="display:flex;align-items:center;">
            <?php if ($i > 0): ?>
            <svg width="12" height="12" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"
                 style="margin:0 6px;color:var(--text-muted);opacity:.4;flex-shrink:0;">
                <path d="M7 5l6 5-6 5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <?php endif; ?>

            <?php if ($isLast): ?>
            <span style="font-size:12px;font-weight:600;color:var(--text);max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                  aria-current="page"
                  title="<?= htmlspecialchars($crumb['name']) ?>">
                <?= htmlspecialchars($crumb['name']) ?>
            </span>
            <?php else: ?>
            <a href="<?= htmlspecialchars($crumb['url']) ?>"
               style="display:inline-flex;align-items:center;gap:4px;font-size:12px;color:var(--text-muted);text-decoration:none;white-space:nowrap;transition:color .12s;"
               onmouseover="this.style.color='var(--forest)'"
               onmouseout="this.style.color='var(--text-muted)'">
                <?php if ($i === 0): ?>
                <svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor" style="flex-shrink:0;">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                <?php endif; ?>
                <?= htmlspecialchars($crumb['name']) ?>
            </a>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ol>
</nav>
