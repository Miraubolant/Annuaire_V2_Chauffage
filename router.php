<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

// ─── Parse l'URI ──────────────────────────────────────────────────────────────
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');
$uri = '/' . ltrim($uri, '/');

// Supprimer le basePath si le site n'est pas à la racine
// $basePath = '/menuisier'; // décommenter si nécessaire
// $uri = preg_replace('#^' . preg_quote($basePath) . '#', '', $uri) ?: '/';

$segments = array_values(array_filter(explode('/', $uri)));
$count    = count($segments);

// ─── Routing ──────────────────────────────────────────────────────────────────

// Routes : pages légales & utilitaires
if ($count === 1) {
    $staticRoutes = [
        'contact'                  => __DIR__ . '/pages/contact.php',
        'mentions-legales'         => __DIR__ . '/pages/mentions-legales.php',
        'politique-confidentialite'=> __DIR__ . '/pages/politique-confidentialite.php',
        'aides'                    => __DIR__ . '/pages/aides.php',
    ];
    if (isset($staticRoutes[$segments[0]])) {
        require $staticRoutes[$segments[0]];
        exit;
    }
}

// Route : API recherche (JSON) — utilise l'index pré-construit
if ($count >= 2 && $segments[0] === 'api' && $segments[1] === 'search') {
    header('Content-Type: application/json; charset=utf-8');
    $q = strtolower(trim($_GET['q'] ?? ''));
    if (strlen($q) < 2) { echo json_encode([]); exit; }

    $indexFile = DATA_DIR . '/search-index.json';
    if (!file_exists($indexFile)) { echo json_encode([]); exit; }

    $allCommunes = json_decode(file_get_contents($indexFile), true) ?? [];

    // Deux passes : préfixe d'abord, puis "contient"
    $prefix  = [];
    $contain = [];
    $qNorm   = iconv('UTF-8', 'ASCII//TRANSLIT', $q) ?: $q;

    foreach ($allCommunes as $c) {
        $nomLow  = strtolower($c['n']);
        $nomNorm = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $c['n']) ?: $c['n']);
        $isPrefix  = str_starts_with($nomLow, $q) || str_starts_with($nomNorm, $qNorm);
        $isContain = stripos($nomLow, $q) !== false  || stripos($nomNorm, $qNorm) !== false
                  || str_starts_with($c['c'], $q);   // recherche par code postal
        if ($isPrefix)       { $prefix[]  = $c; if (count($prefix)  >= 10) break; }
        elseif ($isContain)  { $contain[] = $c; }
    }

    $merged = array_slice(array_merge($prefix, $contain), 0, 10);
    // Trier par artisans desc dans chaque groupe
    usort($merged, fn($a, $b) => $b['a'] <=> $a['a']);

    $results = array_map(fn($c) => [
        'nom'     => $c['n'],
        'cp'      => $c['c'],
        'url'     => $c['u'],
        'artisans'=> $c['a'],
    ], array_slice($merged, 0, 10));

    echo json_encode($results, JSON_UNESCAPED_UNICODE);
    exit;
}

// Route : Home
if ($count === 0 || ($count === 1 && $segments[0] === '')) {
    require __DIR__ . '/index.php';
    exit;
}

// ─── Valider le slug de région (segment 0) ────────────────────────────────────
$regionSlugs = [
    'auvergne-rhone-alpes', 'bourgogne-franche-comte', 'bretagne',
    'centre-val-de-loire', 'corse', 'grand-est', 'guadeloupe', 'guyane',
    'hauts-de-france', 'ile-de-france', 'la-reunion', 'martinique', 'mayotte',
    'normandie', 'nouvelle-aquitaine', 'occitanie', 'pays-de-la-loire',
    'provence-alpes-cote-d-azur',
];

$regionSlug = $segments[0] ?? '';
if (!in_array($regionSlug, $regionSlugs, true)) {
    require __DIR__ . '/templates/404.php';
    exit;
}

// Route : Région
if ($count === 1) {
    $regionData = getRegionData($regionSlug);
    if (empty($regionData)) { require __DIR__ . '/templates/404.php'; exit; }
    require __DIR__ . '/pages/region.php';
    exit;
}

