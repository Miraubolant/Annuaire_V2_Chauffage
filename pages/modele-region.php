<?php
// Variables depuis router.php : $regionSlug, $regionData, $modele
$regionSlug ??= '';
$regionData ??= [];
$modele     ??= ['slug' => '', 'nom' => '', 'emoji' => '', 'vud_cat' => 10];
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

$deptMap   = getDeptMapping();
$regionNom = $regionData['region']['nom'] ?? nomRegion($regionSlug);
$stats     = $regionData['stats'] ?? [];
$depts     = $regionData['departements'] ?? [];
$modNom    = $modele['nom'];
$vudCat    = getVudCatForModele($modele['slug']);

// Nombre d'artisans total dans la région (clé artisans_* dynamique)
$nbArtisans = 0;
foreach ($stats as $k => $v) {
    if (str_starts_with($k, 'artisans_') && is_int($v)) { $nbArtisans = $v; break; }
}

// Zone climatique dominante de la région
$zoneByRegion = [
    'hauts-de-france'            => 'H1',
    'normandie'                  => 'H1',
    'grand-est'                  => 'H1',
    'bretagne'                   => 'H1',
    'bourgogne-franche-comte'    => 'H1',
    'auvergne-rhone-alpes'       => 'H1',
    'ile-de-france'              => 'H2',
    'centre-val-de-loire'        => 'H2',
    'pays-de-la-loire'           => 'H2',
    'nouvelle-aquitaine'         => 'H2',
    'occitanie'                  => 'H3',
    'provence-alpes-cote-d-azur' => 'H3',
    'corse'                      => 'H3',
    'guadeloupe'                 => 'H3',
    'guyane'                     => 'H3',
    'martinique'                 => 'H3',
    'mayotte'                    => 'H3',
    'la-reunion'                 => 'H3',
];
$zone = $zoneByRegion[$regionSlug] ?? 'H2';

$zoneEditorial = [
    'H1' => [
        'label' => 'Zone H1 — Climat nordique',
        'cee'   => 'financement RGE disponible',
        'text'  => "La région {$regionNom} bénéficie d'un climat nordique qui nécessite des systèmes de chauffage performants. La pompe à chaleur air-eau et la chaudière gaz à condensation sont les solutions les plus plébiscitées. Nos " . METIER_PLURIEL . " locaux certifiés RGE vous accompagnent pour un projet sur mesure avec MaPrimeRénov'.",
    ],
    'H2' => [
        'label' => 'Zone H2 — Climat tempéré',
        'cee'   => 'financement RGE disponible',
        'text'  => "La région {$regionNom} bénéficie d'un climat tempéré avec des hivers modérés, idéal pour une chaudière gaz ou une pompe à chaleur. Nos " . METIER_PLURIEL . " certifiés RGE vous proposent installation, remplacement, entretien annuel et dépannage avec TVA 5,5% et MaPrimeRénov' applicables.",
    ],
    'H3' => [
        'label' => 'Zone H3 — Climat méditerranéen',
        'cee'   => 'financement RGE disponible',
        'text'  => "La région {$regionNom} jouit d'un climat méditerranéen avec des hivers doux, idéal pour la pompe à chaleur réversible. Le marché du remplacement de chaudière y est dynamique grâce aux aides de l'État. Nos " . METIER_PLURIEL . " qualifiés RGE réalisent tous vos projets de chauffage renouvelable avec financement disponible.",
    ],
];
$editorial = $zoneEditorial[$zone];
$editorialText = str_replace('{regionNom}', $regionNom, $editorial['text']);

// Artisans par département (chargement des fichiers niche)
$deptArtisans = [];
foreach ($depts as $dept) {
    $dCode = strtoupper($dept['code']);
    $dNom  = $deptMap[$dept['code']]['nom'] ?? $dept['nom'] ?? $dept['code'];
    $dSlug = $deptMap[$dept['code']]['slug'] ?? $dept['slug'] ?? '';
    $nicheFile = DATA_DIR . '/' . NICHE_DIR . '/' . $dCode . '.json';
    $nb = 0;
    if (file_exists($nicheFile)) {
        $nd = json_decode(file_get_contents($nicheFile), true) ?? [];
        foreach ($nd as $cd) { $nb += count($cd['artisans'] ?? []); }
        unset($nd);
    }
    $deptArtisans[] = ['code' => $dept['code'], 'slug' => $dSlug, 'nom' => $dNom, 'nb' => $nb];
}
usort($deptArtisans, fn($a, $b) => $b['nb'] <=> $a['nb']);

$title         = seoTitle('modele-region', ['modele' => $modele, 'region_slug' => $regionSlug]);
$description   = seoDescription('modele-region', ['modele' => $modele, 'region_slug' => $regionSlug, 'nb_artisans' => $nbArtisans]);
$canonical_url = urlModeleRegion($regionSlug, $modele['slug']);
$robots        = 'index,follow';

$trail = [
    ['name' => 'Accueil',    'url' => SITE_URL . '/'],
    ['name' => $regionNom,   'url' => urlRegion($regionSlug)],
    ['name' => $modNom,      'url' => $canonical_url],
];

$jsonLd = [jsonLdBreadcrumbs($trail)];
$jsonLd[] = '<script type="application/ld+json">' . json_encode([
    '@context'   => 'https://schema.org',
    '@type'      => 'Service',
    'name'       => $modNom . ' en ' . $regionNom,
    'provider'   => ['@type' => 'Organization', 'name' => SITE_NAME],
    'areaServed' => ['@type' => 'State', 'name' => $regionNom],
    'serviceType' => METIER_CAP,
], JSON_UNESCAPED_UNICODE) . '</script>';

