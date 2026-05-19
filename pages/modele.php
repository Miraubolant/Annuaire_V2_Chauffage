<?php
// Variables depuis router.php : $modele, $commune, $deptCode, $deptSlug, $regionSlug, $villeSlug, $villeCp
$modele     ??= ['slug' => '', 'nom' => '', 'emoji' => '', 'vud_cat' => 10];
$commune    ??= [];
$deptCode   ??= '';
$deptSlug   ??= '';
$regionSlug ??= '';
$villeSlug  ??= '';
$villeCp    ??= '';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

$artisans  = getArtisansByVille($deptCode, $villeSlug);
$aides     = getAidesForCommune($commune);
$vudCat    = getVudCatForModele($modele['slug']);
$deptMap   = getDeptMapping();
$deptNom   = $deptMap[$deptCode]['nom'] ?? '';

$modNom    = $modele['nom'];
$artVille  = articleVille($commune['nom']);
$zone      = $commune['aides_etat']['zone_climatique'] ?? 'H2';

$title         = seoTitle('modele', ['modele' => $modele, 'commune' => $commune]);
$description   = seoDescription('modele', ['modele' => $modele, 'commune' => $commune]);
$canonical_url = urlModele($regionSlug, $deptSlug, $villeSlug, $villeCp, $modele['slug']);
$robots        = 'index,follow';

$trail = [
    ['name' => 'Accueil',               'url' => SITE_URL . '/'],
    ['name' => nomRegion($regionSlug),  'url' => urlRegion($regionSlug)],
    ['name' => $deptNom,                'url' => urlDepartement($regionSlug, $deptSlug)],
    ['name' => $commune['nom'],         'url' => urlVille($regionSlug, $deptSlug, $villeSlug, $villeCp)],
    ['name' => $modNom,                 'url' => $canonical_url],
];

$jsonLd = [jsonLdBreadcrumbs($trail)];
$jsonLd[] = '<script type="application/ld+json">' . json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'Service',
    'name'     => $modNom . ' ' . $artVille,
    'provider' => ['@type' => 'Organization', 'name' => SITE_NAME],
    'areaServed' => ['@type' => 'City', 'name' => $commune['nom']],
    'serviceType' => 'chauffagiste',
], JSON_UNESCAPED_UNICODE) . '</script>';

$faq = [
    ['q' => "Quel est le prix de \"{$modNom}\" {$artVille} ?",
     'r' => "Le tarif pour {$modNom} {$artVille} varie selon la taille du projet, les matériaux et la complexité du chantier. Demandez plusieurs devis gratuits pour comparer les offres des chauffagistes de {$commune['nom']}."],
    ['q' => "Quelle TVA s'applique pour \"{$modNom}\" ?",
     'r' => "La TVA à 5,5% s'applique aux travaux de chauffage. Des solutions de financement RGE sont disponibles pour étaler le coût. Votre chauffagiste {$artVille} vous confirme les conditions lors du devis."],
    ['q' => "Peut-on financer \"{$modNom}\" {$artVille} ?",
     'r' => "Des solutions de financement RGE sont disponibles {$artVille}. Pour une pompe à chaleur air-eau, un crédit d'impôt peut s'appliquer selon votre situation. Votre chauffagiste qualifié peut vous orienter vers les meilleures options."],
    ['q' => "Combien de temps prend \"{$modNom}\" {$artVille} ?",
     'r' => "La durée des travaux dépend du type de projet. Contactez les chauffagistes de {$commune['nom']} pour obtenir un planning précis lors de la visite technique gratuite."],
    ['q' => "Comment choisir un chauffagiste pour \"{$modNom}\" {$artVille} ?",
     'r' => "Vérifiez les certifications (RGE), la garantie décennale et demandez au moins 3 devis détaillés. Consultez les avis clients en ligne pour choisir le meilleur chauffagiste de {$commune['nom']}."],
];
$jsonLd[] = jsonLdFAQ($faq);

require __DIR__ . '/../templates/header.php'; ?><div style="background:var(--stone);border-bottom:1px solid rgba(0,0,0,.06);">
    <div style="max-width:1280px;margin:0 auto;padding:10px 24px;">
        <?php require __DIR__ . '/../components/breadcrumb.php'; ?>
    </div>
</div>

