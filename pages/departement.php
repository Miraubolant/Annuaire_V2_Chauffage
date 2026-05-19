<?php
// Variables depuis router.php : $deptCode, $deptSlug, $regionSlug, $geoData
$deptCode   ??= '';
$deptSlug   ??= '';
$regionSlug ??= '';
$geoData    ??= [];
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

$deptMap  = getDeptMapping();
$deptInfo = $deptMap[$deptCode] ?? [];
$deptNom  = $deptInfo['nom'] ?? '';
$communes = $geoData['communes'] ?? [];
$voisins  = $geoData['voisins'] ?? [];

// Pré-charger les compteurs depuis le fichier niche du département
$nicheFileDept = DATA_DIR . '/' . NICHE_DIR . '/' . strtoupper($deptCode) . '.json';
$nicheDataDept = file_exists($nicheFileDept) ? (json_decode(file_get_contents($nicheFileDept), true) ?? []) : [];
$nicheCountDept = [];
foreach ($nicheDataDept as $slug => $cd) {
    $nicheCountDept[$slug] = count($cd['artisans'] ?? []);
}
// Injecter les bons compteurs dans chaque commune (pour getCompteurArtisans et card-ville)
foreach ($communes as &$c) {
    if (isset($nicheCountDept[$c['slug']])) {
        $c['artisans'][NICHE_KEY] = $nicheCountDept[$c['slug']];
    }
}
unset($c);
$getCount = fn(array $c) => (int)($c['artisans'][NICHE_KEY] ?? 0);

// Calculer stats département
$totalArtisans  = 0;
$totalAvant1990 = 0;
$totalLogements = 0;
$zonesCount     = [];
foreach ($communes as $c) {
    $totalArtisans  += $getCount($c);
    $totalAvant1990 += $c['logement']['logements_avant_1990'] ?? 0;
    $totalLogements += $c['logement']['logements_total'] ?? 0;
    $z = $c['aides_etat']['zone_climatique'] ?? null;
    if ($z) $zonesCount[$z] = ($zonesCount[$z] ?? 0) + 1;
}
$pctAvant1990 = $totalLogements > 0 ? round($totalAvant1990 / $totalLogements * 100) : 0;
arsort($zonesCount);
$zoneDominante = array_key_first($zonesCount) ?? 'H2';
$communesAvecArtisans = array_filter($communes, fn($c) => $getCount($c) > 0);

// Trier communes par nb artisans desc
usort($communes, fn($a, $b) => $getCount($b) - $getCount($a));

$artDept = articleDepartement($deptCode);
$title         = seoTitle('departement', ['code' => $deptCode, 'nom' => $deptNom, 'artisans_chauffage' => $totalArtisans, 'zone' => $zoneDominante]);
$description   = seoDescription('departement', ['code' => $deptCode, 'nom' => $deptNom, 'artisans_chauffage' => $totalArtisans, 'zone' => $zoneDominante]);
$canonical_url = urlDepartement($regionSlug, $deptSlug);
$robots        = 'index,follow';

$trail = [
    ['name' => 'Accueil',                          'url' => SITE_URL . '/'],
    ['name' => nomRegion($regionSlug),             'url' => urlRegion($regionSlug)],
    ['name' => $deptNom . ' (' . $deptCode . ')',  'url' => $canonical_url],
];
$jsonLd = [jsonLdBreadcrumbs($trail)];

$faq = [
    ['q' => "Combien de chauffagistes exercent {$artDept} ?",
     'r' => "Il y a {$totalArtisans} chauffagistes référencés {$artDept}, répartis dans " . count($communesAvecArtisans) . " communes. Trouvez un professionnel qualifié près de chez vous et demandez un devis gratuit."],
    ['q' => "Quelles aides sont disponibles pour une chaudière {$artDept} ?",
     'r' => "Les propriétaires {$artDept} peuvent bénéficier de la TVA à 5,5% sur les travaux de chauffage, du crédit d'impôt pour une pompe à chaleur air-eau, et de solutions de financement RGE. Votre chauffagiste peut vous aider à optimiser votre budget."],
    ['q' => "Quel est le prix d'une chaudière {$artDept} ?",
     'r' => "Le prix varie selon le type de système : chaudière gaz condensation entre 2 500 € et 5 000 € installée, pompe à chaleur air-eau entre 8 000 € et 18 000 €. Après MaPrimeRénov' et prime CEE, le reste à charge peut être divisé par deux. Demandez plusieurs devis pour comparer."],
    ['q' => "Comment trouver un chauffagiste qualifié {$artDept} ?",
     'r' => "Cherchez votre ville dans notre annuaire pour trouver les chauffagistes certifiés RGE ou QualiPAC {$artDept}. Ces certifications garantissent la qualité et le professionnalisme de votre chauffagiste."],
    ['q' => "Faut-il un permis de construire pour une chaudière {$artDept} ?",
     'r' => "une chaudière de 10 à 100 m² {$artDept} nécessite une déclaration préalable de travaux. Au-delà de 100 m² ou si le chauffage est couverte, un permis de construire est requis. Votre chauffagiste {$artDept} vous guide dans les démarches administratives."],
];
$jsonLd[] = jsonLdFAQ($faq);

