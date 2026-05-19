<?php
require_once __DIR__ . '/config.php';

// ─── Lecture des données JSON ────────────────────────────────────────────────

function getGeoData(string $dept_code): array {
    static $cache = [];
    if (isset($cache[$dept_code])) return $cache[$dept_code];
    $file = DATA_DIR . '/' . strtoupper($dept_code) . '.json';
    if (!file_exists($file)) return [];
    $cache[$dept_code] = json_decode(file_get_contents($file), true) ?? [];
    return $cache[$dept_code];
}

function getCommuneBySlug(string $dept_code, string $slug): ?array {
    $data = getGeoData($dept_code);
    if (empty($data['communes'])) return null;
    foreach ($data['communes'] as $commune) {
        if ($commune['slug'] === $slug) return $commune;
    }
    return null;
}

function getArtisansByVille(string $dept_code, string $ville_slug): array {
    static $cache = [];
    $key = $dept_code . ':' . $ville_slug;
    if (isset($cache[$key])) return $cache[$key];
    $file = DATA_DIR . '/' . NICHE_DIR . '/' . strtoupper($dept_code) . '.json';
    if (!file_exists($file)) return ($cache[$key] = []);
    $data = json_decode(file_get_contents($file), true) ?? [];
    $cache[$key] = $data[$ville_slug]['artisans'] ?? [];
    return $cache[$key];
}

function getRegionData(string $region_slug): array {
    static $cache = [];
    if (isset($cache[$region_slug])) return $cache[$region_slug];
    $file = DATA_DIR . '/regions/' . $region_slug . '.json';
    if (!file_exists($file)) return [];
    $cache[$region_slug] = json_decode(file_get_contents($file), true) ?? [];
    return $cache[$region_slug];
}

// ─── Mapping départements ────────────────────────────────────────────────────
// Retourne [code => [slug, nom, region_slug]] pour les 109 depts

