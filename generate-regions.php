<?php
/**
 * Script one-shot : génère output/regions/{slug}.json pour les 18 régions
 * Usage : php generate-regions.php
 */

define('OUTPUT_DIR', __DIR__ . '/output');
define('REGIONS_DIR', OUTPUT_DIR . '/regions');

if (!is_dir(REGIONS_DIR)) {
    mkdir(REGIONS_DIR, 0755, true);
}

$regions = [];

// Pré-charger les comptes d'artisans par commune depuis artisans-chauffage/
// Structure : $artisansByDept[DEPT_CODE][commune_slug] = nb_artisans
$artisansByDept = [];
foreach (glob(OUTPUT_DIR . '/artisans-chauffage/*.json') as $nicheFile) {
    $deptCode = strtolower(basename($nicheFile, '.json'));
    $nicheData = json_decode(file_get_contents($nicheFile), true) ?? [];
    $artisansByDept[$deptCode] = [];
    foreach ($nicheData as $communeSlug => $communeData) {
        $artisansByDept[$deptCode][$communeSlug] = count($communeData['artisans'] ?? []);
    }
}

// Lire tous les fichiers géo départementaux
$files = glob(OUTPUT_DIR . '/*.json');
foreach ($files as $file) {
    $data = json_decode(file_get_contents($file), true);
    if (!$data || empty($data['region']['slug'])) continue;

    $rSlug = $data['region']['slug'];
    $dCode = $data['departement']['code'];

    if (!isset($regions[$rSlug])) {
        $regions[$rSlug] = [
            'region' => $data['region'],
            'departements' => [],
            'stats' => [
                'communes_total' => 0,
                'communes_avec_artisans' => 0,
                'artisans_chauffage' => 0,
                'population_totale' => 0,
            ],
            'zones_climatiques' => [],
        ];
    }

    // Stats département
    $deptStats = [
        'code' => $dCode,
        'nom' => $data['departement']['nom'],
        'slug' => $data['departement']['slug'],
        'population_totale' => $data['departement']['population_totale'],
        'communes_count' => $data['communes_count'],
        'communes_avec_artisans' => 0,
        'artisans_chauffage' => 0,
        'voisins' => $data['voisins'] ?? [],
    ];

    foreach ($data['communes'] as $commune) {
        $dCodeLower = strtolower($dCode);
        $nbArtisans = $artisansByDept[$dCodeLower][$commune['slug']] ?? 0;

        $regions[$rSlug]['stats']['communes_total']++;
        $regions[$rSlug]['stats']['population_totale'] += $commune['population'] ?? 0;
        $regions[$rSlug]['stats']['artisans_chauffage'] += $nbArtisans;
        $deptStats['artisans_chauffage'] += $nbArtisans;

        if ($nbArtisans > 0) {
            $regions[$rSlug]['stats']['communes_avec_artisans']++;
            $deptStats['communes_avec_artisans']++;
        }

        $zone = $commune['aides_etat']['zone_climatique'] ?? null;
        if ($zone) {
            $regions[$rSlug]['zones_climatiques'][$zone] = ($regions[$rSlug]['zones_climatiques'][$zone] ?? 0) + 1;
        }
    }

    $regions[$rSlug]['departements'][] = $deptStats;
}

// Déterminer la zone climatique dominante par région
foreach ($regions as $rSlug => &$rData) {
    if (!empty($rData['zones_climatiques'])) {
        arsort($rData['zones_climatiques']);
        $rData['zone_climatique_dominante'] = array_key_first($rData['zones_climatiques']);
    } else {
        $rData['zone_climatique_dominante'] = null;
    }
    // Trier les départements par code
    usort($rData['departements'], fn($a, $b) => strcmp($a['code'], $b['code']));
}
unset($rData);

// Écrire un fichier par région
$count = 0;
foreach ($regions as $rSlug => $rData) {
    $path = REGIONS_DIR . '/' . $rSlug . '.json';
    file_put_contents($path, json_encode($rData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    $count++;
    echo "  ✓ {$rData['region']['nom']} ({$rData['stats']['communes_total']} communes, {$rData['stats']['artisans_chauffage']} artisans)\n";
}

echo "\n{$count} fichiers générés dans output/regions/\n";