require __DIR__ . '/../templates/header.php';
?>

<!-- Breadcrumb strip -->
<div style="background:var(--stone);border-bottom:1px solid rgba(0,0,0,.06);">
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
            <h1 style="font-family:var(--font-display);font-size:clamp(26px,3.5vw,44px);font-weight:700;color:#fff;line-height:1.2;margin-bottom:14px;letter-spacing:-.02em;">
                chauffagistes <?= htmlspecialchars($artDept) ?><br>
                <em style="color:#F0A07A;font-style:italic;"><?= htmlspecialchars($deptNom) ?> (<?= htmlspecialchars($deptCode) ?>)</em>
            </h1>

            <p style="font-size:15px;color:rgba(255,255,255,.65);margin-bottom:28px;line-height:1.7;">
                Trouvez un chauffagiste qualifié <?= htmlspecialchars($artDept) ?> — devis gratuit, TVA 5,5%, financement RGE disponible.
            </p>

            <div class="ph-stat-row">
                <div class="ph-stat">
                    <div class="ph-stat-num"><?= number_format($totalArtisans, 0, ',', ' ') ?></div>
                    <div class="ph-stat-label">chauffagistes</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,.15);align-self:stretch;"></div>
                <div class="ph-stat">
                    <div class="ph-stat-num"><?= count($communesAvecArtisans) ?></div>
                    <div class="ph-stat-label">Communes</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,.15);align-self:stretch;"></div>
                <div class="ph-stat">
                    <div class="ph-stat-num"><?= $pctAvant1990 ?>%</div>
                    <div class="ph-stat-label">Logements avant 1990</div>
                </div>
            </div>

            <div class="ph-badge-grid">
                <div class="ph-badge">
                    <div class="ph-badge-icon">🏅</div>
                    <div>
                        <div style="font-weight:700;color:#fff;font-size:13px;">certifié RGE</div>
                        <div style="font-size:11px;color:rgba(255,255,255,.5);margin-top:2px;">Certifiés</div>
                    </div>
                </div>
                <div class="ph-badge">
                    <div class="ph-badge-icon">🔥</div>
                    <div>
                        <div style="font-weight:700;color:#fff;font-size:13px;">Financement dispo</div>
                        <div style="font-size:11px;color:rgba(255,255,255,.5);margin-top:2px;">TVA 5,5% applicable</div>
                    </div>
                </div>
            </div>

            <div>
                <p style="font-size:11px;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.08em;font-weight:700;margin-bottom:10px;">Principales villes</p>
                <div class="ph-tags">
                    <?php foreach (array_slice($communes, 0, 5) as $c): ?>
                    <span class="ph-tag">📍 <?= htmlspecialchars($c['nom']) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Colonne droite : widget devis -->
        <div>
            <div class="ph-widget-card">
                <div class="ph-widget-header">
                    <span style="font-size:22px;">🔥</span>
                    <div>
                        <div style="font-weight:700;color:#fff;font-size:15px;">Obtenir un devis gratuit</div>
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

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <!-- Communes (triées par nb artisans) -->
    <section class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">
            chauffagistes par ville <?= htmlspecialchars($artDept) ?>
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            <?php foreach (array_slice($communes, 0, 60) as $ville): ?>
            <?php require __DIR__ . '/../components/card-ville.php'; ?>
            <?php endforeach; ?>
        </div>
        <?php if (count($communes) > 60): ?>
        <p class="text-center text-gray-400 text-sm mt-4">
            +<?= count($communes) - 60 ?> communes supplémentaires — utilisez la recherche
        </p>
        <?php endif; ?>
    </section>

    <!-- Départements voisins -->
    <?php if (!empty($voisins)): ?>
    <section class="mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">📍 Départements voisins</h2>
        <div class="flex flex-wrap gap-3">
            <?php foreach ($voisins as $v):
                $vDeptInfo = getDeptMapping()[$v['dep_code']] ?? null;
                if (!$vDeptInfo) continue;
            ?>
            <a href="<?= htmlspecialchars(urlDepartement($vDeptInfo['region_slug'], $vDeptInfo['slug'])) ?>"
               class="bg-white border border-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-full hover:border-blue-400 hover:text-blue-600 transition-colors">
                <?= htmlspecialchars($v['dep_nom']) ?> (<?= $v['dep_code'] ?>)
            </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- FAQ -->
    <?php $questions = FAQ_ACCUEIL; require __DIR__ . '/../components/faq.php'; ?>

</main>

<?php require __DIR__ . '/../templates/footer.php'; ?>

