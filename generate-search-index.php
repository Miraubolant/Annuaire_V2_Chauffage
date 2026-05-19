<?php
/**
 * Génère output/search-index.json — index léger pour l'autocomplete.
 * Lit chaque fichier dept séquentiellement sans cache pour limiter la mémoire.
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$deptMap = getDeptMapping();
$index   = [];

// Pré-charger les compteurs artisans depuis les fichiers niche (plus fiables que le champ geo)
$nicheCountByDept = [];
foreach (glob(DATA_DIR . '/' . NICHE_DIR . '/*.json') as $nicheFile) {
    $dc = strtolower(basename($nicheFile, '.json'));
    $nicheData = json_decode(file_get_contents($nicheFile), true) ?? [];
    foreach ($nicheData as $slug => $communeData) {
        $nicheCountByDept[$dc][$slug] = count($communeData['artisans'] ?? []);
    }
}

foreach ($deptMap as $code => $info) {
    $file = DATA_DIR . '/' . strtoupper($code) . '.json';
    if (!file_exists($file)) continue;

    // Lecture sans passer par getGeoData() (qui met tout en cache statique)
    $geo = json_decode(file_get_contents($file), true);
    if (empty($geo['communes'])) { unset($geo); continue; }

    $regionSlug = $info['region_slug'];
    $deptSlug   = $info['slug'];
    $dc         = strtolower($code);

    foreach ($geo['communes'] as $commune) {
        $nb  = $nicheCountByDept[$dc][$commune['slug']] ?? (int)($commune['artisans'][NICHE_KEY] ?? 0);
        $cp  = $commune['code_postal'];
        $sl  = $commune['slug'];
        $nom = $commune['nom'];

        // Index minimal : pas de villes_proches ni de logement
        $index[] = [
            'n' => $nom,                                                          // nom
            'c' => $cp,                                                           // code postal
            'u' => '/' . $regionSlug . '/' . $deptSlug . '/' . $sl . '-' . $cp . '/',  // url
            'a' => $nb,                                                           // artisans
        ];
    }
    unset($geo); // libérer la mémoire avant le prochain fichier
}

$out = DATA_DIR . '/search-index.json';
file_put_contents($out, json_encode($index, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
$size = round(filesize($out) / 1024);
echo "OK — " . count($index) . " communes — {$size} Ko → $out\n";

