<?php
/**
 * Generation SEO -- Chauffage
 * Toutes les communes (avec ET sans artisan reference)
 * Sortie : JSON {text, meta} par commune
 *
 * Usage CLI :
 *   php generate-seo-villes.php              -> tous les departements
 *   php generate-seo-villes.php 75           -> departement 75 uniquement
 *   php generate-seo-villes.php 75 force     -> regenere meme si deja present
 */

declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

const DEEPSEEK_API_KEY = 'sk-d155937287894871a81e3e31d1c65fee';
const DEEPSEEK_MODEL   = 'deepseek-chat';
const SEO_OUTPUT_DIR   = __DIR__ . '/output/seo';
const DELAY_MS         = 150;
const MAX_RETRIES      = 3;

$filterDept = isset($argv[1]) ? strtoupper($argv[1]) : null;
$forceRegen = isset($argv[2]) && $argv[2] === 'force';

$deptMap = getDeptMapping();
$stats   = ['done' => 0, 'skipped' => 0, 'errors' => 0, 'tokens_in' => 0, 'tokens_out' => 0];

echo "=== SEO Chauffage — toutes communes ===\n";
echo 'Filtre dept : ' . ($filterDept ?? 'tous') . ' | Force : ' . ($forceRegen ? 'oui' : 'non') . "\n\n";

foreach ($deptMap as $deptCode => $deptInfo) {
    $deptCode = (string)$deptCode;
    if ($filterDept && $filterDept !== strtoupper($deptCode)) continue;

    $geoFile = DATA_DIR . '/' . strtoupper($deptCode) . '.json';
    if (!file_exists($geoFile)) continue;

    $geo       = json_decode(file_get_contents($geoFile), true);
    $communes  = $geo['communes'] ?? [];
    $deptNom   = $deptInfo['nom'];
    $regionNom = nomRegion($deptInfo['region_slug']);
    $total     = count($communes);

    $outDir = SEO_OUTPUT_DIR . '/' . strtoupper($deptCode);
    if (!is_dir($outDir)) mkdir($outDir, 0755, true);

    echo "\n┌── DEPT {$deptCode} — {$deptNom} ({$total} communes) ──\n";

    $i = 0;
    foreach ($communes as $commune) {
        $i++;
        $outFile    = $outDir . '/' . $commune['slug'] . '.json';
        $nbArtisans = (int)($commune['artisans'][NICHE_KEY] ?? 0);

        if (!$forceRegen && file_exists($outFile)) {
            $stats['skipped']++;
            echo "│  [{$i}/{$total}] {$commune['nom']} — ignoré (déjà présent)\n";
            continue;
        }

        echo "│  [{$i}/{$total}] {$commune['nom']}... ";

        $prompt = $nbArtisans > 0
            ? buildPromptAvecArtisans($commune, $deptNom, $regionNom, $nbArtisans)
            : buildPromptSansArtisans($commune, $deptNom, $regionNom);

        $json = callWithRetry($prompt, $stats);

        if ($json === null) {
            echo "✗ ERREUR\n";
            $stats['errors']++;
            continue;
        }

        file_put_contents($outFile, $json);
        $stats['done']++;
        echo "✓  (total: {$stats['done']} | coût: \$" . number_format(estimateCost($stats), 3) . ")\n";

        usleep(DELAY_MS * 1000);
    }

    echo "└── {$deptCode} terminé\n";
}

echo "\n=== Résumé ===\n";
echo 'Générés  : ' . $stats['done'] . "\n";
echo 'Ignorés  : ' . $stats['skipped'] . "\n";
echo 'Erreurs  : ' . $stats['errors'] . "\n";
echo 'Coût estimé : $' . number_format(estimateCost($stats), 4) . " USD\n";

// ─── Prompts ──────────────────────────────────────────────────────────────────

