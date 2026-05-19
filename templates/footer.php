<footer class="site-footer">
    <div class="footer-top">

        <!-- Colonne 1 : Brand -->
        <div>
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:42px;height:42px;background:rgba(255,255,255,.1);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;">🔥</div>
                <span class="footer-brand-name"><?= SITE_NAME ?></span>
            </div>
            <p class="footer-brand-desc">
                Le premier annuaire des chauffagistes qualifiés en France. Trouvez un professionnel certifié près de chez vous pour construire, rénover ou entretenir votre chauffage.
            </p>
            <div class="footer-badges">
                <span class="footer-badge">✓ RGE</span>
                <span class="footer-badge">✓ QualiPAC certifié</span>
                <span class="footer-badge">✓ Financement dispo</span>
                <span class="footer-badge">✓ Devis gratuit</span>
            </div>
        </div>

        <!-- Colonne 2 : Départements -->
        <div>
            <p class="footer-col-title">Principaux départements</p>
            <?php
            $footerDepts = [
                ['code' => '75', 'nom' => 'Paris',              'region' => 'ile-de-france',             'slug' => 'paris'],
                ['code' => '69', 'nom' => 'Rhône',              'region' => 'auvergne-rhone-alpes',      'slug' => 'rhone'],
                ['code' => '13', 'nom' => 'Bouches-du-Rhône',   'region' => 'provence-alpes-cote-d-azur','slug' => 'bouches-du-rhone'],
                ['code' => '33', 'nom' => 'Gironde',            'region' => 'nouvelle-aquitaine',        'slug' => 'gironde'],
                ['code' => '59', 'nom' => 'Nord',               'region' => 'hauts-de-france',           'slug' => 'nord'],
                ['code' => '31', 'nom' => 'Haute-Garonne',      'region' => 'occitanie',                 'slug' => 'haute-garonne'],
                ['code' => '67', 'nom' => 'Bas-Rhin',           'region' => 'grand-est',                 'slug' => 'bas-rhin'],
                ['code' => '06', 'nom' => 'Alpes-Maritimes',    'region' => 'provence-alpes-cote-d-azur','slug' => 'alpes-maritimes'],
            ];
            foreach ($footerDepts as $d):
            ?>
            <a href="<?= htmlspecialchars(urlDepartement($d['region'], $d['slug'])) ?>" class="footer-link">
                <?= htmlspecialchars($d['nom']) ?> (<?= $d['code'] ?>)
            </a>
            <?php endforeach; ?>
        </div>

        <!-- Colonne 3 : Régions -->
        <div>
            <p class="footer-col-title">Régions</p>
            <?php
            $footerRegions = [
                ['slug' => 'ile-de-france',             'nom' => 'Île-de-France'],
                ['slug' => 'auvergne-rhone-alpes',      'nom' => 'Auvergne-Rhône-Alpes'],
                ['slug' => 'nouvelle-aquitaine',        'nom' => 'Nouvelle-Aquitaine'],
                ['slug' => 'occitanie',                 'nom' => 'Occitanie'],
                ['slug' => 'hauts-de-france',           'nom' => 'Hauts-de-France'],
                ['slug' => 'bretagne',                  'nom' => 'Bretagne'],
                ['slug' => 'normandie',                 'nom' => 'Normandie'],
                ['slug' => 'provence-alpes-cote-d-azur','nom' => 'PACA'],
            ];
            foreach ($footerRegions as $r):
            ?>
            <a href="<?= htmlspecialchars(urlRegion($r['slug'])) ?>" class="footer-link">
                <?= htmlspecialchars($r['nom']) ?>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- Colonne 4 : Informations -->
        <div>
            <p class="footer-col-title">Informations</p>
            <a href="<?= SITE_URL ?>/contact/" class="footer-link">Contact</a>
            <a href="<?= SITE_URL ?>/mentions-legales/" class="footer-link">Mentions légales</a>
            <a href="<?= SITE_URL ?>/politique-confidentialite/" class="footer-link">Confidentialité</a>
            <a href="<?= SITE_URL ?>/sitemap.xml" class="footer-link">Plan du site</a>
        </div>
    </div>

    <div class="footer-bottom">
        <p class="footer-bottom-text">
            © <?= SITE_YEAR ?> <?= SITE_NAME ?> — Annuaire des chauffagistes en France. Tous droits réservés.
        </p>
        <div class="footer-bottom-links">
            <a href="<?= SITE_URL ?>/mentions-legales/" class="footer-bottom-link">Mentions légales</a>
            <a href="<?= SITE_URL ?>/politique-confidentialite/" class="footer-bottom-link">Confidentialité</a>
            <a href="<?= SITE_URL ?>/contact/" class="footer-bottom-link">Contact</a>
        </div>
    </div>
</footer>

<!-- ── Scroll to top ── -->
<button class="scroll-top"
    x-data="{ show: false }"
    x-init="window.addEventListener('scroll', () => { show = window.scrollY > 400 })"
    x-show="show"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-2"
    x-cloak
    @click="window.scrollTo({top:0,behavior:'smooth'})"
    aria-label="Retour en haut de page">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 15l-6-6-6 6"/></svg>
</button>
</body>
</html>

