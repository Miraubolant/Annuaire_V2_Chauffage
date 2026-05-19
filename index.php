<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$isHomepage    = true;
$title         = seoTitle('home', []);
$description   = seoDescription('home', []);
$canonical_url = SITE_URL . '/';
$robots        = 'index,follow';
$jsonLd = [
    jsonLdOrganization(),
    jsonLdWebSite(),
    jsonLdFAQ(FAQ_ACCUEIL),
];

$allRegionFiles = glob(DATA_DIR . '/regions/*.json');
$topRegionSlugs = [];
foreach ($allRegionFiles as $f) {
    $topRegionSlugs[] = basename($f, '.json');
}
usort($topRegionSlugs, function($a, $b) {
    $ra = getRegionData($a)['stats']['artisans_chauffage'] ?? 0;
    $rb = getRegionData($b)['stats']['artisans_chauffage'] ?? 0;
    return $rb <=> $ra;
});

require __DIR__ . '/templates/header.php'; ?><section class="hero-section">
    <div class="hero-grain"></div>

    <div class="hero-content">
        <div class="hero-eyebrow">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            30 000 chauffagistes référencés en France
        </div>

        <h1 class="hero-title">
            Trouvez votre<br><em>chauffagiste</em><br>près de chez vous
        </h1>

        <p class="hero-subtitle">
            Construction, rénovation, entretien — comparez les chauffagistes<br>
            et bénéficiez des solutions de financement et de la TVA à 5,5%.
        </p>

        <!-- Barre de recherche hero -->
        <p class="hero-search-label">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            Recherchez par ville ou code postal
        </p>
        <div class="hero-search-wrap" x-data="searchVille()">
            <div class="hero-search-bar">
                <svg class="hero-search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input
                    type="text"
                    class="hero-search-input"
                    x-model="query"
                    @input.debounce.300ms="search()"
                    @keydown.arrow-down.prevent="focusNext()"
                    @keydown.arrow-up.prevent="focusPrev()"
                    @keydown.enter.prevent="selectFocused() || (results[0] && (window.location.href = results[0].url))"
                    @keydown.escape="results = []"
                    placeholder="Entrez votre ville ou code postal…"
                    autocomplete="off"
                >
                <button class="hero-search-btn"
                        @click="selectFocused() || (results[0] && (window.location.href = results[0].url))">
                    <span class="hero-search-btn-label">Trouver un installateur</span>
                    <svg class="hero-search-btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>
            </div>

            <!-- Résultats -->
            <div x-show="results.length > 0" x-cloak class="hero-search-results">
                <template x-for="(r, i) in results" :key="r.url">
                    <a :href="r.url"
                       :class="{ focused: focused === i }"
                       @mouseenter="focused = i"
                       class="search-result-item">
                        <div>
                            <div class="search-result-city" x-text="r.nom"></div>
                            <div class="search-result-cp" x-text="r.cp"></div>
                        </div>
                        <div class="search-result-badge"
                             x-text="r.artisans > 0 ? r.artisans + ' chauffagistes' : 'Devis possible'"></div>
                    </a>
                </template>
            </div>
        </div>

        <!-- Trust signals -->
        <div class="hero-trust">
            <div class="hero-trust-item">
                <div class="hero-trust-icon">✓</div>
                <span>chauffagistes professionnels vérifiés</span>
            </div>
            <div class="hero-trust-item">
                <div class="hero-trust-icon">✓</div>
                <span>TVA 5,5% & financement disponible</span>
            </div>
            <div class="hero-trust-item">
                <div class="hero-trust-icon">✓</div>
                <span>Devis gratuit & sans engagement</span>
            </div>
        </div>
    </div>

    <!-- Scroll hint -->
    <div class="hero-scroll-hint">
        <span>Découvrir</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
    </div>
</section>

<!-- ─── Stats bar ─────────────────────────────────────────────────────── -->
<div class="stats-row" style="justify-content:center;">
    <div class="stat-chip">
        <div>
            <div class="stat-chip-num">30 915</div>
            <div class="stat-chip-label">chauffagistes référencés</div>
        </div>
    </div>
    <div class="stat-chip">
        <div>
            <div class="stat-chip-num">34 882</div>
            <div class="stat-chip-label">Communes couvertes</div>
        </div>
    </div>
    <div class="stat-chip">
        <div>
            <div class="stat-chip-num">12</div>
            <div class="stat-chip-label">Services chauffage disponibles</div>
        </div>
    </div>
    <div class="stat-chip">
        <div>
            <div class="stat-chip-num">TVA 5,5%</div>
            <div class="stat-chip-label">Sur travaux de rénovation</div>
        </div>
    </div>
