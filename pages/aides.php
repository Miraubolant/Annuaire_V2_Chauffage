<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

$title         = 'Aides pour votre chauffage — TVA 5,5%, financement RGE — ' . SITE_NAME;
$description   = 'Toutes les aides financières pour vos travaux de chauffage : TVA à 5,5%, crédit d\'impôt pompe à chaleur, financement RGE. Renseignez-vous auprès de votre chauffagiste qualifié.';
$canonical_url = SITE_URL . '/aides/';
$robots        = 'index,follow';
$jsonLd        = [];

$trail = [
    ['name' => 'Accueil', 'url' => SITE_URL . '/'],
    ['name' => 'Aides à la rénovation', 'url' => $canonical_url],
];

require __DIR__ . '/../templates/header.php';
?>

<!-- ─── Hero ─────────────────────────────────────────────────────────────────── -->
<div style="background:var(--forest-dark);padding:56px 24px 48px;text-align:center;">
    <div style="max-width:780px;margin:0 auto;">
        <span class="section-eyebrow" style="color:rgba(255,255,255,.65);background:rgba(255,255,255,.1);margin-bottom:16px;">
            💶 Financements 2026
        </span>
        <h1 style="font-family:var(--font-display);font-size:clamp(26px,4vw,44px);font-weight:700;color:#fff;line-height:1.2;letter-spacing:-.02em;margin-bottom:14px;">
            Aides et financements<br>pour votre chauffage
        </h1>
        <p style="font-size:16px;color:rgba(255,255,255,.65);line-height:1.7;max-width:560px;margin:0 auto 28px;">
            TVA 5,5%, crédit d'impôt pompe à chaleur, financement RGE — des solutions pour réduire le coût de votre projet chauffage.
        </p>
        <div style="display:flex;flex-wrap:wrap;gap:10px;justify-content:center;">
            <span style="background:rgba(255,255,255,.12);color:#fff;font-size:12px;font-weight:600;padding:6px 14px;border-radius:100px;">✓ TVA 5,5% sur les travaux</span>
            <span style="background:rgba(255,255,255,.12);color:#fff;font-size:12px;font-weight:600;padding:6px 14px;border-radius:100px;">✓ financement RGE dispo</span>
            <span style="background:rgba(255,255,255,.12);color:#fff;font-size:12px;font-weight:600;padding:6px 14px;border-radius:100px;">✓ Devis gratuit · Réponse 48h</span>
        </div>
    </div>
</div>

