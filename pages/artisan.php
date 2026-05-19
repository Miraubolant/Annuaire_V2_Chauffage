<?php
// Variables depuis router.php : $artisan, $artisans, $commune, $deptCode, $deptSlug, $regionSlug, $villeSlug, $villeCp
$artisan    ??= [];
$artisans   ??= [];
$commune    ??= [];
$deptCode   ??= '';
$deptSlug   ??= '';
$regionSlug ??= '';
$villeSlug  ??= '';
$villeCp    ??= '';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

$deptMap  = getDeptMapping();
$deptNom  = $deptMap[$deptCode]['nom'] ?? '';
$artVille = articleVille($commune['nom']);
$aides    = getAidesForCommune($commune);
$note     = (float) ($artisan['note'] ?? 0);
$avis     = (int)   ($artisan['avis'] ?? 0);
$stars    = min(5, max(0, round($note)));
$zone     = $commune['aides_etat']['zone_climatique'] ?? 'H2';

$title         = seoTitle('artisan', array_merge($artisan, ['ville' => $commune]));
$description   = seoDescription('artisan', array_merge($artisan, ['ville' => $commune]));
$canonical_url = urlArtisan($regionSlug, $deptSlug, $villeSlug, $villeCp, $artisan['slug']);
$robots        = 'index,follow';

$trail = [
    ['name' => 'Accueil',              'url' => SITE_URL . '/'],
    ['name' => nomRegion($regionSlug), 'url' => urlRegion($regionSlug)],
    ['name' => $deptNom,               'url' => urlDepartement($regionSlug, $deptSlug)],
    ['name' => $commune['nom'],        'url' => urlVille($regionSlug, $deptSlug, $villeSlug, $villeCp)],
    ['name' => $artisan['nom'],        'url' => $canonical_url],
];
$jsonLd = [jsonLdBreadcrumbs($trail), jsonLdLocalBusiness($artisan, $commune)];

require __DIR__ . '/../templates/header.php'; ?><div style="background:var(--stone);border-bottom:1px solid rgba(0,0,0,.06);">
    <div style="max-width:1280px;margin:0 auto;padding:10px 24px;">
        <?php require __DIR__ . '/../components/breadcrumb.php'; ?>
    </div>
</div>

<!-- Hero 2 colonnes -->
<section class="ph-section">
    <div class="hero-grain"></div>
    <div class="ph-grid">

        <!-- Colonne gauche : identité artisan -->
        <div>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;">
                <span style="font-size:28px;">🔥</span>
                <span style="font-weight:600;color:rgba(255,255,255,.7);font-size:13px;text-transform:uppercase;letter-spacing:.06em;">
                    <?= htmlspecialchars($commune['nom']) ?> · <?= htmlspecialchars($deptNom) ?>
                </span>
            </div>

            <h1 style="font-family:var(--font-display);font-size:clamp(24px,3.2vw,42px);font-weight:700;color:#fff;line-height:1.2;margin-bottom:14px;letter-spacing:-.02em;">
                <?= htmlspecialchars($artisan['nom']) ?><br>
                <em style="color:#F0A07A;font-style:italic;"><?= htmlspecialchars($artVille) ?></em>
            </h1>

            <?php if ($note > 0): ?>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:18px;">
                <div style="display:flex;gap:2px;">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span style="color:<?= $i <= $stars ? '#FBBF24' : 'rgba(255,255,255,.25)' ?>;font-size:20px;">★</span>
                    <?php endfor; ?>
                </div>
                <span style="font-weight:700;color:#fff;font-size:15px;"><?= formatNote($note) ?></span>
                <span style="color:rgba(255,255,255,.5);font-size:13px;">(<?= $avis ?> avis)</span>
            </div>
            <?php endif; ?>

            <p style="font-size:15px;color:rgba(255,255,255,.65);margin-bottom:28px;line-height:1.7;">
                <?= METIER_CAP ?> qualifié <?= htmlspecialchars($artVille) ?> — Garantie décennale, devis gratuit.
            </p>

            <div class="ph-stat-row">
                <div class="ph-stat">
                    <div class="ph-stat-num"><?= $note > 0 ? formatNote($note) : '—' ?></div>
                    <div class="ph-stat-label">Note / 5</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,.15);align-self:stretch;"></div>
                <div class="ph-stat">
                    <div class="ph-stat-num"><?= $avis > 0 ? $avis : '—' ?></div>
                    <div class="ph-stat-label">Avis clients</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,.15);align-self:stretch;"></div>
                <div class="ph-stat">
                    <div class="ph-stat-num">TVA 5,5%</div>
                    <div class="ph-stat-label">Financement disponible</div>
                </div>
            </div>

            <!-- Boutons contact -->
            <div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:24px;">
                <?php if (!empty($artisan['telephone'])): ?>
                <a href="tel:<?= preg_replace('/\s/', '', $artisan['telephone']) ?>"
                   style="display:flex;align-items:center;gap:8px;background:#f97316;color:#fff;font-weight:700;font-size:14px;padding:12px 20px;border-radius:10px;text-decoration:none;">
                    📞 <?= htmlspecialchars(formatPhone($artisan['telephone'])) ?>
                </a>
                <?php endif; ?>
                <?php if (!empty($artisan['site_web'])): ?>
                <a href="<?= htmlspecialchars($artisan['site_web']) ?>"
                   style="display:flex;align-items:center;gap:8px;background:rgba(255,255,255,.15);color:#fff;font-weight:600;font-size:14px;padding:12px 20px;border-radius:10px;text-decoration:none;"
                   target="_blank" rel="noopener nofollow">
                    🌐 Visiter le site
                </a>
                <?php endif; ?>
            </div>

            <div class="ph-badge-grid" style="margin-top:24px;">
                <div class="ph-badge">
                    <div class="ph-badge-icon">✓</div>
                    <div>
                        <div style="font-weight:700;color:#fff;font-size:13px;">Devis Gratuit</div>
                        <div style="font-size:11px;color:rgba(255,255,255,.5);margin-top:2px;">Sous 48 heures</div>
                    </div>
                </div>
                <div class="ph-badge">
                    <div class="ph-badge-icon">🏅</div>
                    <div>
                        <div style="font-weight:700;color:#fff;font-size:13px;">Garantie décennale</div>
                        <div style="font-size:11px;color:rgba(255,255,255,.5);margin-top:2px;">Assurance vérifiée</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite : widget devis -->
        <div>
            <div class="ph-widget-card">
                <div class="ph-widget-header">
                    <span style="font-size:22px;">🔥</span>
                    <div>
                        <div style="font-weight:700;color:#fff;font-size:15px;">Demander un devis gratuit</div>
                        <div style="font-size:11px;color:rgba(255,255,255,.55);">Réponse sous 48h · Sans engagement</div>
                    </div>
                </div>
                <div style="color:#1a1a1a;">
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
            </div>
        </div>

    </div>