</div>

<!-- ─── Régions ───────────────────────────────────────────────────────── -->
<section style="padding: 80px 24px; max-width:1280px; margin:0 auto;">
    <div style="text-align:center;margin-bottom:48px;">
        <span class="section-eyebrow">🗺️ couverture nationale</span>
        <h2 class="section-title">chauffagistes par région</h2>
        <p class="section-subtitle">Sélectionnez votre région pour trouver les chauffagistes près de chez vous.</p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;">
        <?php foreach ($topRegionSlugs as $rSlug):
            $region = getRegionData($rSlug);
            if (empty($region)) continue;
            $rNom   = $region['region']['nom'] ?? nomRegion($rSlug);
            $nbArt  = $region['stats']['artisans_chauffage'] ?? 0;
            $nbCom  = $region['stats']['communes_avec_artisans'] ?? 0;
            $nbDep  = count($region['departements'] ?? []);
            $url    = urlRegion($rSlug);
        ?>
        <a href="<?= htmlspecialchars($url) ?>" class="region-card">
            <div class="region-card-name"><?= htmlspecialchars($rNom) ?></div>
            <div class="region-card-stats">
                🗺️ <?= $nbDep ?> département<?= $nbDep > 1 ? 's' : '' ?><br>
                🔥 <?= number_format($nbArt, 0, ',', ' ') ?> chauffagistes<br>
                📍 <?= number_format($nbCom, 0, ',', ' ') ?> communes
            </div>
            <div class="region-card-cta">
                Voir les installateurs
                <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- ─── Aides ─────────────────────────────────────────────────────────── -->
<section id="aides" style="background:var(--stone);padding:80px 24px;">
    <div style="max-width:1280px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:48px;">
            <span class="section-eyebrow">💶 Financements disponibles</span>
            <h2 class="section-title">Aides et financements chauffage</h2>
            <p class="section-subtitle">Cumulables · Applicables partout en France · Sans avance de frais</p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px;">
            <?php foreach (AIDES_NATIONALES as $aide): ?>
            <div class="aide-card">
                <div class="aide-card-nom"><?= htmlspecialchars($aide['nom']) ?></div>
                <div class="aide-card-montant"><?= htmlspecialchars($aide['montant']) ?></div>
                <div class="aide-card-cond"><?= htmlspecialchars($aide['conditions']) ?></div>
                <a href="<?= htmlspecialchars($aide['url']) ?>"
                   style="display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:600;color:var(--forest);text-decoration:none;margin-top:12px;"
                   target="_blank" rel="noopener nofollow">
                    En savoir plus
                    <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.25 5.5a.75.75 0 000 1.5h8.5a.75.75 0 000-1.5h-8.5zm0 4a.75.75 0 000 1.5h5.5a.75.75 0 000-1.5h-5.5z" clip-rule="evenodd"/></svg>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align:center;margin-top:40px;">
            <a href="<?= SITE_URL ?>/aides/"
               style="display:inline-flex;align-items:center;gap:8px;background:var(--forest);color:#fff;font-size:14px;font-weight:700;padding:13px 28px;border-radius:100px;text-decoration:none;box-shadow:0 4px 14px rgba(61,74,82,.3);transition:opacity .15s;"
               onmouseover="this.style.opacity='.88'" onmouseout="this.style.opacity='1'">
                Toutes les aides chauffage
                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- ─── Widget devis ──────────────────────────────────────────────────── -->
<section style="background:var(--forest);padding:64px 24px;">
    <div style="max-width:860px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:32px;">
            <span class="section-eyebrow" style="color:rgba(255,255,255,.6);">🔥 Devis gratuit</span>
            <h2 style="font-family:var(--font-display);font-size:clamp(22px,3vw,34px);font-weight:700;color:#fff;line-height:1.2;margin:12px 0 8px;letter-spacing:-.02em;">
                Obtenez vos devis chauffage maintenant
            </h2>
            <p style="font-size:15px;color:rgba(255,255,255,.6);line-height:1.7;">
                Réponse sous 48h · Sans engagement · chauffagistes professionnels
            </p>
        </div>
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
</section>

