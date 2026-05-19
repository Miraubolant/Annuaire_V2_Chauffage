<?php
// Variables injectées par router.php
$regionSlug ??= '';
$regionData ??= [];
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

$regionNom  = $regionData['region']['nom'] ?? nomRegion($regionSlug);
$stats      = $regionData['stats'] ?? [];
$depts      = $regionData['departements'] ?? [];
$nbArt      = number_format($stats['artisans_chauffage'] ?? 0, 0, ',', ' ');
$nbCom      = number_format($stats['communes_avec_artisans'] ?? 0, 0, ',', ' ');
$nbDep      = count($depts);

// ── Top villes de la région ───────────────────────────────────────────────────
// Chargement manuel sans cache static pour éviter l'épuisement mémoire
// (12 fichiers JSON lourds en mémoire simultanée = fatal error)
$topVilles = [];
foreach ($depts as $dept) {
    $dFile = DATA_DIR . '/' . strtoupper($dept['code']) . '.json';
    if (!file_exists($dFile)) continue;
    $raw  = file_get_contents($dFile);
    $data = json_decode($raw, true);
    unset($raw);
    foreach ($data['communes'] ?? [] as $commune) {
        $nb = $commune['artisans'][NICHE_KEY] ?? 0;
        if ($nb < 1) continue;
        $topVilles[] = [
            'nom'      => $commune['nom'],
            'slug'     => $commune['slug'],
            'cp'       => $commune['code_postal'],
            'artisans' => $nb,
            'deptSlug' => $dept['slug'],
        ];
    }
    unset($data);
}
usort($topVilles, fn($a, $b) => $b['artisans'] <=> $a['artisans']);
$topVilles = array_slice($topVilles, 0, 24);

// ── Autres régions ────────────────────────────────────────────────────────────
$autresRegions = [];
foreach (glob(DATA_DIR . '/regions/*.json') as $f) {
    $slug = basename($f, '.json');
    if ($slug === $regionSlug) continue;
    $rd = getRegionData($slug);
    $autresRegions[] = [
        'slug' => $slug,
        'nom'  => $rd['region']['nom'] ?? nomRegion($slug),
        'nb'   => $rd['stats']['artisans_chauffage'] ?? 0,
    ];
}
usort($autresRegions, fn($a, $b) => $b['nb'] <=> $a['nb']);

// ── Groupes de services (nom différent de $serviceGroups dans header.php) ────
$regionSvcGroups = [
    'Chaudières 🔥'                => ['installation-chaudiere-gaz', 'installation-chaudiere-fioul', 'remplacement-chaudiere'],
    'Chauffage renouvelable ♻️'    => ['pompe-chaleur-air-eau', 'poele-bois-pellets', 'chaudiere-biomasse'],
    'Émetteurs & distribution 🏠'  => ['plancher-chauffant', 'radiateurs-emetteurs', 'ballon-eau-chaude'],
    'Entretien & SAV 🧰'           => ['entretien-chaudiere', 'depannage-chauffage', 'audit-energetique'],
];
$modeleIndex = [];
foreach (MODELES as $m) { $modeleIndex[$m['slug']] = $m['nom']; }

// ── SEO ───────────────────────────────────────────────────────────────────────
$title         = seoTitle('region', ['slug' => $regionSlug]);
$description   = seoDescription('region', array_merge(['slug' => $regionSlug], $stats));
$canonical_url = urlRegion($regionSlug);
$robots        = 'index,follow';

$trail = [
    ['name' => 'Accueil',  'url' => SITE_URL . '/'],
    ['name' => $regionNom, 'url' => $canonical_url],
];
$jsonLd = [jsonLdBreadcrumbs($trail)];

