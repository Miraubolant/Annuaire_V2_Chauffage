<?php
define('SITE_EMAIL', 'admin@miraubolant.com');
// ─── Identité site ────────────────────────────────────────────────────────────
define('SITE_NAME',    'Annuaire Chauffagiste');
define('SITE_URL',     rtrim(getenv('SITE_URL') ?: 'https://annuaire-chauffagiste-france.fr', '/'));
define('SITE_YEAR',    date('Y'));
define('METIER',       'chauffagiste');
define('METIER_PLURIEL', 'chauffagistes');
define('METIER_CAP',   'Chauffagiste');
define('NICHE_KEY',    'chauffage_pac_clim');
define('NICHE_DIR',    'artisans-chauffage');
define('DATA_DIR',     __DIR__ . '/output');

// ─── ViteUnDevis ─────────────────────────────────────────────────────────────
define('VUD_PARTENAIRE_ID', 2372);
define('VUD_CATEGORIE_ID',  156);
define('VUD_BASE_URL',      'https://www.viteundevis.com');
define('VUD_DEVIS_URL',     'https://www.viteundevis.com/devis-0-156-devis_chauffage.php');

// ─── Modèles de services chauffage ───────────────────────────────────────────
define('MODELES', [
    // Chaudières
    ['slug' => 'installation-chaudiere-gaz',        'nom' => 'Installation chaudière gaz à condensation', 'emoji' => '🔥', 'vud_cat' => 156],
    ['slug' => 'installation-chaudiere-fioul',      'nom' => 'Installation chaudière fioul',               'emoji' => '🛢️',  'vud_cat' => 156],
    ['slug' => 'remplacement-chaudiere',            'nom' => 'Remplacement chaudière ancienne',            'emoji' => '🔄', 'vud_cat' => 156],
    // Chauffage renouvelable
    ['slug' => 'pompe-chaleur-air-eau',             'nom' => 'Pompe à chaleur air-eau',                   'emoji' => '♻️',  'vud_cat' => 156],
    ['slug' => 'poele-bois-pellets',                'nom' => 'Poêle à bois et à pellets',                 'emoji' => '🌲', 'vud_cat' => 156],
    ['slug' => 'chaudiere-biomasse',                'nom' => 'Chaudière biomasse',                         'emoji' => '🌿', 'vud_cat' => 156],
    // Émetteurs & distribution
    ['slug' => 'plancher-chauffant',                'nom' => 'Plancher chauffant hydraulique',             'emoji' => '🏠', 'vud_cat' => 156],
    ['slug' => 'radiateurs-emetteurs',              'nom' => 'Radiateurs et émetteurs de chaleur',         'emoji' => '🌡️', 'vud_cat' => 156],
    // Entretien & SAV
    ['slug' => 'entretien-chaudiere',               'nom' => 'Entretien annuel chaudière',                 'emoji' => '🧰', 'vud_cat' => 156],
    ['slug' => 'depannage-chauffage',               'nom' => 'Dépannage chauffage urgence',                'emoji' => '🛠️',  'vud_cat' => 156],
    // Eau chaude & climatisation
    ['slug' => 'ballon-eau-chaude',                 'nom' => 'Ballon eau chaude et chauffe-eau',           'emoji' => '💧', 'vud_cat' => 156],
    ['slug' => 'audit-energetique',                 'nom' => 'Audit énergétique et bilan thermique',       'emoji' => '📋', 'vud_cat' => 156],
]);

