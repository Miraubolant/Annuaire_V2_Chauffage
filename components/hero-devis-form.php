<?php
// $h1       (string)
// $subtitle (string)
// $commune  (array)
$zone       = $commune['aides_etat']['zone_climatique'] ?? '';
$zoneLabels = ['H1' => 'Zone H1 — Nord', 'H2' => 'Zone H2 — Centre', 'H3' => 'Zone H3 — Sud'];
$nbArt      = getCompteurArtisans($commune);
?>
<div style="background:linear-gradient(135deg,var(--forest-dark) 0%,var(--forest) 100%);border-radius:var(--radius-xl);margin-bottom:32px;box-shadow:var(--shadow-xl);overflow:hidden;">
    <div class="hero-row">

        <!-- ── Gauche : texte ─────────────────────────────────────────────── -->
        <div class="hero-text-col" style="padding:36px 32px;">
            <div style="position:absolute;inset:0;background:url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.03\'%3E%3Ccircle cx=\'30\' cy=\'30\' r=\'2\'/%3E%3C/g%3E%3C/svg%3E');pointer-events:none;"></div>

            <?php if ($zone): ?>
            <div style="margin-bottom:14px;">
                <span style="background:rgba(232,184,75,.18);border:1px solid rgba(232,184,75,.3);color:var(--gold-light);font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;padding:4px 12px;border-radius:100px;">
                    📍 <?= htmlspecialchars($zoneLabels[$zone] ?? 'Zone ' . $zone) ?>
                </span>
            </div>
            <?php endif; ?>

            <h1 style="font-family:var(--font-display);font-size:clamp(20px,3vw,30px);font-weight:700;line-height:1.2;letter-spacing:-.02em;margin-bottom:10px;color:#fff;">
                <?= htmlspecialchars($h1) ?>
            </h1>
            <p style="color:rgba(255,255,255,.65);font-size:14px;margin-bottom:24px;line-height:1.6;">
                <?= htmlspecialchars($subtitle) ?>
            </p>

            <div style="display:flex;flex-direction:column;gap:8px;">
                <div style="display:flex;align-items:center;gap:8px;color:rgba(255,255,255,.7);font-size:13px;">
                    <span style="width:20px;height:20px;background:rgba(232,184,75,.2);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;flex-shrink:0;">✓</span>
                    Installation & entretien chauffage
                </div>
                <div style="display:flex;align-items:center;gap:8px;color:rgba(255,255,255,.7);font-size:13px;">
                    <span style="width:20px;height:20px;background:rgba(232,184,75,.2);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;flex-shrink:0;">✓</span>
                    chauffagistes professionnels
                </div>
                <div style="display:flex;align-items:center;gap:8px;color:rgba(255,255,255,.7);font-size:13px;">
                    <span style="width:20px;height:20px;background:rgba(232,184,75,.2);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;flex-shrink:0;">✓</span>
                    Devis gratuit · Réponse en 24h
                </div>
            </div>
        </div>

        <!-- ── Droite : widget sur fond blanc ─────────────────────────────── -->
        <div class="hero-widget-col">
            <div style="padding:20px 24px 16px;border-bottom:1px solid #f0ebe1;">
                <p style="font-weight:700;font-size:14px;color:#EA580C;margin-bottom:2px;">Devis gratuit chauffage</p>
                <p style="font-size:12px;color:#6b6762;">Comparez les chauffagistes qualifiés · Sans engagement</p>
            </div>

            <!-- Widget ViteUnDevis — catégorie Chauffage -->
            <div style="flex:1;padding:16px 24px;background:#ffffff;color:#1a1a1a;">
                <div id="v2e29b6034ad"></div>
                <script>
                    vud_partenaire_id = '<?= VUD_PARTENAIRE_ID ?>';
                    vud_categorie_id  = '<?= VUD_CATEGORIE_ID ?>';
                    var vud_js = document.createElement('script');
                    vud_js.type = 'text/javascript';
                    vud_js.src = '//www.viteundevis.com/2e29b6034a/' + vud_partenaire_id + '/' + vud_categorie_id + '/';
                    var s = document.getElementsByTagName('script')[0];
                    s.parentNode.insertBefore(vud_js, s);
                </script>
            </div>

            <div style="padding:16px 24px;border-top:1px solid #f0ebe1;background:#faf8f3;">
                <a href="<?= VUD_DEVIS_URL ?>"
                   style="display:flex;align-items:center;justify-content:center;gap:6px;background:var(--gold);color:#fff;font-weight:700;font-size:14px;padding:13px 20px;border-radius:100px;text-decoration:none;box-shadow:0 3px 14px rgba(200,150,62,.4);"
                   target="_blank" rel="noopener sponsored">
                    Obtenir mon devis gratuit
                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
                </a>
            </div>
        </div>

    </div>
</div>