$faq = [
    ['q' => "Combien de chauffagistes y a-t-il " . articleRegion($regionSlug) . " ?",
     'r' => "Il y a " . $nbArt . " chauffagistes référencés " . articleRegion($regionSlug) . " dans notre annuaire, répartis dans " . $nbCom . " communes et " . $nbDep . " département" . ($nbDep > 1 ? 's' : '') . "."],
    ['q' => "Quels financements sont disponibles pour une chaudière " . articleRegion($regionSlug) . " ?",
     'r' => "Les propriétaires " . articleRegion($regionSlug) . " peuvent bénéficier de la TVA à 5,5% sur les travaux de chauffage, du crédit d'impôt pour une pompe à chaleur air-eau, et de solutions de financement RGE. Votre chauffagiste local vous accompagne dans l'optimisation de votre budget."],
    ['q' => "Comment trouver un chauffagiste qualifié " . articleRegion($regionSlug) . " ?",
     'r' => "Cherchez votre ville dans notre annuaire pour trouver les chauffagistes certifiés RGE ou QualiPAC " . articleRegion($regionSlug) . ". Ces certifications sont gage de qualité et de professionnalisme."],
    ['q' => "Quels sont les délais pour installer une chaudière " . articleRegion($regionSlug) . " ?",
     'r' => "Les délais varient : 1 à 2 jours pour une chaudière gaz à condensation, 1 à 2 semaines pour une pompe à chaleur air-eau. Anticipez en contactant votre chauffagiste dès l'été pour des travaux avant l'hiver."],
    ['q' => "Quelle est la différence entre une chaudière gaz et une pompe à chaleur " . articleRegion($regionSlug) . " ?",
     'r' => "La chaudière gaz à condensation (2 500 € à 5 000 € installée) est la plus répandue, éligible CEE BAR-TH-106. La pompe à chaleur air-eau (8 000 € à 18 000 €) est plus économe à long terme, éligible MaPrimeRénov' jusqu'à 11 000 €. Votre chauffagiste " . articleRegion($regionSlug) . " vous conseille selon votre logement."],
];
$jsonLd[] = jsonLdFAQ($faq);

require __DIR__ . '/../templates/header.php';
?>

<main>

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
                <span style="color:#F59E0B;letter-spacing:2px;font-size:16px;">★★★★★</span>
                <span style="font-weight:600;color:#fff;font-size:14px;">4.9/5</span>
                <span style="color:rgba(255,255,255,.4);font-size:13px;">· <?= $nbArt ?> chauffagistes</span>
            </div>

            <h1 style="font-family:var(--font-display);font-size:clamp(26px,3.5vw,44px);font-weight:700;color:#fff;line-height:1.2;margin-bottom:14px;letter-spacing:-.02em;">
                chauffagistes<br>
                <em style="color:#F0A07A;font-style:italic;"><?= htmlspecialchars($regionNom) ?></em>
            </h1>

            <p style="font-size:15px;color:rgba(255,255,255,.65);margin-bottom:28px;line-height:1.7;">
                Trouvez un chauffagiste qualifié <?= htmlspecialchars(articleRegion($regionSlug)) ?> — devis gratuit, TVA 5,5%, financement RGE disponible.
            </p>

            <div class="ph-stat-row">
                <div class="ph-stat">
                    <div class="ph-stat-num"><?= $nbArt ?></div>
                    <div class="ph-stat-label">chauffagistes</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,.15);align-self:stretch;"></div>
                <div class="ph-stat">
                    <div class="ph-stat-num"><?= $nbCom ?></div>
                    <div class="ph-stat-label">Communes</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,.15);align-self:stretch;"></div>
                <div class="ph-stat">
                    <div class="ph-stat-num"><?= $nbDep ?></div>
                    <div class="ph-stat-label">Département<?= $nbDep > 1 ? 's' : '' ?></div>
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
                        <div style="font-weight:700;color:#fff;font-size:13px;">chauffagistes assurés</div>
                        <div style="font-size:11px;color:rgba(255,255,255,.5);margin-top:2px;">Garantie décennale</div>
                    </div>
                </div>
            </div>

            <div>
                <p style="font-size:11px;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.08em;font-weight:700;margin-bottom:10px;">Départements</p>
                <div class="ph-tags">
                    <?php foreach (array_slice($depts, 0, 6) as $d): ?>
                    <span class="ph-tag"><?= htmlspecialchars($d['nom']) ?> (<?= $d['code'] ?>)</span>
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

<!-- ── Contenu éditorial SEO ───────────────────────────────────────────────── -->
<section style="background:var(--cream);padding:48px 24px;border-bottom:1px solid var(--stone);">
    <div style="max-width:1280px;margin:0 auto;">
        <div style="max-width:860px;">
            <h2 style="font-family:var(--font-display);font-size:22px;font-weight:700;color:var(--text);margin-bottom:12px;">
                Pourquoi faire appel à un chauffagiste <?= htmlspecialchars(articleRegion($regionSlug)) ?> ?
            </h2>
            <p style="font-size:15px;color:var(--text-muted);line-height:1.8;">
                Les chauffagistes <?= htmlspecialchars(articleRegion($regionSlug)) ?> couvrent un large éventail de prestations : installation chaudière gaz à condensation, pompe à chaleur air-eau,
                chaudière biomasse, plancher chauffant hydraulique, radiateurs, entretien annuel chaudière, dépannage chauffage urgence,
                audit énergétique et bilan thermique.
                Avec <strong><?= $nbArt ?> chauffagistes référencés</strong> dans la région <?= htmlspecialchars($regionNom) ?>,
                vous trouverez facilement un professionnel certifié proche de chez vous pour bénéficier de la TVA 5,5%
                et des solutions de financement RGE.
            </p>
        </div>
    </div>