$faq = [
    ['q' => "Quel est le coût de \"{$modNom}\" en {$regionNom} ?",
     'r' => "Le tarif pour {$modNom} en {$regionNom} varie selon le type de projet, la taille et les équipements choisis. Demandez plusieurs devis gratuits aux " . METIER_PLURIEL . " de votre département pour comparer les offres."],
    ['q' => "Comment financer \"{$modNom}\" en {$regionNom} ?",
     'r' => "Des solutions de financement RGE sont {$editorial['cee']}. La TVA à 5,5% s'applique aux travaux de chauffage. Pour une pompe à chaleur air-eau, un crédit d'impôt peut être accessible. Votre " . METIER . " vous guide dans les démarches."],
    ['q' => "Combien de " . METIER_PLURIEL . " proposent \"{$modNom}\" en {$regionNom} ?",
     'r' => "{$nbArtisans} " . METIER_PLURIEL . " sont référencés en {$regionNom}. Sélectionnez votre département ci-dessous pour trouver les professionnels les plus proches de chez vous."],
    ['q' => "Quelle TVA s'applique pour \"{$modNom}\" en {$regionNom} ?",
     'r' => "La TVA à 5,5% s'applique aux travaux de chauffage. Votre " . METIER . " vous confirmera les conditions lors de l'établissement du devis gratuit."],
    ['q' => "Quelle garantie pour \"{$modNom}\" en {$regionNom} ?",
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
                    <?= htmlspecialchars($regionNom) ?>
                </span>
            </div>

            <h1 style="font-family:var(--font-display);font-size:clamp(24px,3.2vw,42px);font-weight:700;color:#fff;line-height:1.2;margin-bottom:14px;letter-spacing:-.02em;">
                <?= htmlspecialchars($modNom) ?><br>
                <em style="color:#F0A07A;font-style:italic;">en <?= htmlspecialchars($regionNom) ?></em>
            </h1>

            <p style="font-size:15px;color:rgba(255,255,255,.65);margin-bottom:28px;line-height:1.7;">
                <?= METIER_CAP ?>s qualifiés en <?= htmlspecialchars($regionNom) ?> — TVA 5,5%, financement RGE disponible, devis gratuit.
            </p>

            <div class="ph-stat-row">
                <div class="ph-stat">
                    <div class="ph-stat-num"><?= number_format($nbArtisans, 0, ',', ' ') ?></div>
                    <div class="ph-stat-label"><?= METIER_CAP ?>s</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,.15);align-self:stretch;"></div>
                <div class="ph-stat">
                    <div class="ph-stat-num"><?= count($deptArtisans) ?></div>
                    <div class="ph-stat-label">Départements</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,.15);align-self:stretch;"></div>
                <div class="ph-stat">
                    <div class="ph-stat-num">TVA 5,5%</div>
                    <div class="ph-stat-label">Financement <?= $editorial['cee'] ?></div>
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
                    <a href="<?= htmlspecialchars(urlModeleRegion($regionSlug, $m['slug'])) ?>"
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

    <!-- Départements de la région -->
    <section class="mb-8">
        <h2 style="font-family:var(--font-display);font-size:22px;font-weight:700;color:var(--text);margin-bottom:16px;">
            <?= htmlspecialchars($modNom) ?> par département en <?= htmlspecialchars($regionNom) ?>
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($deptArtisans as $d): if ($d['nb'] < 1) continue; ?>
            <a href="<?= htmlspecialchars(urlModeleDepartement($regionSlug, $d['slug'], $modele['slug'])) ?>"
               style="text-decoration:none;display:block;background:#fff;border-radius:12px;border:1px solid #e5e7eb;padding:16px 20px;transition:border-color .15s,box-shadow .15s;"
               onmouseover="this.style.borderColor='#f97316';this.style.boxShadow='0 4px 12px rgba(0,0,0,.08)'"
               onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                <div style="font-weight:700;color:var(--text);font-size:15px;margin-bottom:4px;"><?= htmlspecialchars($d['nom']) ?></div>
                <div style="font-size:13px;color:var(--text-muted);"><?= $d['nb'] ?> <?= $d['nb'] > 1 ? METIER_PLURIEL : METIER ?></div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Éditorial zone climatique -->
    <section class="mb-8" style="background:#fff;border-radius:12px;border:1px solid #e5e7eb;padding:24px;">
        <h2 style="font-family:var(--font-display);font-size:18px;font-weight:700;color:var(--text);margin-bottom:12px;">
            <?= htmlspecialchars($modNom) ?> en <?= htmlspecialchars($regionNom) ?> — <?= htmlspecialchars($editorial['label']) ?>
        </h2>
        <p style="font-size:14px;color:var(--text-muted);line-height:1.7;"><?= htmlspecialchars($editorialText) ?></p>
    </section>

    <!-- FAQ -->
    <?php
    $questions = $faq;
    $title     = 'FAQ — ' . $modNom . ' en ' . $regionNom;
    require __DIR__ . '/../components/faq.php';
    ?>

    <!-- Autres services dans la région -->
    <section class="mt-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            🛠️ Autres services chauffage en <?= htmlspecialchars($regionNom) ?>
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            <?php foreach (MODELES as $m):
                if ($m['slug'] === $modele['slug']) continue;
                $url = urlModeleRegion($regionSlug, $m['slug']);
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