function getDeptMapping(): array {
    static $map = null;
    if ($map !== null) return $map;
    $map = [
        '01' => ['slug' => 'ain',                    'nom' => 'Ain',                    'region_slug' => 'auvergne-rhone-alpes'],
        '02' => ['slug' => 'aisne',                  'nom' => 'Aisne',                  'region_slug' => 'hauts-de-france'],
        '03' => ['slug' => 'allier',                 'nom' => 'Allier',                 'region_slug' => 'auvergne-rhone-alpes'],
        '04' => ['slug' => 'alpes-de-haute-provence','nom' => 'Alpes-de-Haute-Provence','region_slug' => 'provence-alpes-cote-d-azur'],
        '05' => ['slug' => 'hautes-alpes',           'nom' => 'Hautes-Alpes',           'region_slug' => 'provence-alpes-cote-d-azur'],
        '06' => ['slug' => 'alpes-maritimes',        'nom' => 'Alpes-Maritimes',        'region_slug' => 'provence-alpes-cote-d-azur'],
        '07' => ['slug' => 'ardeche',                'nom' => 'Ardèche',                'region_slug' => 'auvergne-rhone-alpes'],
        '08' => ['slug' => 'ardennes',               'nom' => 'Ardennes',               'region_slug' => 'grand-est'],
        '09' => ['slug' => 'ariege',                 'nom' => 'Ariège',                 'region_slug' => 'occitanie'],
        '10' => ['slug' => 'aube',                   'nom' => 'Aube',                   'region_slug' => 'grand-est'],
        '11' => ['slug' => 'aude',                   'nom' => 'Aude',                   'region_slug' => 'occitanie'],
        '12' => ['slug' => 'aveyron',                'nom' => 'Aveyron',                'region_slug' => 'occitanie'],
        '13' => ['slug' => 'bouches-du-rhone',       'nom' => 'Bouches-du-Rhône',       'region_slug' => 'provence-alpes-cote-d-azur'],
        '14' => ['slug' => 'calvados',               'nom' => 'Calvados',               'region_slug' => 'normandie'],
        '15' => ['slug' => 'cantal',                 'nom' => 'Cantal',                 'region_slug' => 'auvergne-rhone-alpes'],
        '16' => ['slug' => 'charente',               'nom' => 'Charente',               'region_slug' => 'nouvelle-aquitaine'],
        '17' => ['slug' => 'charente-maritime',      'nom' => 'Charente-Maritime',      'region_slug' => 'nouvelle-aquitaine'],
        '18' => ['slug' => 'cher',                   'nom' => 'Cher',                   'region_slug' => 'centre-val-de-loire'],
        '19' => ['slug' => 'correze',                'nom' => 'Corrèze',                'region_slug' => 'nouvelle-aquitaine'],
        '2A' => ['slug' => 'corse-du-sud',           'nom' => 'Corse-du-Sud',           'region_slug' => 'corse'],
        '2B' => ['slug' => 'haute-corse',            'nom' => 'Haute-Corse',            'region_slug' => 'corse'],
        '21' => ['slug' => 'cote-d-or',              'nom' => "Côte-d'Or",              'region_slug' => 'bourgogne-franche-comte'],
        '22' => ['slug' => 'cotes-d-armor',          'nom' => "Côtes-d'Armor",          'region_slug' => 'bretagne'],
        '23' => ['slug' => 'creuse',                 'nom' => 'Creuse',                 'region_slug' => 'nouvelle-aquitaine'],
        '24' => ['slug' => 'dordogne',               'nom' => 'Dordogne',               'region_slug' => 'nouvelle-aquitaine'],
        '25' => ['slug' => 'doubs',                  'nom' => 'Doubs',                  'region_slug' => 'bourgogne-franche-comte'],
        '26' => ['slug' => 'drome',                  'nom' => 'Drôme',                  'region_slug' => 'auvergne-rhone-alpes'],
        '27' => ['slug' => 'eure',                   'nom' => 'Eure',                   'region_slug' => 'normandie'],
        '28' => ['slug' => 'eure-et-loir',           'nom' => 'Eure-et-Loir',           'region_slug' => 'centre-val-de-loire'],
        '29' => ['slug' => 'finistere',              'nom' => 'Finistère',              'region_slug' => 'bretagne'],
        '30' => ['slug' => 'gard',                   'nom' => 'Gard',                   'region_slug' => 'occitanie'],
        '31' => ['slug' => 'haute-garonne',          'nom' => 'Haute-Garonne',          'region_slug' => 'occitanie'],
        '32' => ['slug' => 'gers',                   'nom' => 'Gers',                   'region_slug' => 'occitanie'],
        '33' => ['slug' => 'gironde',                'nom' => 'Gironde',                'region_slug' => 'nouvelle-aquitaine'],
        '34' => ['slug' => 'herault',                'nom' => 'Hérault',                'region_slug' => 'occitanie'],
        '35' => ['slug' => 'ille-et-vilaine',        'nom' => 'Ille-et-Vilaine',        'region_slug' => 'bretagne'],
        '36' => ['slug' => 'indre',                  'nom' => 'Indre',                  'region_slug' => 'centre-val-de-loire'],
        '37' => ['slug' => 'indre-et-loire',         'nom' => 'Indre-et-Loire',         'region_slug' => 'centre-val-de-loire'],
        '38' => ['slug' => 'isere',                  'nom' => 'Isère',                  'region_slug' => 'auvergne-rhone-alpes'],
        '39' => ['slug' => 'jura',                   'nom' => 'Jura',                   'region_slug' => 'bourgogne-franche-comte'],
        '40' => ['slug' => 'landes',                 'nom' => 'Landes',                 'region_slug' => 'nouvelle-aquitaine'],
        '41' => ['slug' => 'loir-et-cher',           'nom' => 'Loir-et-Cher',           'region_slug' => 'centre-val-de-loire'],
        '42' => ['slug' => 'loire',                  'nom' => 'Loire',                  'region_slug' => 'auvergne-rhone-alpes'],
        '43' => ['slug' => 'haute-loire',            'nom' => 'Haute-Loire',            'region_slug' => 'auvergne-rhone-alpes'],
        '44' => ['slug' => 'loire-atlantique',       'nom' => 'Loire-Atlantique',       'region_slug' => 'pays-de-la-loire'],
        '45' => ['slug' => 'loiret',                 'nom' => 'Loiret',                 'region_slug' => 'centre-val-de-loire'],
        '46' => ['slug' => 'lot',                    'nom' => 'Lot',                    'region_slug' => 'occitanie'],
        '47' => ['slug' => 'lot-et-garonne',         'nom' => 'Lot-et-Garonne',         'region_slug' => 'nouvelle-aquitaine'],
        '48' => ['slug' => 'lozere',                 'nom' => 'Lozère',                 'region_slug' => 'occitanie'],
        '49' => ['slug' => 'maine-et-loire',         'nom' => 'Maine-et-Loire',         'region_slug' => 'pays-de-la-loire'],
        '50' => ['slug' => 'manche',                 'nom' => 'Manche',                 'region_slug' => 'normandie'],
        '51' => ['slug' => 'marne',                  'nom' => 'Marne',                  'region_slug' => 'grand-est'],
        '52' => ['slug' => 'haute-marne',            'nom' => 'Haute-Marne',            'region_slug' => 'grand-est'],
        '53' => ['slug' => 'mayenne',                'nom' => 'Mayenne',                'region_slug' => 'pays-de-la-loire'],
        '54' => ['slug' => 'meurthe-et-moselle',     'nom' => 'Meurthe-et-Moselle',     'region_slug' => 'grand-est'],
        '55' => ['slug' => 'meuse',                  'nom' => 'Meuse',                  'region_slug' => 'grand-est'],
        '56' => ['slug' => 'morbihan',               'nom' => 'Morbihan',               'region_slug' => 'bretagne'],
        '57' => ['slug' => 'moselle',                'nom' => 'Moselle',                'region_slug' => 'grand-est'],
        '58' => ['slug' => 'nievre',                 'nom' => 'Nièvre',                 'region_slug' => 'bourgogne-franche-comte'],
        '59' => ['slug' => 'nord',                   'nom' => 'Nord',                   'region_slug' => 'hauts-de-france'],
        '60' => ['slug' => 'oise',                   'nom' => 'Oise',                   'region_slug' => 'hauts-de-france'],
        '61' => ['slug' => 'orne',                   'nom' => 'Orne',                   'region_slug' => 'normandie'],
        '62' => ['slug' => 'pas-de-calais',          'nom' => 'Pas-de-Calais',          'region_slug' => 'hauts-de-france'],
        '63' => ['slug' => 'puy-de-dome',            'nom' => 'Puy-de-Dôme',            'region_slug' => 'auvergne-rhone-alpes'],
        '64' => ['slug' => 'pyrenees-atlantiques',   'nom' => 'Pyrénées-Atlantiques',   'region_slug' => 'nouvelle-aquitaine'],
        '65' => ['slug' => 'hautes-pyrenees',        'nom' => 'Hautes-Pyrénées',        'region_slug' => 'occitanie'],
        '66' => ['slug' => 'pyrenees-orientales',    'nom' => 'Pyrénées-Orientales',    'region_slug' => 'occitanie'],
        '67' => ['slug' => 'bas-rhin',               'nom' => 'Bas-Rhin',               'region_slug' => 'grand-est'],
        '68' => ['slug' => 'haut-rhin',              'nom' => 'Haut-Rhin',              'region_slug' => 'grand-est'],
        '69' => ['slug' => 'rhone',                  'nom' => 'Rhône',                  'region_slug' => 'auvergne-rhone-alpes'],
        '70' => ['slug' => 'haute-saone',            'nom' => 'Haute-Saône',            'region_slug' => 'bourgogne-franche-comte'],
        '71' => ['slug' => 'saone-et-loire',         'nom' => 'Saône-et-Loire',         'region_slug' => 'bourgogne-franche-comte'],
        '72' => ['slug' => 'sarthe',                 'nom' => 'Sarthe',                 'region_slug' => 'pays-de-la-loire'],
        '73' => ['slug' => 'savoie',                 'nom' => 'Savoie',                 'region_slug' => 'auvergne-rhone-alpes'],
        '74' => ['slug' => 'haute-savoie',           'nom' => 'Haute-Savoie',           'region_slug' => 'auvergne-rhone-alpes'],
        '75' => ['slug' => 'paris',                  'nom' => 'Paris',                  'region_slug' => 'ile-de-france'],
        '76' => ['slug' => 'seine-maritime',         'nom' => 'Seine-Maritime',         'region_slug' => 'normandie'],
        '77' => ['slug' => 'seine-et-marne',         'nom' => 'Seine-et-Marne',         'region_slug' => 'ile-de-france'],
        '78' => ['slug' => 'yvelines',               'nom' => 'Yvelines',               'region_slug' => 'ile-de-france'],
        '79' => ['slug' => 'deux-sevres',            'nom' => 'Deux-Sèvres',            'region_slug' => 'nouvelle-aquitaine'],
        '80' => ['slug' => 'somme',                  'nom' => 'Somme',                  'region_slug' => 'hauts-de-france'],
        '81' => ['slug' => 'tarn',                   'nom' => 'Tarn',                   'region_slug' => 'occitanie'],
        '82' => ['slug' => 'tarn-et-garonne',        'nom' => 'Tarn-et-Garonne',        'region_slug' => 'occitanie'],
        '83' => ['slug' => 'var',                    'nom' => 'Var',                    'region_slug' => 'provence-alpes-cote-d-azur'],
        '84' => ['slug' => 'vaucluse',               'nom' => 'Vaucluse',               'region_slug' => 'provence-alpes-cote-d-azur'],
        '85' => ['slug' => 'vendee',                 'nom' => 'Vendée',                 'region_slug' => 'pays-de-la-loire'],
        '86' => ['slug' => 'vienne',                 'nom' => 'Vienne',                 'region_slug' => 'nouvelle-aquitaine'],
        '87' => ['slug' => 'haute-vienne',           'nom' => 'Haute-Vienne',           'region_slug' => 'nouvelle-aquitaine'],
        '88' => ['slug' => 'vosges',                 'nom' => 'Vosges',                 'region_slug' => 'grand-est'],
        '89' => ['slug' => 'yonne',                  'nom' => 'Yonne',                  'region_slug' => 'bourgogne-franche-comte'],
        '90' => ['slug' => 'territoire-de-belfort', 'nom' => 'Territoire de Belfort',  'region_slug' => 'bourgogne-franche-comte'],
        '91' => ['slug' => 'essonne',                'nom' => 'Essonne',                'region_slug' => 'ile-de-france'],
        '92' => ['slug' => 'hauts-de-seine',         'nom' => 'Hauts-de-Seine',         'region_slug' => 'ile-de-france'],
        '93' => ['slug' => 'seine-saint-denis',      'nom' => 'Seine-Saint-Denis',      'region_slug' => 'ile-de-france'],
        '94' => ['slug' => 'val-de-marne',           'nom' => 'Val-de-Marne',           'region_slug' => 'ile-de-france'],
        '95' => ['slug' => 'val-d-oise',             'nom' => "Val-d'Oise",             'region_slug' => 'ile-de-france'],
        '971' => ['slug' => 'guadeloupe',            'nom' => 'Guadeloupe',             'region_slug' => 'guadeloupe'],
        '972' => ['slug' => 'martinique',            'nom' => 'Martinique',             'region_slug' => 'martinique'],
        '973' => ['slug' => 'guyane',                'nom' => 'Guyane',                 'region_slug' => 'guyane'],
        '974' => ['slug' => 'la-reunion',            'nom' => 'La Réunion',             'region_slug' => 'la-reunion'],
        '976' => ['slug' => 'mayotte',               'nom' => 'Mayotte',                'region_slug' => 'mayotte'],
    ];
    return $map;
}