<main style="max-width:1100px;margin:0 auto;padding:48px 24px;">
    <?php require __DIR__ . '/../components/breadcrumb.php'; ?>

    <!-- ─── Aides nationales ──────────────────────────────────────────────────── -->
    <section style="margin-bottom:64px;">
        <div style="margin-bottom:32px;">
            <span class="section-eyebrow">🏛️ Aides nationales</span>
            <h2 class="section-title" style="margin-top:8px;">Financements disponibles pour votre chauffage</h2>
            <p class="section-subtitle">Ces aides sont accessibles à tous en France métropolitaine et en outre-mer.</p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:24px;">
            <?php foreach (AIDES_NATIONALES as $key => $aide): ?>
            <?php
            $icons = [
                'tva_10'       => '🧾',
                'tva_55'       => '🏷️',
                'cee'          => '⚡',
                'eco_ptz'      => '🏦',
                'maprimerenov' => '🏛️',
            ];
            $icon = $icons[$key] ?? '💶';
            ?>
            <div class="aide-card" style="padding-left:28px;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                    <span style="font-size:22px;"><?= $icon ?></span>
                    <div>
                        <div class="aide-card-nom"><?= htmlspecialchars($aide['nom']) ?></div>
                        <?php if (!empty($aide['code'])): ?>
                        <span style="font-size:11px;font-weight:700;color:var(--text-muted);letter-spacing:.05em;"><?= htmlspecialchars($aide['code']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="aide-card-montant" style="margin-bottom:12px;">
                    💰 <?= htmlspecialchars($aide['montant']) ?>
                </div>

                <p style="font-size:13px;color:var(--text-muted);line-height:1.7;margin-bottom:12px;">
                    <?= htmlspecialchars($aide['description']) ?>
                </p>

                <div style="background:var(--cream);border-radius:8px;padding:10px 12px;margin-bottom:14px;">
                    <p style="font-size:11px;font-weight:700;color:var(--text);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Conditions</p>
                    <p style="font-size:12px;color:var(--text-muted);line-height:1.6;"><?= htmlspecialchars($aide['conditions']) ?></p>
                </div>

                <a href="<?= htmlspecialchars($aide['url']) ?>"
                   style="display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:700;color:var(--forest);text-decoration:none;border:1px solid var(--forest);padding:6px 14px;border-radius:100px;transition:all .15s;"
                   target="_blank" rel="noopener nofollow"
                   onmouseover="this.style.background='var(--forest)';this.style.color='#fff'"
                   onmouseout="this.style.background='transparent';this.style.color='var(--forest)'">
                    Source officielle
                    <svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/><path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/></svg>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ─── Comment cumuler ──────────────────────────────────────────────────── -->
    <section style="background:var(--cream);border:1px solid var(--stone);border-radius:var(--radius-lg);padding:32px;margin-bottom:64px;">
        <h2 style="font-family:var(--font-display);font-size:20px;font-weight:700;color:var(--text);margin-bottom:20px;">
            🔗 Comment cumuler les aides ?
        </h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;">
            <div style="display:flex;gap:12px;align-items:flex-start;">
                <span style="background:var(--forest);color:#fff;font-size:11px;font-weight:800;width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;">1</span>
                <div>
                    <p style="font-size:13px;font-weight:700;color:var(--text);margin-bottom:2px;">Choisissez un chauffagiste qualifié</p>
                    <p style="font-size:12px;color:var(--text-muted);line-height:1.6;">Indispensable pour bénéficier du financement et des conditions avantageuses.</p>
                </div>
            </div>
            <div style="display:flex;gap:12px;align-items:flex-start;">
                <span style="background:var(--forest);color:#fff;font-size:11px;font-weight:800;width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;">2</span>
                <div>
                    <p style="font-size:13px;font-weight:700;color:var(--text);margin-bottom:2px;">Demandez plusieurs devis</p>
                    <p style="font-size:12px;color:var(--text-muted);line-height:1.6;">Comparez les devis et vérifiez les certifications (RGE) sur chaque proposition.</p>
                </div>
            </div>
            <div style="display:flex;gap:12px;align-items:flex-start;">
                <span style="background:var(--forest);color:#fff;font-size:11px;font-weight:800;width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;">3</span>
                <div>
                    <p style="font-size:13px;font-weight:700;color:var(--text);margin-bottom:2px;">Demandez le financement RGE</p>
                    <p style="font-size:12px;color:var(--text-muted);line-height:1.6;">Avant le début des travaux, votre chauffagiste vous accompagne dans les démarches.</p>
                </div>
            </div>
            <div style="display:flex;gap:12px;align-items:flex-start;">
                <span style="background:var(--forest);color:#fff;font-size:11px;font-weight:800;width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;">4</span>
                <div>
                    <p style="font-size:13px;font-weight:700;color:var(--text);margin-bottom:2px;">La TVA 5,5% s'applique sur les travaux</p>
                    <p style="font-size:12px;color:var(--text-muted);line-height:1.6;">Votre chauffagiste applique directement la TVA applicable sur sa facture.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ─── Zones climatiques ─────────────────────────────────────────────────── -->
    <section style="margin-bottom:64px;">
        <div style="margin-bottom:32px;">
            <span class="section-eyebrow">🔥 Solutions selon votre région</span>
            <h2 class="section-title" style="margin-top:8px;">systèmes de chauffage & options de financement</h2>
            <p class="section-subtitle">Les solutions chauffage varient selon votre région et votre projet. Consultez un chauffagiste local pour un devis personnalisé.</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;">
            <?php
            $zoneColors = [
                'H1' => ['bg' => '#EBF5FF', 'border' => '#3B82F6', 'text' => '#1D4ED8'],
                'H2' => ['bg' => '#FFF7ED', 'border' => '#F97316', 'text' => '#9A3412'],
                'H3' => ['bg' => '#FEF3C7', 'border' => '#F59E0B', 'text' => '#92400E'],
            ];
            foreach (ZONES_CLIMATIQUES as $code => $zone):
                $c = $zoneColors[$code];
            ?>
            <div style="background:<?= $c['bg'] ?>;border:1px solid <?= $c['border'] ?>33;border-top:3px solid <?= $c['border'] ?>;border-radius:16px;padding:24px;">
                <div style="display:inline-flex;align-items:center;gap:8px;background:<?= $c['border'] ?>18;color:<?= $c['text'] ?>;font-size:13px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;padding:4px 12px;border-radius:100px;margin-bottom:12px;">
                    Zone <?= $code ?>
                </div>
                <h3 style="font-family:var(--font-display);font-size:16px;font-weight:600;color:var(--text);margin-bottom:8px;">
                    <?= htmlspecialchars(explode(' — ', $zone['label'])[1] ?? $zone['label']) ?>
                </h3>
                <p style="font-size:13px;color:var(--text-muted);line-height:1.7;"><?= htmlspecialchars($zone['description']) ?></p>
                <?php if ($zone['cee_bonus']): ?>
                <span style="display:inline-block;margin-top:12px;font-size:11px;font-weight:700;color:<?= $c['text'] ?>;background:<?= $c['border'] ?>20;padding:3px 10px;border-radius:100px;">
                    ✓ Prime CEE maximale
                </span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ─── Dispositifs urbains ───────────────────────────────────────────────── -->
    <section style="margin-bottom:64px;">
        <div style="margin-bottom:32px;">
            <span class="section-eyebrow">🏙️ Dispositifs spéciaux</span>
            <h2 class="section-title" style="margin-top:8px;">Aides renforcées selon votre commune</h2>
            <p class="section-subtitle">Certaines communes bénéficient d'aides supplémentaires selon leur classement.</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;">
            <?php foreach ([AIDES_QPV, AIDES_ACV, AIDES_PVD] as $dispositif): ?>
            <div style="background:var(--white);border:1px solid var(--stone);border-radius:var(--radius-lg);padding:24px;">
                <h3 style="font-family:var(--font-display);font-size:16px;font-weight:700;color:var(--text);margin-bottom:8px;">
                    <?= htmlspecialchars($dispositif['nom']) ?>
                </h3>
                <p style="font-size:13px;color:var(--text-muted);line-height:1.7;margin-bottom:14px;">
                    <?= htmlspecialchars($dispositif['description']) ?>
                </p>
                <ul style="display:flex;flex-direction:column;gap:6px;">
                    <?php foreach ($dispositif['avantages'] as $av): ?>
                    <li style="display:flex;align-items:baseline;gap:8px;font-size:12px;color:var(--text-muted);line-height:1.5;">
                        <span style="color:var(--forest);font-weight:700;flex-shrink:0;">✓</span>
                        <?= htmlspecialchars($av) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ─── CTA devis ─────────────────────────────────────────────────────────── -->
    <section style="background:var(--forest);border-radius:var(--radius-lg);padding:48px 32px;text-align:center;">
        <span class="section-eyebrow" style="color:rgba(255,255,255,.6);background:rgba(255,255,255,.1);margin-bottom:16px;">
            🔥 Obtenir un devis
        </span>
        <h2 style="font-family:var(--font-display);font-size:clamp(20px,3vw,32px);font-weight:700;color:#fff;margin-bottom:10px;letter-spacing:-.02em;">
            Trouvez un chauffagiste qualifié près de chez vous
        </h2>
        <p style="font-size:15px;color:rgba(255,255,255,.65);line-height:1.7;max-width:480px;margin:0 auto 28px;">
            Un chauffagiste professionnel vous accompagne dans votre projet. Devis gratuit, sans engagement, réponse sous 48h.
        </p>
        <a href="<?= htmlspecialchars(SITE_URL) ?>/"
           style="display:inline-flex;align-items:center;gap:8px;background:#fff;color:var(--forest);font-size:15px;font-weight:700;padding:14px 28px;border-radius:100px;text-decoration:none;box-shadow:0 4px 16px rgba(0,0,0,.2);">
            Rechercher par ville
            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
        </a>
    </section>

</main>

<?php require __DIR__ . '/../templates/footer.php'; ?>

