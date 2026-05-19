<?php
$title         ??= SITE_NAME . ' — Devis gratuit ' . SITE_YEAR;
$description   ??= 'Trouvez votre ' . METIER . ' qualifié en France. Devis gratuit en 2 minutes.';
$robots        ??= 'index,follow';
$canonical_url ??= SITE_URL . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$jsonLd        ??= [];
$ogImage       ??= SITE_URL . '/assets/img/og-default.jpg';
$isHomepage    ??= false;

$serviceGroups = [
    ['icon' => '🔥', 'label' => 'Chaudières', 'items' => [
        ['slug' => 'installation-chaudiere-gaz',    'nom' => 'Chaudière gaz condensation'],
        ['slug' => 'installation-chaudiere-fioul',  'nom' => 'Chaudière fioul'],
        ['slug' => 'remplacement-chaudiere',        'nom' => 'Remplacement chaudière'],
    ]],
    ['icon' => '♻️', 'label' => 'Chauffage renouvelable', 'items' => [
        ['slug' => 'pompe-chaleur-air-eau',         'nom' => 'Pompe à chaleur air-eau'],
        ['slug' => 'poele-bois-pellets',            'nom' => 'Poêle bois & pellets'],
        ['slug' => 'chaudiere-biomasse',            'nom' => 'Chaudière biomasse'],
    ]],
    ['icon' => '🏠', 'label' => 'Émetteurs & distribution', 'items' => [
        ['slug' => 'plancher-chauffant',            'nom' => 'Plancher chauffant'],
        ['slug' => 'radiateurs-emetteurs',          'nom' => 'Radiateurs & émetteurs'],
        ['slug' => 'ballon-eau-chaude',             'nom' => 'Ballon eau chaude'],
    ]],
    ['icon' => '🧰', 'label' => 'Entretien & SAV', 'items' => [
        ['slug' => 'entretien-chaudiere',           'nom' => 'Entretien annuel chaudière'],
        ['slug' => 'depannage-chauffage',           'nom' => 'Dépannage urgence'],
        ['slug' => 'audit-energetique',             'nom' => 'Audit énergétique'],
    ]],
];

$regionLinks = [
    ['slug' => 'ile-de-france',             'nom' => 'Île-de-France'],
    ['slug' => 'auvergne-rhone-alpes',      'nom' => 'Auvergne-Rhône-Alpes'],
    ['slug' => 'nouvelle-aquitaine',        'nom' => 'Nouvelle-Aquitaine'],
    ['slug' => 'occitanie',                 'nom' => 'Occitanie'],
    ['slug' => 'hauts-de-france',           'nom' => 'Hauts-de-France'],
    ['slug' => 'grand-est',                 'nom' => 'Grand Est'],
    ['slug' => 'bretagne',                  'nom' => 'Bretagne'],
    ['slug' => 'pays-de-la-loire',          'nom' => 'Pays de la Loire'],
    ['slug' => 'normandie',                 'nom' => 'Normandie'],
    ['slug' => 'provence-alpes-cote-d-azur','nom' => 'Provence-Alpes-Côte d\'Azur'],
    ['slug' => 'bourgogne-franche-comte',   'nom' => 'Bourgogne-Franche-Comté'],
    ['slug' => 'centre-val-de-loire',       'nom' => 'Centre-Val de Loire'],
    ['slug' => 'corse',                     'nom' => 'Corse'],
];
?>
<!DOCTYPE html>
<html lang="fr" style="margin:0;padding:0;">
<head>
    <style>html,body{margin:0;padding:0;}</style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="<?= htmlspecialchars($robots) ?>">
    <title><?= htmlspecialchars($title) ?></title>
    <meta name="description" content="<?= htmlspecialchars(truncate($description, 160)) ?>">
    <?= canonical($canonical_url) ?>

    <meta property="og:type"        content="website">
    <meta property="og:title"       content="<?= htmlspecialchars($title) ?>">
    <meta property="og:description" content="<?= htmlspecialchars(truncate($description, 160)) ?>">
    <meta property="og:url"         content="<?= htmlspecialchars($canonical_url) ?>">
    <meta property="og:image"       content="<?= htmlspecialchars($ogImage) ?>">
    <meta property="og:site_name"   content="<?= SITE_NAME ?>">
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?= htmlspecialchars($title) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars(truncate($description, 160)) ?>">
    <meta name="twitter:image"       content="<?= htmlspecialchars($ogImage) ?>">

    <?php foreach ($jsonLd as $ld): ?><?= $ld ?><?php endforeach; ?>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/app.min.css">
    <link rel="icon" href="<?= SITE_URL ?>/assets/img/favicon.svg" type="image/svg+xml">
    <meta name="theme-color" content="#0369A1">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body style="background:var(--cream); color:var(--text);">