function getDeptCodeBySlug(string $slug): ?string {
    foreach (getDeptMapping() as $code => $info) {
        if ($info['slug'] === $slug) return (string) $code;
    }
    return null;
}

// ─── Compteurs ───────────────────────────────────────────────────────────────

function getCompteurArtisans(array $commune): int {
    return (int) ($commune['artisans'][NICHE_KEY] ?? 0);
}

function getCompteurLabel(int $n): string {
    if ($n === 0) return 'Aucun ' . METIER;
    if ($n === 1) return '1 ' . METIER;
    return $n . ' ' . METIER_PLURIEL;
}

// ─── Construction des URLs ────────────────────────────────────────────────────

function urlRegion(string $region_slug): string {
    return SITE_URL . '/' . $region_slug . '/';
}

function urlDepartement(string $region_slug, string $dept_slug): string {
    return SITE_URL . '/' . $region_slug . '/' . $dept_slug . '/';
}

function urlVille(string $region_slug, string $dept_slug, string $ville_slug, string $cp): string {
    return SITE_URL . '/' . $region_slug . '/' . $dept_slug . '/' . $ville_slug . '-' . $cp . '/';
}

function urlModele(string $region_slug, string $dept_slug, string $ville_slug, string $cp, string $modele_slug): string {
    return SITE_URL . '/' . $region_slug . '/' . $dept_slug . '/' . $ville_slug . '-' . $cp . '/' . $modele_slug . '/';
}