</section>

<!-- ── Départements ────────────────────────────────────────────────────────── -->
<section style="padding:56px 24px;background:var(--white);border-bottom:1px solid var(--stone);">
    <div style="max-width:1280px;margin:0 auto;">
        <div style="margin-bottom:32px;">
            <span class="section-eyebrow">📍 <?= $nbDep ?> département<?= $nbDep > 1 ? 's' : '' ?></span>
            <h2 class="section-title" style="margin-top:8px;">chauffagistes par département <?= htmlspecialchars(articleRegion($regionSlug)) ?></h2>
            <p class="section-subtitle">Sélectionnez votre département pour voir les artisans disponibles.</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;">
            <?php foreach ($depts as $dept): ?>
            <?php
            $dUrl  = urlDepartement($regionSlug, $dept['slug']);
            $dNb   = number_format($dept['artisans_chauffage'] ?? 0, 0, ',', ' ');
            $dCom  = $dept['communes_avec_artisans'] ?? 0;
            ?>
            <a href="<?= htmlspecialchars($dUrl) ?>"
               style="display:block;background:var(--cream);border:1px solid var(--stone);border-radius:var(--radius);padding:20px;text-decoration:none;transition:border-color .15s,box-shadow .15s,transform .15s;"
               onmouseover="this.style.borderColor='var(--forest)';this.style.boxShadow='var(--shadow)';this.style.transform='translateY(-2px)';"
               onmouseout="this.style.borderColor='var(--stone)';this.style.boxShadow='none';this.style.transform='';">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                    <span style="background:var(--forest);color:#fff;font-size:11px;font-weight:800;padding:3px 8px;border-radius:6px;letter-spacing:.04em;"><?= htmlspecialchars($dept['code']) ?></span>
                    <span style="font-weight:600;color:var(--text);font-size:14px;"><?= htmlspecialchars($dept['nom']) ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--text-muted);">
                    <span>🔥 <?= $dNb ?> chauffagistes</span>
                    <span>📍 <?= $dCom ?> communes</span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── Top villes ─────────────────────────────────────────────────────────── -->
<?php if (!empty($topVilles)): ?>
<section style="padding:56px 24px;background:var(--stone);border-bottom:1px solid var(--stone);">
    <div style="max-width:1280px;margin:0 auto;">
        <div style="margin-bottom:32px;">
            <span class="section-eyebrow">🏘️ Villes les mieux couvertes</span>
            <h2 class="section-title" style="margin-top:8px;">chauffagistes dans les principales villes <?= htmlspecialchars(articleRegion($regionSlug)) ?></h2>
            <p class="section-subtitle">Accédez directement aux artisans de votre ville.</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:10px;">
            <?php foreach ($topVilles as $ville): ?>
            <?php
            $vUrl = urlVille($regionSlug, $ville['deptSlug'], $ville['slug'], $ville['cp']);
            ?>
            <a href="<?= htmlspecialchars($vUrl) ?>"
               style="display:flex;align-items:center;justify-content:space-between;gap:8px;background:var(--white);border:1px solid rgba(0,0,0,.06);border-radius:var(--radius-sm);padding:10px 14px;text-decoration:none;font-size:13px;transition:border-color .15s,background .15s;"
               onmouseover="this.style.borderColor='var(--forest)';this.style.background='var(--cream)';"
               onmouseout="this.style.borderColor='rgba(0,0,0,.06)';this.style.background='var(--white)';">
                <span style="font-weight:600;color:var(--text);"><?= htmlspecialchars($ville['nom']) ?></span>
                <span style="flex-shrink:0;font-size:11px;font-weight:700;color:var(--forest);background:rgba(61,74,82,.08);padding:2px 7px;border-radius:100px;"><?= $ville['artisans'] ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ── Services disponibles ────────────────────────────────────────────────── -->