// ─── Valider le slug de département (segment 1) ───────────────────────────────
$deptSlug = $segments[1] ?? '';
$deptCode = getDeptCodeBySlug($deptSlug);
if ($deptCode === null) {
    // count=2 : peut être un modèle × région ({region}/{modele-slug}/)
    if ($count === 2) {
        $modele = null;
        foreach (MODELES as $m) {
            if ($m['slug'] === $deptSlug) { $modele = $m; break; }
        }
        if ($modele !== null) {
            $regionData = getRegionData($regionSlug);
            if (!empty($regionData)) {
                require __DIR__ . '/pages/modele-region.php';
                exit;
            }
        }
    }
    require __DIR__ . '/templates/404.php';
    exit;
}

// Vérifier cohérence région/dept
$deptMap = getDeptMapping();
if (($deptMap[$deptCode]['region_slug'] ?? '') !== $regionSlug) {
    // Rediriger vers la bonne URL
    $correctRegion = $deptMap[$deptCode]['region_slug'] ?? null;
    if ($correctRegion) {
        $correctUrl = urlDepartement($correctRegion, $deptSlug);
        header('Location: ' . $correctUrl, true, 301);
    } else {
        require __DIR__ . '/templates/404.php';
    }
    exit;
}

$geoData = getGeoData($deptCode);
if (empty($geoData)) { require __DIR__ . '/templates/404.php'; exit; }

// Route : Département
if ($count === 2) {
    require __DIR__ . '/pages/departement.php';
    exit;
}

// ─── Valider le slug de ville-CP (segment 2) ─────────────────────────────────
$villeSegment = $segments[2] ?? '';

// Format attendu : {slug}-{cp} — le CP est le dernier segment numérique
if (!preg_match('/^(.+?)-(\d{5})$/', $villeSegment, $mVille)) {
    // count=3 : peut être un modèle × département ({region}/{dept}/{modele-slug}/)
    if ($count === 3) {
        $modele = null;
        foreach (MODELES as $mod) {
            if ($mod['slug'] === $villeSegment) { $modele = $mod; break; }
        }
        if ($modele !== null) {
            require __DIR__ . '/pages/modele-departement.php';
            exit;
        }
    }
    require __DIR__ . '/templates/404.php';
    exit;
}
$villeSlug = $mVille[1];
$villeCp   = $mVille[2];

$commune = getCommuneBySlug($deptCode, $villeSlug);
if ($commune === null || $commune['code_postal'] !== $villeCp) {
    // Essayer avec les codes_postaux alternatifs
    if ($commune !== null && in_array($villeCp, $commune['codes_postaux'] ?? [])) {
        // CP alternatif → rediriger vers le CP principal
        $canonicalUrl = urlVille($regionSlug, $deptSlug, $villeSlug, $commune['code_postal']);
        header('Location: ' . $canonicalUrl, true, 301);
        exit;
    }
    require __DIR__ . '/templates/404.php';
    exit;
}

// Route : Ville
if ($count === 3) {
    require __DIR__ . '/pages/ville.php';
    exit;
}

// ─── Segment 3 ────────────────────────────────────────────────────────────────
$segment3 = $segments[3] ?? '';

// Route : Liste artisans de la ville
if ($segment3 === 'artisans' && $count === 4) {
    $artisans = getArtisansByVille($deptCode, $villeSlug);
    require __DIR__ . '/pages/artisans.php';
    exit;
}

// Route : Fiche artisan
if ($segment3 === 'artisans' && $count === 5) {
    $artisanSlug = $segments[4] ?? '';
    $artisans    = getArtisansByVille($deptCode, $villeSlug);
    $artisan     = null;
    foreach ($artisans as $a) {
        if ($a['slug'] === $artisanSlug) { $artisan = $a; break; }
    }
    if ($artisan === null) { require __DIR__ . '/templates/404.php'; exit; }
    require __DIR__ . '/pages/artisan.php';
    exit;
}

// Route : Page modèle (service spécifique dans la ville)
$modeleSlug = $segment3;
$modele     = null;
foreach (MODELES as $m) {
    if ($m['slug'] === $modeleSlug) { $modele = $m; break; }
}
if ($modele !== null && $count === 4) {
    require __DIR__ . '/pages/modele.php';
    exit;
}

// Fallback 404
require __DIR__ . '/templates/404.php';