<div
    class="site-header <?= $isHomepage ? 'hdr-transparent' : 'hdr-solid' ?>"
    x-data="{
        servicesOpen: false,
        regionsOpen:  false,
        deptsOpen:    false,
        searchOpen:   false,
        mobileOpen:   false,
        scrolled:     false,
        _svcTimer:    null,
        _regTimer:    null,
        _deptTimer:   null,
        init() {
            this.scrolled = window.scrollY > 10;
            this.$watch('searchOpen', v => { if (!v) Alpine.store('search').clear(); if (v) this.mobileOpen = false; });
            this.$watch('mobileOpen', v => { if (v) this.searchOpen = false; });
        }
    }"
    @scroll.window="scrolled = window.scrollY > 10"
    :class="(scrolled || !<?= $isHomepage ? 'true' : 'false' ?>) ? 'hdr-solid' : 'hdr-transparent'"
>
    <div class="hdr-inner">

        <!-- ── Logo ── -->
        <a href="<?= SITE_URL ?>/" class="hdr-logo">
            <div class="hdr-logo-icon">🏊</div>
            <div>
                <span class="hdr-logo-name"><?= SITE_NAME ?></span>
                <span class="hdr-logo-sub">10 000 chauffagistes en France</span>
            </div>
        </a>

        <!-- ── Nav centre ── -->
        <nav class="hdr-nav" style="position:relative;">

            <!-- Services -->
            <div style="position:relative;"
                 @mouseenter="clearTimeout(_svcTimer); servicesOpen=true"
                 @mouseleave="_svcTimer=setTimeout(()=>servicesOpen=false,200)">
                <button class="nav-btn" :class="{ open: servicesOpen }">
                    Services
                    <svg class="nav-chevron" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                </button>
                <div x-show="servicesOpen"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak
                     class="dropdown-panel"
                     style="left: -16px;">
                    <div class="mega-panel">
                        <?php foreach ($serviceGroups as $group): ?>
                        <div class="mega-col">
                            <div class="mega-col-header">
                                <span><?= $group['icon'] ?></span>
                                <?= htmlspecialchars($group['label']) ?>
                            </div>
                            <?php
                            $hasCityCtx = isset($villeSlug, $villeCp, $regionSlug, $deptSlug) && $villeSlug && $villeCp;
                            foreach ($group['items'] as $item):
                                $svcUrl = $hasCityCtx
                                    ? urlModele($regionSlug, $deptSlug, $villeSlug, $villeCp, $item['slug'])
                                    : null;
                            ?>
                            <?php if ($svcUrl): ?>
                            <a href="<?= htmlspecialchars($svcUrl) ?>" class="mega-link">
                                <?= htmlspecialchars($item['nom']) ?>
                            </a>
                            <?php else: ?>
                            <a href="#" class="mega-link"
                               @click.prevent="Alpine.store('search').set('<?= addslashes($item['slug']) ?>', '<?= addslashes($item['nom']) ?>'); servicesOpen=false; searchOpen=true; $nextTick(() => document.getElementById('hdr-search-input').focus())"
                               title="Entrez votre ville pour trouver un chauffagiste qualifié">
                                <?= htmlspecialchars($item['nom']) ?>
                            </a>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <?php endforeach; ?>
                        <div class="mega-footer">
                            <?php if ($hasCityCtx): ?>
                            <span style="font-size:13px;color:var(--text-muted);">12 services chauffage à <?= htmlspecialchars($villeNom ?? '') ?></span>
                            <?php else: ?>
                            <span style="font-size:13px;color:var(--text-muted);">Cliquez sur un service → entrez votre ville</span>
                            <?php endif; ?>
                            <a href="#" class="mega-cta"
                               @click.prevent="Alpine.store('search').clear(); servicesOpen=false; searchOpen=true; $nextTick(() => document.getElementById('hdr-search-input').focus())">
                                Rechercher par ville
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Régions -->
            <div style="position:relative;"
                 @mouseenter="clearTimeout(_regTimer); regionsOpen=true"
                 @mouseleave="_regTimer=setTimeout(()=>regionsOpen=false,200)">
                <button class="nav-btn" :class="{ open: regionsOpen }">
                    Régions
                    <svg class="nav-chevron" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                </button>
                <div x-show="regionsOpen"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak
                     class="dropdown-panel regions-panel"
                     style="left:-16px;">
                    <?php foreach ($regionLinks as $r): ?>
                    <a href="<?= htmlspecialchars(urlRegion($r['slug'])) ?>" class="region-link">
                        <?= htmlspecialchars($r['nom']) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Départements -->
            <div style="position:relative;"
                 @mouseenter="clearTimeout(_deptTimer); deptsOpen=true"
                 @mouseleave="_deptTimer=setTimeout(()=>deptsOpen=false,200)">
                <button class="nav-btn" :class="{ open: deptsOpen }">
                    Départements
                    <svg class="nav-chevron" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                </button>
                <div x-show="deptsOpen"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak
                     class="dropdown-panel"
                     style="left:-16px;min-width:760px;max-height:480px;overflow-y:auto;">
                    <?php
                    $deptsByRegion = [];
                    foreach (getDeptMapping() as $code => $d) {
                        $deptsByRegion[$d['region_slug']][] = ['code' => $code] + $d;
                    }
                    $regionNames = [
                        'ile-de-france'             => 'Île-de-France',
                        'auvergne-rhone-alpes'      => 'Auvergne-Rhône-Alpes',
                        'nouvelle-aquitaine'        => 'Nouvelle-Aquitaine',
                        'occitanie'                 => 'Occitanie',
                        'hauts-de-france'           => 'Hauts-de-France',
                        'grand-est'                 => 'Grand Est',
                        'bretagne'                  => 'Bretagne',
                        'pays-de-la-loire'          => 'Pays de la Loire',
                        'normandie'                 => 'Normandie',
                        'provence-alpes-cote-d-azur'=> 'PACA',
                        'bourgogne-franche-comte'   => 'Bourgogne-FC',
                        'centre-val-de-loire'       => 'Centre-Val de Loire',
                        'corse'                     => 'Corse',
                    ];
                    ?>
                    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0;padding:8px 0;">
                        <?php foreach ($regionNames as $rSlug => $rNom):
                            if (empty($deptsByRegion[$rSlug])) continue;
                        ?>
                        <div style="padding:12px 16px;">
                            <div style="font-size:10px;font-weight:800;color:var(--forest);text-transform:uppercase;letter-spacing:.07em;margin-bottom:8px;padding-bottom:6px;border-bottom:1px solid var(--stone);">
                                <?= htmlspecialchars($rNom) ?>
                            </div>
                            <?php foreach ($deptsByRegion[$rSlug] as $d): ?>
                            <a href="<?= htmlspecialchars(urlDepartement($rSlug, $d['slug'])) ?>"
                               style="display:block;font-size:12px;color:var(--text-muted);text-decoration:none;padding:3px 0;white-space:nowrap;transition:color .12s;"
                               onmouseover="this.style.color='var(--forest)'"
                               onmouseout="this.style.color='var(--text-muted)'">
                                <?= htmlspecialchars($d['nom']) ?> <span style="color:var(--text-muted);opacity:.6;">(<?= $d['code'] ?>)</span>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Aides -->
            <a href="<?= SITE_URL ?>/#aides" class="nav-btn" style="text-decoration:none;">
                Aides 2026
                <span style="font-size:10px;background:var(--gold);color:#fff;padding:2px 6px;border-radius:100px;font-weight:700;margin-left:2px;">CEE / TVA</span>
            </a>
        </nav>

        <!-- ── Actions ── -->
        <div class="hdr-actions">
            <button class="hdr-search-btn"
                    @click="searchOpen=!searchOpen; $nextTick(() => searchOpen && document.getElementById('hdr-search-input').focus())"
                    :title="searchOpen ? 'Fermer la recherche' : 'Rechercher une ville'">
                <svg x-show="!searchOpen" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <svg x-show="searchOpen" x-cloak width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
            <a href="<?= VUD_DEVIS_URL ?>"
               class="btn-devis-nav" target="_blank" rel="noopener sponsored">
                Devis gratuit
            </a>
            <!-- ── Hamburger (mobile) ── -->
            <button class="hdr-burger" @click="mobileOpen=!mobileOpen" :aria-expanded="mobileOpen" aria-label="Menu">
                <svg x-show="!mobileOpen" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
                <svg x-show="mobileOpen" x-cloak width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <!-- ── Mobile menu ── -->
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-cloak
         class="mobile-menu">
        <div class="mobile-menu-inner">
            <div class="mobile-menu-section">
                <span class="mobile-menu-label">Navigation</span>
                <a href="<?= SITE_URL ?>/#aides" class="mobile-menu-link" @click="mobileOpen=false">
                    Aides 2026
                    <span style="font-size:10px;background:var(--gold);color:#fff;padding:2px 6px;border-radius:100px;font-weight:700;">CEE / TVA</span>
                </a>
                <a href="<?= VUD_DEVIS_URL ?>" class="mobile-menu-link" target="_blank" rel="noopener sponsored">
                    Devis gratuit
                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
                </a>
            </div>
            <div class="mobile-menu-section">
                <span class="mobile-menu-label">Régions</span>
                <div class="mobile-menu-regions">
                    <?php foreach ($regionLinks as $r): ?>
                    <a href="<?= htmlspecialchars(urlRegion($r['slug'])) ?>" class="mobile-menu-region" @click="mobileOpen=false">
                        <?= htmlspecialchars($r['nom']) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <a href="<?= VUD_DEVIS_URL ?>" class="mobile-menu-cta" target="_blank" rel="noopener sponsored">
                Trouver un chauffagiste — Devis gratuit
            </a>
        </div>
    </div>

    <!-- ── Search bar expansible ── -->
    <div x-show="searchOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-cloak
         class="hdr-search-expanded"
         x-data="searchVille()">
        <div class="hdr-search-expanded-inner">
            <div class="hdr-search-field-wrap">
                <svg class="hdr-search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <span class="hdr-search-token" x-show="$store.search.slug" x-cloak>
                    <span x-text="$store.search.label"></span>
                    <button @click.stop="Alpine.store('search').clear(); $nextTick(() => document.getElementById('hdr-search-input').focus())" title="Retirer ce filtre">×</button>
                </span>
                <input
                    id="hdr-search-input"
                    type="text"
                    class="hdr-search-input"
                    x-model="query"
                    @input.debounce.300ms="search()"
                    @keydown.escape="searchOpen=false"
                    @keydown.arrow-down.prevent="focusNext()"
                    @keydown.arrow-up.prevent="focusPrev()"
                    @keydown.enter.prevent="selectFocused() || (results[0] && (window.location.href = results[0].url))"
                    :placeholder="$store.search.slug ? 'Dans quelle ville ?' : 'Entrez votre ville (ex. Lyon, Bordeaux, Rennes…)'"
                    autocomplete="off"
                >
                <button class="hdr-search-close" @click="searchOpen=false">×</button>
            </div>

            <div x-show="results.length > 0" x-cloak class="search-results-dropdown">
                <template x-for="(r, i) in results" :key="r.url">
                    <a :href="r.url"
                       :class="{ focused: focused === i }"
                       @mouseenter="focused = i"
                       class="search-result-item">
                        <div>
                            <div class="search-result-city" x-text="r.nom"></div>
                            <div class="search-result-cp" x-text="$store.search.slug ? $store.search.label + ' · ' + r.cp : 'Code postal : ' + r.cp"></div>
                        </div>
                        <div class="search-result-badge" x-text="r.artisans + ' chauffagistes'"></div>
                    </a>
                </template>
            </div>
        </div>
    </div>
</div><!-- /.site-header -->
<?php if (!$isHomepage): ?>
<div style="height:70px;"></div>
<?php endif; ?>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.store('search', {
        slug: null,
        label: null,
        set(slug, label) { this.slug = slug; this.label = label; },
        clear() { this.slug = null; this.label = null; }
    });
});

function searchVille() {
    return {
        query: '',
        results: [],
        focused: -1,
        async search() {
            if (this.query.length < 2) { this.results = []; return; }
            try {
                const res = await fetch('/api/search?q=' + encodeURIComponent(this.query));
                const data = await res.json();
                const svc = Alpine.store('search').slug;
                this.results = data.map(r => ({
                    ...r,
                    url: svc ? r.url.replace(/\/$/, '') + '/' + svc + '/' : r.url
                }));
                this.focused = -1;
            } catch(e) { this.results = []; }
        },
        focusNext() { this.focused = Math.min(this.focused + 1, this.results.length - 1); },
        focusPrev() { this.focused = Math.max(this.focused - 1, 0); },
        selectFocused() {
            if (this.focused >= 0 && this.results[this.focused]) {
                window.location.href = this.results[this.focused].url;
                return true;
            }
            return false;
        }
    };
}
</script>
