<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$file    = $_GET['file'] ?? 'sitemap';
$today   = date('Y-m-d');
$deptMap = getDeptMapping();

header('Content-Type: application/xml; charset=utf-8');
header('X-Robots-Tag: noindex');

// ─── Sitemap index ────────────────────────────────────────────────────────────
if ($file === 'sitemap') {
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    $sitemaps = [
        'sitemap-meta',
        'sitemap-villes',
        'sitemap-artisans-1',
        'sitemap-artisans-2',
        'sitemap-modeles-1',
        'sitemap-modeles-2',
        'sitemap-modeles-3',
        'sitemap-modeles-4',
        'sitemap-modeles-5',
        'sitemap-modeles-6',
        'sitemap-modeles-7',
        'sitemap-modeles-8',
        'sitemap-modeles-region',
        'sitemap-modeles-dept',
    ];
    foreach ($sitemaps as $s) {
        echo "  <sitemap>\n";
        echo "    <loc>" . SITE_URL . '/' . $s . '.xml</loc>' . "\n";
        echo "    <lastmod>{$today}</lastmod>\n";
        echo "  </sitemap>\n";
    }
    echo '</sitemapindex>';
    exit;
}

// ─── Helpers ─────────────────────────────────────────────────────────────────
function sitemapUrl(string $loc, string $lastmod, string $priority, string $freq = 'weekly'): string {
    return "  <url>\n    <loc>" . htmlspecialchars($loc) . "</loc>\n    <lastmod>{$lastmod}</lastmod>\n    <changefreq>{$freq}</changefreq>\n    <priority>{$priority}</priority>\n  </url>\n";
}

function startUrlset(): void {
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
}

// ─── Sitemap meta : home + régions + depts ────────────────────────────────────
if ($file === 'sitemap-meta') {
    startUrlset();
    echo sitemapUrl(SITE_URL . '/', $today, '1.0', 'daily');
    $regionSlugs = [
        'auvergne-rhone-alpes','bourgogne-franche-comte','bretagne',
        'centre-val-de-loire','corse','grand-est','guadeloupe','guyane',
        'hauts-de-france','ile-de-france','la-reunion','martinique','mayotte',
        'normandie','nouvelle-aquitaine','occitanie','pays-de-la-loire',
        'provence-alpes-cote-d-azur',
    ];
    foreach ($regionSlugs as $r) {
        echo sitemapUrl(urlRegion($r), $today, '0.7');
    }
    foreach ($deptMap as $code => $info) {
        echo sitemapUrl(urlDepartement($info['region_slug'], $info['slug']), $today, '0.8');
    }
    echo '</urlset>';
    exit;
}

// ─── Sitemap villes ──────────────────────────────────────────────────────────
// Seules les communes avec au moins 1 artisan sont indexées.
// On inclut aussi la page /artisans/ (index,follow, page 1) pour chaque ville.
if ($file === 'sitemap-villes') {
    startUrlset();
    foreach ($deptMap as $code => $info) {
        $geoFile = DATA_DIR . '/' . strtoupper($code) . '.json';
        if (!file_exists($geoFile)) continue;
        $raw = file_get_contents($geoFile);
        $geo = json_decode($raw, true);
        unset($raw);
        foreach ($geo['communes'] ?? [] as $commune) {
            if (($commune['artisans'][NICHE_KEY] ?? 0) < 1) continue;
            $villeUrl = urlVille($info['region_slug'], $info['slug'], $commune['slug'], $commune['code_postal']);
            echo sitemapUrl($villeUrl, $today, '0.9');
            $artUrl = urlArtisans($info['region_slug'], $info['slug'], $commune['slug'], $commune['code_postal']);
            echo sitemapUrl($artUrl, $today, '0.7', 'monthly');
        }
        unset($geo);
    }
    echo '</urlset>';
    exit;
}