function buildPromptAvecArtisans(array $c, string $dept, string $region, int $nb): string
{
    $ctx      = communeContext($c);
    $nom      = $c['nom'];
    $cp       = $c['code_postal'];
    $pop      = $ctx['population'];
    $zone     = $ctx['zone_label'];
    $aidesSpe = $ctx['aides_speciales'];

    return <<<PROMPT
Tu es redacteur SEO specialise en chauffage, plomberie et renovation energetique. Genere le contenu SEO pour la page d'annuaire des chauffagistes a {$nom} ({$cp}).

DONNEES LOCALES :
- Commune : {$nom}, {$dept} ({$region})
- Population : {$pop} hab.
- Chauffagistes references : {$nb}
- Contexte climatique : {$zone}
{$aidesSpe}

CONSIGNES TEXTE (280-340 mots) :
- Pas de nom de marque ni de chauffagiste specifique
- Angle editorial : confort thermique, economies d'energie, valorisation immobiliere, aides de l'Etat
- Mots-cles naturels : chauffagiste {$nom}, chaudiere {$nom}, pompe a chaleur {$nom}, installation chauffage {$nom}
- Aides a mentionner : MaPrimeRenov (jusqu'a 11 000 EUR), prime CEE BAR-TH-106 (chaudiere HPE), TVA 5,5% (equipements renouvelables), Eco-PTZ
- Services a mentionner : installation chaudiere gaz condensation, pompe a chaleur air-eau, chaudiere biomasse, plancher chauffant, entretien annuel chaudiere, depannage chauffage urgence, radiateurs, ballon eau chaude
- Mentionner la certification RGE obligatoire pour les aides de l'Etat
- Paragraphes courts (3-4 lignes), pas de titre H1/H2, texte brut

CONSIGNES META (130-155 caracteres) :
- Inclure : chauffagiste {$nom}, devis gratuit, un service cle (installation ou entretien)
- Formule comme une invitation a l'action

FORMAT DE REPONSE — JSON valide uniquement, sans markdown ni balise :
{{"text":"Texte editorial ici","meta":"Meta description ici"}}
PROMPT;
}

function buildPromptSansArtisans(array $c, string $dept, string $region): string
{
    $ctx      = communeContext($c);
    $proches  = villesProchesLabel($c);
    $nom      = $c['nom'];
    $cp       = $c['code_postal'];
    $pop      = $ctx['population'];
    $zone     = $ctx['zone_label'];
    $aidesSpe = $ctx['aides_speciales'];

    return <<<PROMPT
Tu es redacteur SEO specialise en chauffage, plomberie et renovation energetique. Genere le contenu SEO pour la page d'annuaire chauffagistes pres de {$nom} ({$cp}).

SITUATION : aucun chauffagiste n'est repertorie a {$nom}, mais des professionnels de {$proches} couvrent ce secteur.

DONNEES LOCALES :
- Commune : {$nom}, {$dept} ({$region})
- Population : {$pop} hab.
- Contexte climatique : {$zone}
{$aidesSpe}

CONSIGNES TEXTE (240-300 mots) :
- Mentionne que les chauffagistes de {$proches} interviennent a {$nom} et alentours
- Valorise les deplacements gratuits pour devis et les solutions de financement accessibles partout
- Mots-cles : chauffagiste pres de {$nom}, chaudiere {$nom}, installation chauffage {$nom}
- Aides : MaPrimeRenov, prime CEE BAR-TH-106, TVA 5,5%, Eco-PTZ
- Mentionner certification RGE obligatoire pour beneficier des aides de l'Etat
- Paragraphes courts, pas de titre H1/H2, texte brut

CONSIGNES META (130-155 caracteres) :
- Inclure : chauffagiste {$nom}, devis gratuit
- Formule comme une invitation a l'action

FORMAT DE REPONSE — JSON valide uniquement, sans markdown ni balise :
{{"text":"Texte editorial ici","meta":"Meta description ici"}}
PROMPT;
}

// ─── Helpers ──────────────────────────────────────────────────────────────────

function communeContext(array $c): array
{
    $log          = is_array($c['logement'] ?? null) ? $c['logement'] : [];
    $logTotal     = max(1, (int)($log['logements_total'] ?? 1));
    $logAvant1990 = (int)($log['logements_avant_1990'] ?? 0);
    $pct          = (int)round($logAvant1990 / $logTotal * 100);

    $zone = (string)($c['aides_etat']['zone_climatique'] ?? 'H2');
    $zoneLabels = [
        'H1' => 'nord/est de la France, hivers rigoureux — forte demande chaudiere gaz et PAC, DJU eleve, MaPrimeRenov maximale pour les foyers modestes',
        'H2' => 'centre de la France, climat tempere — bonne demande chaudiere condensation et PAC air-eau, TVA 5,5% et prime CEE accessibles',
        'H3' => 'sud de la France, hivers doux — plus grand interet pour PAC reversible et climatisation, marche pompe a chaleur air-air en croissance',
    ];

    $aides = [];
    if (!empty($c['aides_etat']['qpv'])) $aides[] = '- QPV : MaPrimeRenov majoree jusqu\'a 100%, aides locales chauffage renouvelable';
    if (!empty($c['aides_etat']['action_coeur_de_ville'])) $aides[] = '- Action Coeur de Ville : subventions rehabilitation thermique, accompagnement renovation';
    if (!empty($c['aides_etat']['petites_villes_de_demain'])) $aides[] = '- Petites Villes de Demain : soutien renovation energetique habitat, partenariats locaux';

    return [
        'population'      => number_format((int)($c['population'] ?? 0), 0, ',', ' '),
        'pct_avant_1990'  => $pct,
        'zone'            => $zone,
        'zone_label'      => $zoneLabels[$zone] ?? '',
        'aides_speciales' => $aides ? implode("\n", $aides) : '',
    ];
}

function villesProchesLabel(array $c): string
{
    $proches = array_slice($c['villes_proches'] ?? [], 0, 3);
    if (empty($proches)) return 'communes voisines';
    $parts = [];
    foreach ($proches as $v) {
        $parts[] = $v['nom'] . ' (' . (int)round($v['distance_km']) . ' km)';
    }
    return implode(', ', $parts);
}

function callWithRetry(string $prompt, array &$stats): ?string
{
    for ($i = 1; $i <= MAX_RETRIES; $i++) {
        $result = callDeepSeek($prompt, $stats);
        if ($result !== null) return $result;
        if ($i < MAX_RETRIES) sleep($i * 2);
    }
    return null;
}

function callDeepSeek(string $prompt, array &$stats): ?string
{
    $payload = json_encode([
        'model'       => DEEPSEEK_MODEL,
        'messages'    => [
            ['role' => 'system', 'content' => 'Tu es redacteur SEO expert en chauffage, plomberie et renovation energetique. Reponds UNIQUEMENT avec un objet JSON valide contenant les cles "text" et "meta". Aucun markdown, aucun commentaire, aucune balise.'],
            ['role' => 'user',   'content' => $prompt],
        ],
        'max_tokens'  => 700,
        'temperature' => 0.85,
    ]);

    $ch = curl_init('https://api.deepseek.com/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . DEEPSEEK_API_KEY,
        ],
    ]);

    $raw      = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    unset($ch);

    if ($raw === false || $httpCode !== 200) return null;

    $response = json_decode($raw, true);
    $content  = trim($response['choices'][0]['message']['content'] ?? '');
    if ($content === '') return null;

    $content = preg_replace('/^```(?:json)?\s*|\s*```$/s', '', $content);

    $data = json_decode(trim($content), true);
    if (!is_array($data) || empty($data['text']) || empty($data['meta'])) return null;

    $data['meta'] = mb_substr(trim($data['meta']), 0, 155);

    $stats['tokens_in']  += $response['usage']['prompt_tokens']     ?? 0;
    $stats['tokens_out'] += $response['usage']['completion_tokens'] ?? 0;

    return json_encode($data, JSON_UNESCAPED_UNICODE);
}

function estimateCost(array $stats): float
{
    return $stats['tokens_in'] * 0.00000014 + $stats['tokens_out'] * 0.00000028;
}