function urlArtisans(string $region_slug, string $dept_slug, string $ville_slug, string $cp): string {
    return SITE_URL . '/' . $region_slug . '/' . $dept_slug . '/' . $ville_slug . '-' . $cp . '/artisans/';
}

function urlArtisan(string $region_slug, string $dept_slug, string $ville_slug, string $cp, string $artisan_slug): string {
    return SITE_URL . '/' . $region_slug . '/' . $dept_slug . '/' . $ville_slug . '-' . $cp . '/artisans/' . $artisan_slug . '/';
}

function urlModeleRegion(string $region_slug, string $modele_slug): string {
    return SITE_URL . '/' . $region_slug . '/' . $modele_slug . '/';
}

function urlModeleDepartement(string $region_slug, string $dept_slug, string $modele_slug): string {
    return SITE_URL . '/' . $region_slug . '/' . $dept_slug . '/' . $modele_slug . '/';
}

// ─── Articles grammaticaux français ──────────────────────────────────────────

function articleVille(string $nom): string {
    // Voyelles pour l'élision
    $voyelles = ['a','e','é','è','ê','ë','i','î','ï','o','ô','u','û','ü','y'];

    // Préfixes avec article défini
    if (preg_match('/^Les? /i', $nom, $m)) {
        $mot = substr($nom, strlen($m[0]));
        // "Les X" → "aux X", "Le X" → "au X"
        if (strtolower($m[0]) === 'les ') return 'aux ' . $mot;
        return 'au ' . $mot;
    }
    if (preg_match("/^L'/i", $nom)) {
        return 'à l\'' . substr($nom, 2);
    }

    // Villes commençant par une voyelle → "à L'..."
    $firstChar = mb_strtolower(mb_substr($nom, 0, 1));
    if (in_array($firstChar, $voyelles)) {
        return "à " . $nom;
    }

    return 'à ' . $nom;
}