// ─── Sitemaps artisans ────────────────────────────────────────────────────────
if (preg_match('/^sitemap-artisans-(\d+)$/', $file, $m)) {
    $part    = (int) $m[1];
    $offset  = ($part - 1) * 50000;
    $limit   = 50000;
    $count   = 0;
    $written = 0;
    $started = false;

    foreach ($deptMap as $code => $info) {
        $artFile = DATA_DIR . '/' . NICHE_DIR . '/' . strtoupper($code) . '.json';
        if (!file_exists($artFile)) continue;
        $artData = json_decode(file_get_contents($artFile), true) ?? [];

        $geoFile = DATA_DIR . '/' . strtoupper($code) . '.json';
        if (!file_exists($geoFile)) continue;
        $raw = file_get_contents($geoFile);
        $geo = json_decode($raw, true);
        unset($raw);

        foreach ($geo['communes'] ?? [] as $commune) {
            $villeArtisans = $artData[$commune['slug']]['artisans'] ?? [];
            foreach ($villeArtisans as $art) {
                $count++;
                if ($count <= $offset) continue;
                if ($written >= $limit) break 2;
                if (!$started) { startUrlset(); $started = true; }
                $url = urlArtisan($info['region_slug'], $info['slug'], $commune['slug'], $commune['code_postal'], $art['slug']);
                echo sitemapUrl($url, $today, '0.6', 'monthly');
                $written++;
            }
        }
        unset($geo, $artData);
        if ($written >= $limit) break; // évite de charger les depts restants inutilement
    }
    if (!$started) startUrlset();
    echo '</urlset>';
    exit;
}

// ─── Sitemaps modèles ─────────────────────────────────────────────────────────
// Uniquement les communes avec au moins 1 artisan pour éviter le thin content.
if (preg_match('/^sitemap-modeles-(\d+)$/', $file, $m)) {
    $part         = (int) $m[1];
    $modCount     = count(MODELES);
    $modesPerFile = (int) ceil($modCount / 8);
    $startMod     = ($part - 1) * $modesPerFile;
    $endMod       = min($startMod + $modesPerFile, $modCount);
    $partModeles  = array_slice(MODELES, $startMod, $endMod - $startMod);

    startUrlset();
    foreach ($deptMap as $code => $info) {
        $geoFile = DATA_DIR . '/' . strtoupper($code) . '.json';
        if (!file_exists($geoFile)) continue;
        $raw = file_get_contents($geoFile);
        $geo = json_decode($raw, true);
        unset($raw);
        foreach ($geo['communes'] ?? [] as $commune) {
            if (($commune['artisans'][NICHE_KEY] ?? 0) < 1) continue;
            foreach ($partModeles as $modele) {
                $url = urlModele($info['region_slug'], $info['slug'], $commune['slug'], $commune['code_postal'], $modele['slug']);
                echo sitemapUrl($url, $today, '0.7', 'monthly');
            }
        }
        unset($geo);
    }
    echo '</urlset>';
    exit;
}

// ─── Sitemap modèles × régions ───────────────────────────────────────────────
if ($file === 'sitemap-modeles-region') {
    $regionSlugs = [
        'auvergne-rhone-alpes','bourgogne-franche-comte','bretagne',
        'centre-val-de-loire','corse','grand-est','guadeloupe','guyane',
        'hauts-de-france','ile-de-france','la-reunion','martinique','mayotte',
        'normandie','nouvelle-aquitaine','occitanie','pays-de-la-loire',
        'provence-alpes-cote-d-azur',
    ];
    startUrlset();
    foreach ($regionSlugs as $r) {
        foreach (MODELES as $modele) {
            $url = SITE_URL . '/' . $r . '/' . $modele['slug'] . '/';
            echo sitemapUrl($url, $today, '0.6', 'monthly');
        }
    }
    echo '</urlset>';
    exit;
}

// ─── Sitemap modèles × départements ──────────────────────────────────────────
if ($file === 'sitemap-modeles-dept') {
    startUrlset();
    foreach ($deptMap as $code => $info) {
        $artFile = DATA_DIR . '/' . NICHE_DIR . '/' . strtoupper($code) . '.json';
        if (!file_exists($artFile)) continue;
        $artData = json_decode(file_get_contents($artFile), true) ?? [];
        $hasArtisans = false;
        foreach ($artData as $cd) {
            if (!empty($cd['artisans'])) { $hasArtisans = true; break; }
        }
        if (!$hasArtisans) continue;
        unset($artData);
        foreach (MODELES as $modele) {
            $url = SITE_URL . '/' . $info['region_slug'] . '/' . $info['slug'] . '/' . $modele['slug'] . '/';
            echo sitemapUrl($url, $today, '0.6', 'monthly');
        }
    }
    echo '</urlset>';
    exit;
}

// Fichier inconnu → 404
http_response_code(404);
echo '<?xml version="1.0" encoding="UTF-8"?><error>Not found</error>';