</section>

<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Informations complémentaires -->
    <?php if (!empty($artisan['adresse']) || !empty($artisan['type'])): ?>
    <section class="mb-6" style="background:#fff;border-radius:12px;border:1px solid #e5e7eb;padding:20px 24px;">
        <h2 style="font-family:var(--font-display);font-size:16px;font-weight:700;color:var(--text);margin-bottom:12px;">
            Informations
        </h2>
        <?php if (!empty($artisan['type'])): ?>
        <p style="font-size:14px;color:var(--text-muted);margin-bottom:8px;">
            <strong>Activité :</strong> <?= htmlspecialchars($artisan['type']) ?>
        </p>
        <?php endif; ?>
        <?php if (!empty($artisan['adresse'])): ?>
        <p style="font-size:14px;color:var(--text-muted);display:flex;align-items:center;gap:6px;">
            <span>📍</span> <?= htmlspecialchars($artisan['adresse']) ?>
        </p>
        <?php endif; ?>
    </section>
    <?php endif; ?>

    <!-- Aides locales -->
    <?php require __DIR__ . '/../components/aides-locales.php'; ?>

    <!-- Artisans similaires -->
    <?php
    $similaires = array_filter($artisans, fn($a) => $a['slug'] !== $artisan['slug']);
    $similaires = array_slice(array_values($similaires), 0, 4);
    if (!empty($similaires)):
    ?>
    <section class="mt-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            Autres <?= METIER_PLURIEL ?> <?= htmlspecialchars($artVille) ?>
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <?php foreach ($similaires as $artisan_tmp):
                $artisan_loop = $artisan_tmp;
                $artisan = $artisan_loop;
                require __DIR__ . '/../components/card-artisan.php';
                $artisan = $artisan_loop;
            endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Services disponibles dans la ville -->
    <section class="mt-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            🔥 services chauffage <?= htmlspecialchars($artVille) ?>
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            <?php foreach (array_slice(MODELES, 0, 12) as $m):
                $url = urlModele($regionSlug, $deptSlug, $villeSlug, $villeCp, $m['slug']);
            ?>
            <a href="<?= htmlspecialchars($url) ?>"
               class="flex items-center gap-3 bg-white rounded-xl border border-gray-100 p-3 hover:border-orange-300 hover:shadow-sm transition-all">
                <span class="text-xl"><?= $m['emoji'] ?></span>
                <span class="text-sm text-gray-700"><?= htmlspecialchars($m['nom']) ?></span>
                <span class="ml-auto text-gray-300 text-sm">→</span>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

</main>

<?php require __DIR__ . '/../templates/footer.php'; ?>