function articleDepartement(string $dept_code): string {
    // Prépositions pour chaque département (dans le/dans la/dans les/en)
    static $table = [
        '01' => 'dans l\'Ain',
        '02' => 'dans l\'Aisne',
        '03' => 'dans l\'Allier',
        '04' => 'dans les Alpes-de-Haute-Provence',
        '05' => 'dans les Hautes-Alpes',
        '06' => 'dans les Alpes-Maritimes',
        '07' => 'en Ardèche',
        '08' => 'dans les Ardennes',
        '09' => 'en Ariège',
        '10' => 'dans l\'Aube',
        '11' => 'dans l\'Aude',
        '12' => 'en Aveyron',
        '13' => 'dans les Bouches-du-Rhône',
        '14' => 'dans le Calvados',
        '15' => 'dans le Cantal',
        '16' => 'en Charente',
        '17' => 'en Charente-Maritime',
        '18' => 'dans le Cher',
        '19' => 'en Corrèze',
        '2A' => 'en Corse-du-Sud',
        '2B' => 'en Haute-Corse',
        '21' => 'en Côte-d\'Or',
        '22' => 'dans les Côtes-d\'Armor',
        '23' => 'en Creuse',
        '24' => 'en Dordogne',
        '25' => 'dans le Doubs',
        '26' => 'dans la Drôme',
        '27' => 'dans l\'Eure',
        '28' => 'en Eure-et-Loir',
        '29' => 'dans le Finistère',
        '30' => 'dans le Gard',
        '31' => 'en Haute-Garonne',
        '32' => 'dans le Gers',
        '33' => 'en Gironde',
        '34' => 'dans l\'Hérault',
        '35' => 'en Ille-et-Vilaine',
        '36' => 'dans l\'Indre',
        '37' => 'en Indre-et-Loire',
        '38' => 'en Isère',
        '39' => 'dans le Jura',
        '40' => 'dans les Landes',
        '41' => 'dans le Loir-et-Cher',
        '42' => 'dans la Loire',
        '43' => 'en Haute-Loire',
        '44' => 'en Loire-Atlantique',
        '45' => 'dans le Loiret',
        '46' => 'dans le Lot',
        '47' => 'en Lot-et-Garonne',
        '48' => 'en Lozère',
        '49' => 'en Maine-et-Loire',
        '50' => 'dans la Manche',
        '51' => 'dans la Marne',
        '52' => 'en Haute-Marne',
        '53' => 'en Mayenne',
        '54' => 'en Meurthe-et-Moselle',
        '55' => 'en Meuse',
        '56' => 'dans le Morbihan',
        '57' => 'en Moselle',
        '58' => 'dans la Nièvre',
        '59' => 'dans le Nord',
        '60' => 'dans l\'Oise',
        '61' => 'dans l\'Orne',
        '62' => 'dans le Pas-de-Calais',
        '63' => 'dans le Puy-de-Dôme',
        '64' => 'dans les Pyrénées-Atlantiques',
        '65' => 'dans les Hautes-Pyrénées',
        '66' => 'dans les Pyrénées-Orientales',
        '67' => 'dans le Bas-Rhin',
        '68' => 'dans le Haut-Rhin',
        '69' => 'dans le Rhône',
        '70' => 'en Haute-Saône',
        '71' => 'en Saône-et-Loire',
        '72' => 'dans la Sarthe',
        '73' => 'en Savoie',
        '74' => 'en Haute-Savoie',
        '75' => 'à Paris',
        '76' => 'en Seine-Maritime',
        '77' => 'en Seine-et-Marne',
        '78' => 'dans les Yvelines',
        '79' => 'dans les Deux-Sèvres',
        '80' => 'dans la Somme',
        '81' => 'dans le Tarn',
        '82' => 'en Tarn-et-Garonne',
        '83' => 'dans le Var',
        '84' => 'dans le Vaucluse',
        '85' => 'en Vendée',
        '86' => 'dans la Vienne',
        '87' => 'en Haute-Vienne',
        '88' => 'dans les Vosges',
        '89' => 'dans l\'Yonne',
        '90' => 'dans le Territoire de Belfort',
        '91' => 'en Essonne',
        '92' => 'dans les Hauts-de-Seine',
        '93' => 'en Seine-Saint-Denis',
        '94' => 'dans le Val-de-Marne',
        '95' => 'dans le Val-d\'Oise',
        '971' => 'en Guadeloupe',
        '972' => 'en Martinique',
        '973' => 'en Guyane',
        '974' => 'à La Réunion',
        '976' => 'à Mayotte',
    ];
    return $table[$dept_code] ?? 'dans le département ' . $dept_code;
}