<!-- Hero 2 colonnes -->
<section class="ph-section">
    <div class="hero-grain"></div>
    <div class="ph-grid">

        <!-- Colonne gauche : éditorial -->
        <div>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;">
                <span style="font-size:28px;"><?= $modele['emoji'] ?></span>
                <span style="font-weight:600;color:rgba(255,255,255,.7);font-size:13px;text-transform:uppercase;letter-spacing:.06em;">
                    <?= htmlspecialchars($deptNom) ?>
                </span>
            </div>

            <h1 style="font-family:var(--font-display);font-size:clamp(24px,3.2vw,42px);font-weight:700;color:#fff;line-height:1.2;margin-bottom:14px;letter-spacing:-.02em;">
                <?= htmlspecialchars($modNom) ?><br>
                <em style="color:#F0A07A;font-style:italic;"><?= htmlspecialchars($artVille) ?></em>
            </h1>

            <p style="font-size:15px;color:rgba(255,255,255,.65);margin-bottom:28px;line-height:1.7;">
                chauffagistes qualifiés <?= htmlspecialchars($artVille) ?> — TVA 5,5%, financement RGE disponible, devis gratuit.
            </p>

            <div class="ph-stat-row">
                <div class="ph-stat">
                    <div class="ph-stat-num"><?= getCompteurArtisans($commune) ?></div>
                    <div class="ph-stat-label">chauffagistes</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,.15);align-self:stretch;"></div>
                <div class="ph-stat">
                    <div class="ph-stat-num">Financement</div>
                    <div class="ph-stat-label">chauffagiste dispo</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,.15);align-self:stretch;"></div>
                <div class="ph-stat">
                    <div class="ph-stat-num">5,5%</div>
                    <div class="ph-stat-label">TVA applicable</div>
                </div>
            </div>

            <div class="ph-badge-grid">
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
                        <div style="font-weight:700;color:#fff;font-size:13px;">certifié RGE</div>
                        <div style="font-size:11px;color:rgba(255,255,255,.5);margin-top:2px;">Certifiés & Assurés</div>
                    </div>
                </div>
            </div>

            <div>
                <p style="font-size:11px;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.08em;font-weight:700;margin-bottom:10px;">Services similaires</p>
                <div class="ph-tags">
                    <?php foreach (array_slice(MODELES, 0, 5) as $m):
                        if ($m['slug'] === $modele['slug']) continue;
                    ?>
                    <a href="<?= htmlspecialchars(urlModele($regionSlug, $deptSlug, $villeSlug, $villeCp, $m['slug'])) ?>"
                       style="text-decoration:none;">
                        <span class="ph-tag"><?= $m['emoji'] ?> <?= htmlspecialchars($m['nom']) ?></span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Colonne droite : widget devis -->
        <div>
            <div class="ph-widget-card">
                <div class="ph-widget-header">
                    <span style="font-size:22px;"><?= $modele['emoji'] ?></span>
                    <div>
                        <div style="font-weight:700;color:#fff;font-size:15px;">Obtenir un devis gratuit</div>
                        <div style="font-size:11px;color:rgba(255,255,255,.55);">Réponse sous 48h · Sans engagement</div>
                    </div>
                </div>
                <div style="color:#1a1a1a;">
                    <div id="v2e29b6034ad"></div>
                    <script>
                        vud_partenaire_id = '<?= VUD_PARTENAIRE_ID ?>';
                        vud_categorie_id  = '<?= $vudCat ?>';
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

    <!-- Artisans de la ville -->
    <?php if (!empty($artisans)): ?>
    <section class="mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            🔥 chauffagistes <?= htmlspecialchars($artVille) ?> pour ce service
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <?php foreach ($artisans as $artisan): ?>
            <?php require __DIR__ . '/../components/card-artisan.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Zone d'intervention — Google Maps -->
    <?php
    $mapLat = $commune['latitude']  ?? null;
    $mapLng = $commune['longitude'] ?? null;
    $mapVilleNom = $commune['nom'] ?? '';
    $mapNbArt = getCompteurArtisans($commune);
    if ($mapLat && $mapLng):
        $mapUrl = 'https://maps.google.com/maps?q=' . $mapLat . ',' . $mapLng . '&hl=fr&z=13&output=embed';
    ?>
    <section class="mb-8">
        <h2 style="font-family:var(--font-display);font-size:20px;font-weight:700;color:var(--text);margin-bottom:16px;">
            Zone d'intervention : <?= htmlspecialchars($mapVilleNom) ?>
        </h2>
        <div style="background:#fff;border-radius:var(--radius-lg);border:1px solid var(--stone);overflow:hidden;box-shadow:var(--shadow-sm);">
            <iframe src="<?= htmlspecialchars($mapUrl) ?>" width="100%" height="300" style="border:none;display:block;" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="<?= htmlspecialchars($modNom) ?> <?= htmlspecialchars($mapVilleNom) ?>"></iframe>
            <div style="padding:12px 20px;background:var(--cream);display:flex;align-items:center;justify-content:space-between;font-size:12px;color:var(--text-muted);">
                <span><?= htmlspecialchars($mapVilleNom) ?> · <?= $mapNbArt ?> chauffagiste<?= $mapNbArt > 1 ? 's' : '' ?> référencé<?= $mapNbArt > 1 ? 's' : '' ?></span>
                <a href="https://www.google.com/maps/search/chauffagiste+<?= urlencode($mapVilleNom . ' ' . $villeCp) ?>/" target="_blank" rel="noopener" style="color:var(--forest);font-weight:600;text-decoration:none;">Voir sur Google Maps →</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Aides locales -->
    <?php require __DIR__ . '/../components/aides-locales.php'; ?>

    <!-- FAQ -->
    <?php
    $questions = FAQ_ACCUEIL;
    $title = 'FAQ — ' . $modNom . ' ' . $artVille;
    require __DIR__ . '/../components/faq.php';
    ?>

    <!-- Autres services dans la ville -->
    <section class="mt-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            🛠️ Autres services chauffage <?= htmlspecialchars($artVille) ?>
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            <?php foreach (MODELES as $m):
                if ($m['slug'] === $modele['slug']) continue;
                $url = urlModele($regionSlug, $deptSlug, $commune['slug'], $commune['code_postal'], $m['slug']);
            ?>
            <a href="<?= htmlspecialchars($url) ?>"
               class="flex items-center gap-3 bg-white rounded-xl border border-gray-100 p-3 hover:border-slate-300 hover:shadow-sm transition-all">
                <span class="text-xl"><?= $m['emoji'] ?></span>
                <span class="text-sm text-gray-700"><?= htmlspecialchars($m['nom']) ?></span>
                <span class="ml-auto text-gray-300 text-sm">→</span>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

</main>

<?php require __DIR__ . '/../templates/footer.php'; ?>

