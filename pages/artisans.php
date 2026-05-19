<?php
// Variables depuis router.php : $artisans, $commune, $deptCode, $deptSlug, $regionSlug, $villeSlug, $villeCp
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
$villeNom = $commune['nom'] ?? '';
$artVille = articleVille($villeNom);

// Tri par note desc
usort($artisans, fn($a, $b) => ($b['note'] ?? 0) <=> ($a['note'] ?? 0));

$page    = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 20;
$total   = count($artisans);
$pages   = max(1, (int) ceil($total / $perPage));
$page    = min($page, $pages);
$slice   = array_slice($artisans, ($page - 1) * $perPage, $perPage);

$title         = 'Les ' . $total . ' ' . METIER_PLURIEL . ' ' . $artVille . ' — Annuaire';
$description   = 'Annuaire complet des ' . $total . ' ' . METIER_PLURIEL . ' ' . $artVille . '. Contacts, avis, adresses. Devis gratuit.';
$canonical_url = urlArtisans($regionSlug, $deptSlug, $villeSlug, $villeCp);
$robots        = $page > 1 ? 'noindex,follow' : 'index,follow';

$trail = [
    ['name' => 'Accueil',              'url' => SITE_URL . '/'],
    ['name' => nomRegion($regionSlug), 'url' => urlRegion($regionSlug)],
    ['name' => $deptNom,               'url' => urlDepartement($regionSlug, $deptSlug)],
    ['name' => $villeNom,              'url' => urlVille($regionSlug, $deptSlug, $villeSlug, $villeCp)],
    ['name' => 'Tous les chauffagistes',  'url' => $canonical_url],
];
$jsonLd = [jsonLdBreadcrumbs($trail)];

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
                <span style="color:#F59E0B;letter-spacing:2px;font-size:16px;">★★★★★</span>
                <span style="font-weight:600;color:#fff;font-size:14px;">4.9/5</span>
                <span style="color:rgba(255,255,255,.4);font-size:13px;">· 10 000 chauffagistes en France</span>
            </div>

            <h1 style="font-family:var(--font-display);font-size:clamp(26px,3.5vw,44px);font-weight:700;color:#fff;line-height:1.2;margin-bottom:14px;letter-spacing:-.02em;">
                <?= $total ?> chauffagistes<br>
                <em style="color:#F0A07A;font-style:italic;"><?= htmlspecialchars($villeNom) ?></em>
            </h1>

            <p style="font-size:15px;color:rgba(255,255,255,.65);margin-bottom:28px;line-height:1.7;">
                Annuaire complet des chauffagistes qualifiés <?= htmlspecialchars($artVille) ?> — contacts, avis et devis gratuit.
            </p>

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
                <p style="font-size:11px;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.08em;font-weight:700;margin-bottom:10px;">Nos services <?= htmlspecialchars($artVille) ?></p>
                <div class="ph-tags">
                    <?php foreach (array_slice(MODELES, 0, 5) as $m): ?>
                    <span class="ph-tag"><?= $m['emoji'] ?> <?= htmlspecialchars($m['nom']) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Colonne droite : widget devis -->
        <div>
            <div class="ph-widget-card">
                <div class="ph-widget-header">
                    <span style="font-size:22px;">🏊</span>
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

<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
        <p style="font-size:14px;color:var(--text-muted);">
            <?= $total ?> chauffagiste<?= $total > 1 ? 's' : '' ?> référencé<?= $total > 1 ? 's' : '' ?> — page <?= $page ?>/<?= $pages ?>
        </p>
        <a href="<?= htmlspecialchars(urlVille($regionSlug, $deptSlug, $villeSlug, $villeCp)) ?>"
           style="font-size:13px;font-weight:600;color:var(--forest);text-decoration:none;">
            ← Retour à <?= htmlspecialchars($villeNom) ?>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <?php foreach ($slice as $artisan): ?>
        <?php require __DIR__ . '/../components/card-artisan.php'; ?>
        <?php endforeach; ?>
    </div>

    <?php
    $baseUrl    = $canonical_url;
    $totalPages = $pages;
    require __DIR__ . '/../components/pagination.php';
    ?>

    <div class="mt-8">
        <?php
        $villeName = $villeNom;
        $context   = 'ville';
        $vudCat    = VUD_CATEGORIE_ID;
        require __DIR__ . '/../components/cta-devis.php';
        ?>
    </div>
</main>

<?php require __DIR__ . '/../templates/footer.php'; ?>