function articleRegion(string $region_slug): string {
    static $table = [
        'auvergne-rhone-alpes'      => 'en Auvergne-Rhône-Alpes',
        'bourgogne-franche-comte'   => 'en Bourgogne-Franche-Comté',
        'bretagne'                  => 'en Bretagne',
        'centre-val-de-loire'       => 'en Centre-Val de Loire',
        'corse'                     => 'en Corse',
        'grand-est'                 => 'dans le Grand Est',
        'guadeloupe'                => 'en Guadeloupe',
        'guyane'                    => 'en Guyane',
        'hauts-de-france'           => 'dans les Hauts-de-France',
        'ile-de-france'             => 'en Île-de-France',
        'la-reunion'                => 'à La Réunion',
        'martinique'                => 'en Martinique',
        'mayotte'                   => 'à Mayotte',
        'normandie'                 => 'en Normandie',
        'nouvelle-aquitaine'        => 'en Nouvelle-Aquitaine',
        'occitanie'                 => 'en Occitanie',
        'pays-de-la-loire'          => 'en Pays de la Loire',
        'provence-alpes-cote-d-azur'=> 'en Provence-Alpes-Côte d\'Azur',
    ];
    return $table[$region_slug] ?? 'en ' . str_replace('-', ' ', $region_slug);
}

// ─── Titre région/dept (court, pour affichage) ───────────────────────────────

function nomRegion(string $region_slug): string {
    static $table = [
        'auvergne-rhone-alpes'      => 'Auvergne-Rhône-Alpes',
        'bourgogne-franche-comte'   => 'Bourgogne-Franche-Comté',
        'bretagne'                  => 'Bretagne',
        'centre-val-de-loire'       => 'Centre-Val de Loire',
        'corse'                     => 'Corse',
        'grand-est'                 => 'Grand Est',
        'guadeloupe'                => 'Guadeloupe',
        'guyane'                    => 'Guyane',
        'hauts-de-france'           => 'Hauts-de-France',
        'ile-de-france'             => 'Île-de-France',
        'la-reunion'                => 'La Réunion',
        'martinique'                => 'Martinique',
        'mayotte'                   => 'Mayotte',
        'normandie'                 => 'Normandie',
        'nouvelle-aquitaine'        => 'Nouvelle-Aquitaine',
        'occitanie'                 => 'Occitanie',
        'pays-de-la-loire'          => 'Pays de la Loire',
        'provence-alpes-cote-d-azur'=> 'Provence-Alpes-Côte d\'Azur',
    ];
    return $table[$region_slug] ?? ucfirst(str_replace('-', ' ', $region_slug));
}

// ─── SEO ──────────────────────────────────────────────────────────────────────

function seoTitle(string $type, array $data): string {
    $year = SITE_YEAR;
    switch ($type) {
        case 'home':
            return METIER_CAP . ' qualifié en France — Devis gratuit ' . $year;

        case 'region':
            return METIER_CAP . ' ' . articleRegion($data['slug']) . ' — Devis gratuit ' . $year;

        case 'departement':
            $art = articleDepartement($data['code']);
            return METIER_CAP . ' ' . $art . ' (' . $data['code'] . ') — Devis ' . $year;

        case 'ville':
            $cp = $data['code_postal'] ?? '';
            $nb = getCompteurArtisans($data);
            return METIER_CAP . ' ' . articleVille($data['nom']) . ' (' . $cp . ') — '
                . getCompteurLabel($nb) . ' — Devis';

        case 'modele':
            $cp = $data['commune']['code_postal'] ?? '';
            return $data['modele']['nom'] . ' ' . articleVille($data['commune']['nom'])
                . ' — Devis gratuit';

        case 'artisan':
            return $data['nom'] . ' — ' . METIER_CAP . ' '
                . articleVille($data['ville']['nom']) . ' — Avis, Contact, Devis';

        case 'artisans':
            $nb = count($data['artisans']);
            return 'Les ' . $nb . ' ' . METIER_PLURIEL . ' '
                . articleVille($data['commune']['nom']) . ' — Annuaire';

        case 'modele-region':
            return $data['modele']['nom'] . ' en ' . nomRegion($data['region_slug'])
                . ' — Devis gratuit ' . $year;

        case 'modele-departement':
            $art = articleDepartement($data['dept_code']);
            return $data['modele']['nom'] . ' ' . $art . ' — Devis gratuit ' . $year;

        default:
            return SITE_NAME . ' — Devis gratuit ' . $year;
    }
}