<!-- ─── Types de systèmes de chauffage ─────────────────────────────────── -->
<section style="padding:80px 24px;max-width:1280px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:48px;">
        <span class="section-eyebrow">🔥 Quel système de chauffage ?</span>
        <h2 class="section-title">Les 3 grands systèmes de chauffage</h2>
        <p class="section-subtitle">Chaque logement est unique — découvrez la solution adaptée à votre habitat et budget.</p>
    </div>
    <div class="zones-grid">
        <?php foreach (ZONES_CLIMATIQUES as $code => $zone):
            $colors = [
                'gaz-condensation' => ['bg' => '#FFF7ED', 'border' => '#EA580C', 'text' => '#9A3412', 'emoji' => '🔥'],
                'pompe-chaleur'    => ['bg' => '#EFF6FF', 'border' => '#3B82F6', 'text' => '#1D4ED8', 'emoji' => '♻️'],
                'bois-pellets'     => ['bg' => '#F0FDF4', 'border' => '#16A34A', 'text' => '#15803D', 'emoji' => '🌲'],
            ];
            $c = $colors[$code] ?? ['bg' => '#F1F5F9', 'border' => '#64748B', 'text' => '#334155', 'emoji' => '🔥'];
        ?>
        <div style="background:<?= $c['bg'] ?>;border:1px solid <?= $c['border'] ?>33;border-top:3px solid <?= $c['border'] ?>;border-radius:16px;padding:24px;">
            <div style="display:inline-flex;align-items:center;gap:8px;background:<?= $c['border'] ?>18;color:<?= $c['text'] ?>;font-size:13px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;padding:4px 12px;border-radius:100px;margin-bottom:12px;">
                <?= $c['emoji'] ?> <?= htmlspecialchars($zone['label']) ?>
            </div>
            <p style="font-size:13px;color:var(--text-muted);line-height:1.7;"><?= htmlspecialchars($zone['description']) ?></p>
            <?php if ($zone['cee_bonus']): ?>
            <span style="display:inline-block;margin-top:10px;font-size:11px;font-weight:700;color:<?= $c['text'] ?>;background:<?= $c['border'] ?>20;padding:3px 10px;border-radius:100px;">
                ✓ Éligible prime CEE
            </span>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ─── FAQ ───────────────────────────────────────────────────────────── -->
<section style="background:var(--stone);padding:80px 24px;">
    <div style="max-width:720px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:48px;">
            <span class="section-eyebrow">❓ Questions fréquentes</span>
            <h2 class="section-title">Tout savoir sur le chauffage</h2>
        </div>
        <?php
        $questions = FAQ_ACCUEIL;
        $title = "Questions fréquentes sur le chauffage";
        require __DIR__ . '/components/faq.php';
        ?>
    </div>
</section>

<!-- ─── CTA final ─────────────────────────────────────────────────────── -->
<section style="background:var(--forest);padding:80px 24px;text-align:center;">
    <div style="max-width:640px;margin:0 auto;">
        <div style="font-size:40px;margin-bottom:20px;">🔥</div>
        <h2 style="font-family:var(--font-display);font-size:clamp(28px,4vw,42px);font-weight:700;color:#fff;line-height:1.2;margin-bottom:16px;letter-spacing:-.02em;">
            Prêt à rénover votre système de chauffage ?
        </h2>
        <p style="font-size:16px;color:rgba(255,255,255,.65);margin-bottom:36px;line-height:1.7;">
            Comparez les offres des chauffagistes qualifiés de votre région.<br>
            Devis gratuit, sans engagement, réponse en 48h.
        </p>
        <a href="<?= VUD_DEVIS_URL ?>"
           style="display:inline-flex;align-items:center;gap:10px;background:var(--gold);color:#fff;font-size:16px;font-weight:700;padding:16px 36px;border-radius:100px;text-decoration:none;box-shadow:0 6px 24px rgba(200,150,62,.4);transition:transform .15s,box-shadow .15s;"
           onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 10px 32px rgba(200,150,62,.5)';"
           onmouseout="this.style.transform='';this.style.boxShadow='0 6px 24px rgba(200,150,62,.4)';"
           target="_blank" rel="noopener sponsored">
            Obtenir mon devis gratuit
            <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
        </a>
        <p style="font-size:12px;color:rgba(255,255,255,.35);margin-top:16px;">Gratuit · Sans engagement · Réponse en 24h</p>
    </div>
</section>

<?php require __DIR__ . '/templates/footer.php'; ?>
