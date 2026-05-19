<?php
// Variables depuis router.php : $regionSlug, $deptSlug, $deptCode, $geoData, $modele
$regionSlug ??= '';
$deptSlug   ??= '';
$deptCode   ??= '';
$geoData    ??= [];
$modele     ??= ['slug' => '', 'nom' => '', 'emoji' => '', 'vud_cat' => 10];
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

$deptMap = getDeptMapping();
$deptNom = $deptMap[$deptCode]['nom'] ?? $deptCode;
$artDept = articleDepartement($deptCode);
$modNom  = $modele['nom'];
$vudCat  = getVudCatForModele($modele['slug']);

// Charger le fichier niche pour ce département
$nicheFile = DATA_DIR . '/' . NICHE_DIR . '/' . strtoupper($deptCode) . '.json';
$nicheData = file_exists($nicheFile) ? (json_decode(file_get_contents($nicheFile), true) ?? []) : [];

// Communes avec artisans + zone dominante
$citiesWithArtisans = [];
$zoneCounts = [];
foreach ($geoData['communes'] ?? [] as $commune) {
    $z = $commune['aides_etat']['zone_climatique'] ?? 'H2';
    $zoneCounts[$z] = ($zoneCounts[$z] ?? 0) + 1;
    $nb = count($nicheData[$commune['slug']]['artisans'] ?? []);
    if ($nb < 1) continue;
    $citiesWithArtisans[] = ['commune' => $commune, 'nb' => $nb];
}
usort($citiesWithArtisans, fn($a, $b) => $b['nb'] <=> $a['nb']);
$nbArtisans = array_sum(array_column($citiesWithArtisans, 'nb'));

arsort($zoneCounts);
$zone = array_key_first($zoneCounts) ?? 'H2';

// Éditorial selon zone
$zoneTexts = [
    'H1' => "Le département {$deptNom} bénéficie d'un climat nordique favorable à la construction de systèmes de chauffage à haute performance. Une pompe à chaleur air-eau est particulièrement adaptée pour prolonger la saison de chauffe. Un " . METIER . " qualifié RGE peut vous conseiller sur les meilleures solutions pour votre région.",
    'H2' => "Le département {$deptNom} offre un climat tempéré avec des hivers modérés nécessitant un système de chauffage performant. La chaudière gaz à condensation et la pompe à chaleur air-eau sont les solutions les plus adaptées. Un " . METIER . " certifié RGE peut réaliser votre projet avec TVA 5,5% et MaPrimeRénov' applicables.",
    'H3' => "Le département {$deptNom} bénéficie d'un climat méditerranéen avec des hivers doux. La pompe à chaleur réversible y est particulièrement plébiscitée pour le chauffage et la climatisation. Un " . METIER . " local RGE vous accompagne pour installer et entretenir votre système de chauffage.",
];
$editorialText = $zoneTexts[$zone] ?? $zoneTexts['H2'];

$title         = seoTitle('modele-departement', ['modele' => $modele, 'dept_code' => $deptCode]);
$description   = seoDescription('modele-departement', ['modele' => $modele, 'dept_code' => $deptCode, 'nb_artisans' => $nbArtisans, 'zone' => $zone]);
$canonical_url = urlModeleDepartement($regionSlug, $deptSlug, $modele['slug']);
$robots        = 'index,follow';

$trail = [
    ['name' => 'Accueil',              'url' => SITE_URL . '/'],
    ['name' => nomRegion($regionSlug), 'url' => urlRegion($regionSlug)],
    ['name' => $deptNom,               'url' => urlDepartement($regionSlug, $deptSlug)],
    ['name' => $modNom,                'url' => $canonical_url],
];

$jsonLd = [jsonLdBreadcrumbs($trail)];
$jsonLd[] = '<script type="application/ld+json">' . json_encode([
    '@context'   => 'https://schema.org',
    '@type'      => 'Service',
    'name'       => $modNom . ' ' . $artDept,
    'provider'   => ['@type' => 'Organization', 'name' => SITE_NAME],
    'areaServed' => ['@type' => 'AdministrativeArea', 'name' => $deptNom],
    'serviceType' => METIER_CAP,
], JSON_UNESCAPED_UNICODE) . '</script>';