function seoDescription(string $type, array $data): string {
    switch ($type) {
        case 'home':
            return 'Trouvez votre ' . METIER . ' parmi 30 000 chauffagistes référencés en France. Chaudière, PAC, plancher chauffant. Devis gratuit en 2 minutes.';

        case 'region':
            $nom = nomRegion($data['slug']);
            $nb = $data['stats']['artisans_chauffage'] ?? 0;
            $c  = $data['stats']['communes_total'] ?? 0;
            return "Trouvez un chauffagiste " . articleRegion($data['slug']) . ". {$nb} professionnels dans {$c} communes. Devis gratuit.";

        case 'departement':
            $art  = articleDepartement($data['code']);
            $nb   = $data['artisans_chauffage'] ?? 0;
            $zone = $data['zone'] ?? '';
            return "{$nb} " . METIER_PLURIEL . " {$art}. MaPrimeRénov', prime CEE, TVA 5,5%. Devis gratuit en 48h.";

        case 'ville':
            $cp  = $data['code_postal'] ?? '';
            $nb  = getCompteurArtisans($data);
            $qpv = ($data['aides_etat']['qpv'] ?? false) ? ', QPV' : '';
            return getCompteurLabel($nb) . ' ' . articleVille($data['nom'])
                . " ({$cp}){$qpv}. Chaudière, PAC, plancher chauffant. MaPrimeRénov', prime CEE. Devis gratuit.";

        case 'modele':
            $cp  = $data['commune']['code_postal'] ?? '';
            $nb  = getCompteurArtisans($data['commune']);
            $mod = $data['modele']['nom'];
            return "{$mod} " . articleVille($data['commune']['nom'])
                . " : {$nb} " . METIER_PLURIEL . ", TVA 5,5%, prime CEE BAR-TH-106, certifiés RGE. Devis gratuit.";

        case 'artisan':
            $note = formatNote($data['note'] ?? 0);
            $avis = $data['avis'] ?? 0;
            return $data['nom'] . ' — ' . METIER_CAP . ' '
                . articleVille($data['ville']['nom']) . ". Note {$note} — {$avis} avis. Devis gratuit en ligne.";

        case 'modele-region':
            $nb  = $data['nb_artisans'];
            $mod = $data['modele']['nom'];
            $nom = nomRegion($data['region_slug']);
            return "{$mod} en {$nom} : {$nb} " . METIER_PLURIEL . " certifiés, garantie décennale. Devis gratuit.";

        case 'modele-departement':
            $art  = articleDepartement($data['dept_code']);
            $nb   = $data['nb_artisans'];
            $mod  = $data['modele']['nom'];
            $zone = $data['zone'];
            return "{$mod} {$art} (zone {$zone}) : {$nb} " . METIER_PLURIEL . " certifiés, garantie décennale. Devis gratuit.";

        default:
            return SITE_NAME . ' — Annuaire des ' . METIER_PLURIEL . ' en France.';
    }
}

function canonical(string $url): string {
    return '<link rel="canonical" href="' . htmlspecialchars($url, ENT_QUOTES) . '">';
}

// ─── JSON-LD ──────────────────────────────────────────────────────────────────

function jsonLdOrganization(): string {
    $data = [
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        'name'     => SITE_NAME,
        'url'      => SITE_URL,
        'logo'     => SITE_URL . '/assets/img/logo.png',
        'contactPoint' => [
            '@type'       => 'ContactPoint',
            'contactType' => 'customer service',
            'email'       => 'contact@annuaire-chauffagiste-france.fr',
        ],
    ];
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
}