// ─── Aides nationales ────────────────────────────────────────────────────────
define('AIDES_NATIONALES', [
    'maprimerenov' => [
        'nom'         => 'MaPrimeRénov\'',
        'code'        => 'MaPrimeRénov\'',
        'description' => 'Aide de l\'État pour le remplacement d\'une chaudière ancienne par un système de chauffage renouvelable : pompe à chaleur, chaudière bois/pellets, réseau de chaleur. Cumulable avec les primes CEE.',
        'montant'     => 'De 1 500 € à 11 000 € selon le système et les revenus du foyer',
        'conditions'  => 'Résidence principale, logement de plus de 15 ans, artisan RGE obligatoire',
        'travaux'     => ['pompe-chaleur-air-eau', 'poele-bois-pellets', 'chaudiere-biomasse', 'remplacement-chaudiere'],
        'url'         => 'https://www.maprimerenov.gouv.fr',
    ],
    'cee_bar_th_106' => [
        'nom'         => 'Prime CEE BAR-TH-106',
        'code'        => 'BAR-TH-106',
        'description' => 'Prime CEE pour l\'installation d\'une chaudière individuelle à haute performance énergétique (condensation ou à très haute performance).',
        'montant'     => 'Jusqu\'à 500 € selon la zone climatique et les revenus',
        'conditions'  => 'Logement de plus de 2 ans, artisan RGE, chaudière HPE ou THPE',
        'travaux'     => ['installation-chaudiere-gaz', 'installation-chaudiere-fioul', 'remplacement-chaudiere'],
        'url'         => 'https://www.ecologie.gouv.fr/dispositif-des-certificats-deconomies-denergie',
    ],
    'tva_55' => [
        'nom'         => 'TVA à 5,5 %',
        'code'        => 'TVA 5,5%',
        'description' => 'Taux de TVA réduit à 5,5 % pour les équipements de chauffage renouvelable (PAC, chaudière bois, solaire thermique) et à 10 % pour les autres travaux de chauffage dans les logements de plus de 2 ans.',
        'montant'     => 'Économie de 14,5 % à 10 % selon le type de travaux',
        'conditions'  => 'Logement achevé depuis plus de 2 ans, résidence principale ou secondaire',
        'travaux'     => ['pompe-chaleur-air-eau', 'poele-bois-pellets', 'chaudiere-biomasse', 'installation-chaudiere-gaz'],
        'url'         => 'https://www.impots.gouv.fr/particulier/questions/jai-fait-des-travaux-dans-mon-logement-quelle-tva-sappliquer',
    ],
    'eco_ptz' => [
        'nom'         => 'Éco-PTZ (Prêt à Taux Zéro)',
        'code'        => 'Éco-PTZ',
        'description' => 'Prêt sans intérêts pour financer les travaux de chauffage renouvelable dans le cadre d\'une rénovation énergétique. Cumulable avec MaPrimeRénov\'.',
        'montant'     => 'Jusqu\'à 50 000 € remboursable sur 20 ans sans intérêts',
        'conditions'  => 'Résidence principale, travaux combinés à d\'autres gestes de rénovation',
        'travaux'     => ['pompe-chaleur-air-eau', 'chaudiere-biomasse', 'plancher-chauffant'],
        'url'         => 'https://www.ecologie.gouv.fr/leco-pret-taux-zero-leco-ptz',
    ],
]);

// ─── Types de systèmes de chauffage ──────────────────────────────────────────
define('ZONES_CLIMATIQUES', [
    'gaz-condensation' => [
        'label'       => 'Chaudière gaz condensation',
        'description' => 'La chaudière gaz à condensation reste le système le plus répandu. Rendement > 109 %, économies de 15 à 25 % sur la facture. Eligible CEE BAR-TH-106. Idéale pour remplacer une vieille chaudière.',
        'cee_bonus'   => true,
        'couleur'     => 'orange',
    ],
    'pompe-chaleur' => [
        'label'       => 'Pompe à chaleur air-eau',
        'description' => 'Solution renouvelable par excellence : COP de 3 à 5 (3 à 5 kWh de chaleur pour 1 kWh électrique). Eligible MaPrimeRénov\' jusqu\'à 11 000 €. Réduit les émissions CO₂ de 60 à 70 %.',
        'cee_bonus'   => true,
        'couleur'     => 'blue',
    ],
    'bois-pellets' => [
        'label'       => 'Poêle & chaudière bois/pellets',
        'description' => 'Chauffage renouvelable au coût de fonctionnement le plus bas. Eligible MaPrimeRénov\' et crédit d\'impôt. Le pellet est 2 à 3 fois moins cher que le gaz à l\'équivalent thermique.',
        'cee_bonus'   => true,
        'couleur'     => 'green',
    ],
]);

// ─── Aides dispositifs urbains ────────────────────────────────────────────────
define('AIDES_QPV', [
    'nom'         => 'Quartier Prioritaire de la Ville (QPV)',
    'description' => 'Cette commune est classée en Quartier Prioritaire de la Ville. MaPrimeRénov\' est majorée pour les travaux de chauffage renouvelable.',
    'avantages'   => ['Majoration MaPrimeRénov\' jusqu\'à 100 %', 'TVA à 5,5 % élargie', 'Accompagnement ANRU possible'],
]);

define('AIDES_ACV', [
    'nom'         => 'Action Cœur de Ville',
    'description' => 'Cette commune bénéficie du programme Action Cœur de Ville, soutenant la rénovation énergétique des logements en centre-ville.',
    'avantages'   => ['Aides à la réhabilitation thermique', 'Accompagnement personnalisé', 'Subventions locales potentielles'],
]);

define('AIDES_PVD', [
    'nom'         => 'Petites Villes de Demain',
    'description' => 'Cette commune participe au programme Petites Villes de Demain, favorisant la rénovation énergétique et le remplacement des systèmes de chauffage anciens.',
    'avantages'   => ['Subventions rénovation habitat', 'Ingénierie de projet financée', 'Partenariats locaux renforcés'],
]);

