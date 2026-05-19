<?php
// $commune  (array)
// $vudCat   (int)
$vudCat ??= VUD_CATEGORIE_ID;
?>
<aside style="position:sticky;top:88px;display:flex;flex-direction:column;gap:16px;">

    <!-- Widget devis -->
    <?php
    $villeName = $commune['nom'] ?? 'votre ville';
    $context   = 'ville';
    require __DIR__ . '/cta-devis.php';
    ?>

    <!-- Aides rapides -->
    <div style="background:var(--cream);border:1px solid var(--stone);border-radius:var(--radius);padding:18px;">
        <h3 style="font-weight:700;color:var(--forest);font-size:13px;margin-bottom:12px;">✅ Aides disponibles</h3>
        <div style="display:flex;flex-direction:column;gap:8px;">
            <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text-muted);">
                <span style="color:var(--forest);font-weight:700;">✓</span>
                TVA <strong style="color:var(--text);margin-left:3px;">10%</strong> travaux de chauffage
            </div>
            <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text-muted);">
                <span style="color:var(--forest);font-weight:700;">✓</span>
                financement RGE dispo
            </div>
            <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text-muted);">
                <span style="color:var(--forest);font-weight:700;">✓</span>
                Devis gratuit · 48h
            </div>
            <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text-muted);">
                <span style="color:var(--forest);font-weight:700;">✓</span>
                solutions chauffage sur mesure
            </div>
        </div>
    </div>
</aside>

