<?php
// $vudCat    (int)    — catégorie ViteUnDevis
// $villeName (string)
// $context   (string)
$vudCat    ??= VUD_CATEGORIE_ID;
$villeName ??= 'votre ville';
$context   ??= 'ville';
?>
<div style="background:linear-gradient(135deg,var(--forest-dark) 0%,var(--forest) 100%);border-radius:var(--radius-lg);padding:24px;color:#fff;text-align:center;box-shadow:var(--shadow-lg);">
    <div style="font-size:28px;margin-bottom:10px;">🔥</div>
    <h3 style="font-family:var(--font-display);font-size:18px;font-weight:700;margin-bottom:6px;color:#fff;">devis chauffage gratuit</h3>
    <p style="color:rgba(255,255,255,.65);font-size:13px;margin-bottom:16px;line-height:1.5;">
        Comparez les offres des chauffagistes
        <?= htmlspecialchars(articleVille($villeName)) ?> en 2 minutes.
    </p>
    <a href="<?= VUD_DEVIS_URL ?>"
       style="display:flex;align-items:center;justify-content:center;gap:6px;background:var(--gold);color:#fff;font-weight:700;font-size:14px;padding:13px 20px;border-radius:100px;text-decoration:none;box-shadow:0 3px 14px rgba(200,150,62,.4);margin-bottom:8px;"
       target="_blank" rel="noopener sponsored">
        Obtenir mon devis gratuit
        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
    </a>
    <p style="font-size:11px;color:rgba(255,255,255,.4);">Gratuit et sans engagement</p>
</div>