<section style="padding:56px 24px;background:var(--white);border-bottom:1px solid var(--stone);">
    <div style="max-width:1280px;margin:0 auto;">
        <div style="margin-bottom:32px;">
            <span class="section-eyebrow">🔧 12 services</span>
            <h2 class="section-title" style="margin-top:8px;">services chauffage <?= htmlspecialchars(articleRegion($regionSlug)) ?></h2>
            <p class="section-subtitle">Tous les travaux de chauffage disponibles auprès de nos chauffagistes référencés.</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:24px;">
            <?php foreach ($regionSvcGroups as $groupLabel => $slugs): ?>
            <div>
                <div style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--forest);margin-bottom:10px;padding-bottom:8px;border-bottom:2px solid var(--stone);"><?= htmlspecialchars($groupLabel) ?></div>
                <?php foreach ($slugs as $sSlug): ?>
                <?php $sNom = $modeleIndex[$sSlug] ?? $sSlug; ?>
                <a href="<?= VUD_DEVIS_URL ?>"
                   style="display:block;padding:5px 0;font-size:13px;color:var(--text-muted);text-decoration:none;transition:color .12s,padding-left .12s;"
                   onmouseover="this.style.color='var(--forest)';this.style.paddingLeft='5px';"
                   onmouseout="this.style.color='var(--text-muted)';this.style.paddingLeft='0';"
                   rel="noopener sponsored" target="_blank">
                    <?= htmlspecialchars($sNom) ?> <?= htmlspecialchars($regionNom) ?>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── Aides à la rénovation ──────────────────────────────────────────────── -->
<section style="padding:56px 24px;background:var(--stone);border-bottom:1px solid var(--stone);">
    <div style="max-width:1280px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:36px;">
            <span class="section-eyebrow">💶 Financements 2026</span>
            <h2 class="section-title" style="margin-top:8px;">Aides et aides chauffage <?= htmlspecialchars(articleRegion($regionSlug)) ?></h2>
            <p class="section-subtitle">Cumulables · Sans avance de frais · Applicables dans tous les départements de la région</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;">
            <?php foreach (AIDES_NATIONALES as $aide): ?>
            <div style="background:var(--white);border:1px solid var(--stone);border-radius:var(--radius);padding:24px;">
                <div style="font-weight:700;color:var(--text);font-size:15px;margin-bottom:6px;"><?= htmlspecialchars($aide['nom']) ?></div>
                <div style="font-family:var(--font-display);font-size:20px;font-weight:700;color:var(--forest);margin-bottom:8px;"><?= htmlspecialchars($aide['montant']) ?></div>
                <div style="font-size:13px;color:var(--text-muted);line-height:1.6;"><?= htmlspecialchars($aide['conditions']) ?></div>
                <a href="<?= htmlspecialchars($aide['url']) ?>"
                   style="display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:600;color:var(--forest);text-decoration:none;margin-top:12px;"
                   target="_blank" rel="noopener nofollow">En savoir plus →</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── Autres régions ──────────────────────────────────────────────────────── -->
<section style="padding:48px 24px;background:var(--cream);border-bottom:1px solid var(--stone);">
    <div style="max-width:1280px;margin:0 auto;">
        <div style="margin-bottom:24px;">
            <span class="section-eyebrow">🗺️ Toutes les régions</span>
            <h2 class="section-title" style="margin-top:8px;font-size:clamp(18px,2.5vw,26px);">chauffagistes dans les autres régions de France</h2>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:8px;">
            <?php foreach ($autresRegions as $ar): ?>
            <a href="<?= htmlspecialchars(urlRegion($ar['slug'])) ?>"
               style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;background:var(--white);border:1px solid var(--stone);border-radius:100px;text-decoration:none;font-size:13px;font-weight:500;color:var(--text);transition:border-color .15s,color .15s;"
               onmouseover="this.style.borderColor='var(--forest)';this.style.color='var(--forest)';"
               onmouseout="this.style.borderColor='var(--stone)';this.style.color='var(--text)';">
                <?= htmlspecialchars($ar['nom']) ?>
                <span style="font-size:11px;color:var(--text-muted);"><?= number_format($ar['nb'], 0, ',', ' ') ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── FAQ ────────────────────────────────────────────────────────────────── -->
<section style="padding:56px 24px;background:var(--stone);">
    <div style="max-width:800px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:36px;">
            <span class="section-eyebrow">❓ Questions fréquentes</span>
            <h2 class="section-title" style="margin-top:8px;">chauffagiste <?= htmlspecialchars(articleRegion($regionSlug)) ?> — FAQ</h2>
        </div>
        <?php $questions = $faq; require __DIR__ . '/../components/faq.php'; ?>
    </div>
</section>

</main>

<?php require __DIR__ . '/../templates/footer.php'; ?>