// ─── FAQ homepage ─────────────────────────────────────────────────────────────
define('FAQ_ACCUEIL', [
    [
        'q' => 'Comment trouver un chauffagiste qualifié près de chez moi ?',
        'r' => 'Utilisez notre moteur de recherche en saisissant le nom de votre ville. Vous obtiendrez la liste des chauffagistes professionnels certifiés RGE dans votre commune, avec leurs avis clients et coordonnées. Demandez 3 devis pour comparer les offres.',
    ],
    [
        'q' => 'Quel est le prix d\'une chaudière à gaz installée ?',
        'r' => 'Une chaudière gaz à condensation installée coûte entre 2 500 et 5 000 €, selon la puissance et la marque. Après la prime CEE BAR-TH-106 (jusqu\'à 500 €) et la TVA à 5,5 %, le reste à charge peut descendre à 2 000-3 500 €. Les chaudières très haute performance (THPE) sont éligibles à de meilleures primes.',
    ],
    [
        'q' => 'Puis-je bénéficier de MaPrimeRénov\' pour remplacer ma chaudière ?',
        'r' => 'Oui, si vous remplacez une chaudière ancienne par un système renouvelable (pompe à chaleur, chaudière biomasse). La prime peut atteindre 11 000 € pour une PAC air-eau selon vos revenus. En revanche, le remplacement d\'une chaudière gaz par une autre chaudière gaz n\'est plus éligible depuis 2024.',
    ],
    [
        'q' => 'Quelle est la meilleure solution de chauffage pour une maison ?',
        'r' => 'La pompe à chaleur air-eau est la solution la plus efficace à long terme (COP 3 à 5). La chaudière gaz à condensation reste pertinente pour les logements mal isolés. Le poêle à pellets offre le coût de fonctionnement le plus bas. Le choix dépend de l\'isolation, du budget et de la disponibilité du gaz.',
    ],
    [
        'q' => 'Faut-il un chauffagiste RGE pour bénéficier des aides ?',
        'r' => 'Oui, pour MaPrimeRénov\', les primes CEE et l\'Éco-PTZ, les travaux doivent être réalisés par un chauffagiste certifié RGE (Reconnu Garant de l\'Environnement). Vérifiez la certification sur faire-france.fr ou qualit-enr.org avant de signer un devis.',
    ],
    [
        'q' => 'À quelle fréquence faut-il entretenir sa chaudière ?',
        'r' => 'L\'entretien annuel de la chaudière est obligatoire pour les chaudières de 4 à 400 kW. Il doit être réalisé par un chauffagiste qualifié et donne lieu à un contrat d\'entretien. Un défaut d\'entretien peut entraîner la perte de garantie constructeur et présenter des risques d\'intoxication au monoxyde de carbone.',
    ],
    [
        'q' => 'Quelle est la durée de vie d\'une chaudière ?',
        'r' => 'Une chaudière bien entretenue dure en moyenne 15 à 20 ans. Au-delà, le rendement baisse et les pannes se multiplient. Remplacer une chaudière de plus de 15 ans permet de réduire la consommation de gaz de 20 à 30 % et d\'accéder aux aides de l\'État.',
    ],
    [
        'q' => 'Combien coûte une pompe à chaleur air-eau installée ?',
        'r' => 'Une PAC air-eau coûte entre 8 000 et 18 000 € installée. Après MaPrimeRénov\' (jusqu\'à 11 000 € pour les ménages modestes) et les primes CEE, le reste à charge peut descendre à 3 000-7 000 €. L\'économie sur la facture de chauffage est de 50 à 70 % par rapport à une chaudière électrique.',
    ],
    [
        'q' => 'Peut-on installer un plancher chauffant en rénovation ?',
        'r' => 'Oui, grâce au plancher chauffant basse température (30-45 °C), compatible avec les PAC. Il s\'installe sur dalle existante avec une chape sèche fine (3 cm) ou en rénovation légère sur support isolant. Très confortable, il répartit la chaleur uniformément et permet des économies de 15 % par rapport aux radiateurs.',
    ],
    [
        'q' => 'Quels sont les signes que ma chaudière doit être remplacée ?',
        'r' => 'Plusieurs signes indiquent qu\'il est temps de changer : pannes fréquentes, bruits anormaux, factures de gaz en hausse, rendement faible (chaudière non-condensation), chaudière de plus de 15 ans. Un chauffagiste RGE peut réaliser un diagnostic gratuit et vous conseiller sur la solution la plus rentable.',
    ],
]);

// ─── Sites du réseau ──────────────────────────────────────────────────────────
define('NETWORK_SITES', [
    ['nom' => 'Annuaire Menuisier',   'url' => 'https://annuaire-menuisier-france.fr',   'emoji' => '🪟'],
    ['nom' => 'Annuaire Couvreur',    'url' => 'https://annuaire-couvreur-france.fr',    'emoji' => '🏠'],
    ['nom' => 'Annuaire Isolation',   'url' => 'https://annuaire-isolation-france.fr',   'emoji' => '🧱'],
    ['nom' => 'Annuaire VMC',         'url' => 'https://annuaire-vmc-france.fr',         'emoji' => '💨'],
    ['nom' => 'Annuaire Pisciniste',  'url' => 'https://annuaire-pisciniste-france.fr',  'emoji' => '🏊'],
]);