$faq = [
    ['q' => "Quel est le tarif pour \"{$modNom}\" {$artDept} ?",
     'r' => "Le tarif pour {$modNom} {$artDept} dépend du type de projet, de la taille et des équipements choisis. Demandez plusieurs devis gratuits aux " . METIER_PLURIEL . " référencés dans le département pour comparer."],
    ['q' => "Quelles aides pour \"{$modNom}\" {$artDept} ?",
     'r' => "La TVA à 5,5% s'applique aux travaux de chauffage {$artDept}. Des solutions de financement RGE sont disponibles. Pour une pompe à chaleur air-eau, un crédit d'impôt peut s'appliquer. Votre " . METIER . " vous guide dans les démarches."],
    ['q' => "Combien de " . METIER_PLURIEL . " pour \"{$modNom}\" {$artDept} ?",
     'r' => "{$nbArtisans} " . METIER_PLURIEL . " référencés {$artDept} peuvent réaliser ces travaux. Consultez la liste ci-dessus pour trouver un professionnel dans votre commune et demandez un devis gratuit."],
    ['q' => "Quelle TVA s'applique pour \"{$modNom}\" {$artDept} ?",
     'r' => "La TVA à 5,5% s'applique aux travaux de chauffage. Votre " . METIER . " vous confirmera les conditions lors de l'établissement du devis gratuit."],
    ['q' => "Quelle garantie pour \"{$modNom}\" {$artDept} ?",
     'r' => "Les travaux bénéficient de la garantie décennale (10 ans). Exigez toujours l'attestation d'assurance décennale de votre " . METIER . " avant de signer le devis."],
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
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;">
                <span style="font-size:28px;"><?= $modele['emoji'] ?></span>
                <span style="font-weight:600;color:rgba(255,255,255,.7);font-size:13px;text-transform:uppercase;letter-spacing:.06em;">
                    <?= htmlspecialchars($deptNom) ?> (<?= htmlspecialchars($deptCode) ?>)
                </span>
            </div>

            <h1 style="font-family:var(--font-display);font-size:clamp(24px,3.2vw,42px);font-weight:700;color:#fff;line-height:1.2;margin-bottom:14px;letter-spacing:-.02em;">
                <?= htmlspecialchars($modNom) ?><br>
                <em style="color:#F0A07A;font-style:italic;"><?= htmlspecialchars($artDept) ?></em>
            </h1>

            <p style="font-size:15px;color:rgba(255,255,255,.65);margin-bottom:28px;line-height:1.7;">
                <?= METIER_CAP ?>s qualifiés <?= htmlspecialchars($artDept) ?> — TVA 5,5%, financement RGE disponible, devis gratuit.
            </p>

            <div class="ph-stat-row">
                <div class="ph-stat">
                    <div class="ph-stat-num"><?= $nbArtisans ?></div>
                    <div class="ph-stat-label"><?= METIER_CAP ?>s</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,.15);align-self:stretch;"></div>
                <div class="ph-stat">
                    <div class="ph-stat-num"><?= count($citiesWithArtisans) ?></div>
                    <div class="ph-stat-label">Communes</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,.15);align-self:stretch;"></div>
                <div class="ph-stat">
                    <div class="ph-stat-num">TVA 5,5%</div>
                    <div class="ph-stat-label">Financement dispo</div>
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
                    <a href="<?= htmlspecialchars(urlModeleDepartement($regionSlug, $deptSlug, $m['slug'])) ?>"
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

    <!-- Communes avec artisans -->
    <?php if (!empty($citiesWithArtisans)): ?>
    <section class="mb-8">
        <h2 style="font-family:var(--font-display);font-size:22px;font-weight:700;color:var(--text);margin-bottom:16px;">
            <?= htmlspecialchars($modNom) ?> par commune <?= htmlspecialchars($artDept) ?>
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($citiesWithArtisans as $item):
                $c   = $item['commune'];
                $url = urlModele($regionSlug, $deptSlug, $c['slug'], $c['code_postal'], $modele['slug']);
            ?>
            <a href="<?= htmlspecialchars($url) ?>"
               style="text-decoration:none;display:block;background:#fff;border-radius:12px;border:1px solid #e5e7eb;padding:16px 20px;transition:border-color .15s,box-shadow .15s;"
               onmouseover="this.style.borderColor='#f97316';this.style.boxShadow='0 4px 12px rgba(0,0,0,.08)'"
               onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                <div style="font-weight:700;color:var(--text);font-size:15px;margin-bottom:4px;"><?= htmlspecialchars($c['nom']) ?></div>
                <div style="font-size:13px;color:var(--text-muted);"><?= $c['code_postal'] ?> · <?= $item['nb'] ?> <?= $item['nb'] > 1 ? METIER_PLURIEL : METIER ?></div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Éditorial zone climatique -->
    <section class="mb-8" style="background:#fff;border-radius:12px;border:1px solid #e5e7eb;padding:24px;">
        <h2 style="font-family:var(--font-display);font-size:18px;font-weight:700;color:var(--text);margin-bottom:12px;">
            <?= htmlspecialchars($modNom) ?> <?= htmlspecialchars($artDept) ?> — Zone climatique <?= htmlspecialchars($zone) ?>
        </h2>
        <p style="font-size:14px;color:var(--text-muted);line-height:1.7;"><?= htmlspecialchars($editorialText) ?></p>
    </section>

    <!-- FAQ -->
    <?php
    $questions = $faq;
    $title     = 'FAQ — ' . $modNom . ' ' . $artDept;
    require __DIR__ . '/../components/faq.php';
    ?>

    <!-- Autres services dans le département -->
    <section class="mt-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            🛠️ Autres services chauffage <?= htmlspecialchars($artDept) ?>
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            <?php foreach (MODELES as $m):
                if ($m['slug'] === $modele['slug']) continue;
                $url = urlModeleDepartement($regionSlug, $deptSlug, $m['slug']);
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