function jsonLdWebSite(): string {
    $data = [
        '@context' => 'https://schema.org',
        '@type'    => 'WebSite',
        'name'     => SITE_NAME,
        'url'      => SITE_URL,
        'potentialAction' => [
            '@type'       => 'SearchAction',
            'target'      => ['@type' => 'EntryPoint', 'urlTemplate' => SITE_URL . '/recherche?q={search_term_string}'],
            'query-input' => 'required name=search_term_string',
        ],
    ];
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
}

function jsonLdBreadcrumbs(array $trail): string {
    $items = [];
    foreach ($trail as $i => $crumb) {
        $items[] = [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $crumb['name'],
            'item'     => $crumb['url'],
        ];
    }
    $data = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $items,
    ];
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
}

function jsonLdFAQ(array $questions): string {
    $entities = [];
    foreach ($questions as $qa) {
        $entities[] = [
            '@type'          => 'Question',
            'name'           => $qa['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $qa['r']],
        ];
    }
    $data = [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => $entities,
    ];
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
}

function jsonLdLocalBusiness(array $artisan, array $commune): string {
    $data = [
        '@context' => 'https://schema.org',
        '@type'    => 'LocalBusiness',
        'name'     => $artisan['nom'],
        'address'  => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => $artisan['adresse'] ?? '',
            'addressLocality' => $commune['nom'],
            'postalCode'      => $commune['code_postal'],
            'addressCountry'  => 'FR',
        ],
    ];
    if (!empty($artisan['telephone'])) {
        $data['telephone'] = $artisan['telephone'];
    }
    if (!empty($artisan['site_web'])) {
        $data['url'] = $artisan['site_web'];
    }
    if (!empty($artisan['note']) && $artisan['note'] > 0) {
        $data['aggregateRating'] = [
            '@type'       => 'AggregateRating',
            'ratingValue' => $artisan['note'],
            'reviewCount' => $artisan['avis'] ?? 0,
            'bestRating'  => 5,
            'worstRating' => 1,
        ];
    }
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
}

// ─── Aides et rénovation ──────────────────────────────────────────────────────

function getAidesForCommune(array $commune): array {
    $aides   = AIDES_NATIONALES;
    $etat    = $commune['aides_etat'] ?? [];
    $extras  = [];

    if (!empty($etat['qpv'])) {
        $extras['qpv'] = AIDES_QPV;
    }
    if (!empty($etat['action_coeur_de_ville'])) {
        $extras['acv'] = AIDES_ACV;
    }
    if (!empty($etat['petites_villes_de_demain'])) {
        $extras['pvd'] = AIDES_PVD;
    }
    return ['nationales' => $aides, 'locales' => $extras, 'zone' => $etat['zone_climatique'] ?? 'H2'];
}

function getPotentielRenovation(array $commune): array {
    $log = $commune['logement'] ?? [];
    $tot = $log['logements_total'] ?? 0;
    $av90 = $log['logements_avant_1990'] ?? 0;
    $pct  = $tot > 0 ? round($av90 / $tot * 100) : 0;

    $revenu = $commune['revenus']['revenu_median'] ?? 0;
    $segment = $revenu >= 30000 ? 'aisé' : ($revenu >= 20000 ? 'intermédiaire' : 'modeste');

    return [
        'logements_total'      => $tot,
        'logements_avant_1990' => $av90,
        'pct_avant_1990'       => $pct,
        'maisons'              => $log['maisons'] ?? 0,
        'appartements'         => $log['appartements'] ?? 0,
        'revenu_median'        => $revenu,
        'segment'              => $segment,
        'niveau'               => $pct >= 70 ? 'fort' : ($pct >= 40 ? 'moyen' : 'faible'),
    ];
}

function getZoneLabel(string $zone): string {
    return ZONES_CLIMATIQUES[$zone]['label'] ?? 'Zone ' . $zone;
}

// ─── Utilitaires ─────────────────────────────────────────────────────────────

function getVudCatForModele(string $slug): int {
    foreach (MODELES as $m) {
        if ($m['slug'] === $slug) return (int) $m['vud_cat'];
    }
    return VUD_CATEGORIE_ID;
}

function formatPhone(string $tel): string {
    $clean = preg_replace('/\D/', '', $tel);
    if (strlen($clean) === 10) {
        return implode(' ', str_split($clean, 2));
    }
    return $tel;
}

function formatNote(float $note): string {
    return number_format($note, 1, ',', '') . '/5';
}

function truncate(string $str, int $len): string {
    if (mb_strlen($str) <= $len) return $str;
    return mb_substr($str, 0, $len - 1) . '…';
}

function slugify(string $str): string {
    $str = mb_strtolower(trim($str));
    $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
    $str = preg_replace('/[^a-z0-9]+/', '-', $str);
    return trim($str, '-');
}

