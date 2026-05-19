<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';
$title         = 'Contact — ' . SITE_NAME;
$description   = 'Contactez l\'équipe ' . SITE_NAME . '. Toute question sur le référencement, les artisans ou les partenariats.';
$canonical_url = SITE_URL . '/contact/';
$robots        = 'noindex,follow';
$jsonLd        = [];
$trail = [
    ['name' => 'Accueil', 'url' => SITE_URL . '/'],
    ['name' => 'Contact',  'url' => $canonical_url],
];
require __DIR__ . '/../templates/header.php';
?>
<main class="max-w-3xl mx-auto px-4 py-12">
    <?php require __DIR__ . '/../components/breadcrumb.php'; ?>

    <h1 style="font-family:var(--font-display);font-size:28px;font-weight:700;color:var(--text);margin-bottom:32px;">
        Nous contacter
    </h1>

    <div style="display:flex;flex-direction:column;gap:28px;font-size:14px;color:var(--text-muted);line-height:1.8;">

        <section style="background:#fff;border:1px solid var(--stone);border-radius:var(--radius);padding:24px;">
            <h2 style="font-weight:700;color:var(--text);font-size:16px;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid var(--stone);">
                Coordonnées
            </h2>
            <p>
                <strong style="color:var(--text);">Miraubolant</strong><br>
                113 rue Saint-Honoré<br>
                75001 Paris<br><br>
                Email : <a href="mailto:contact@annuaire-isolant.fr" style="color:var(--forest);text-decoration:none;font-weight:600;">contact@annuaire-isolant.fr</a>
            </p>
        </section>

        <section style="background:#fff;border:1px solid var(--stone);border-radius:var(--radius);padding:24px;">
            <h2 style="font-weight:700;color:var(--text);font-size:16px;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid var(--stone);">
                Pour quelle demande ?
            </h2>
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div style="display:flex;gap:12px;align-items:flex-start;">
                    <span style="font-size:18px;line-height:1.4;">🏠</span>
                    <div>
                        <strong style="color:var(--text);">Artisans &amp; référencement</strong><br>
                        Pour signaler une erreur sur une fiche artisan ou demander une correction, écrivez-nous à l'adresse ci-dessus.
                    </div>
                </div>
                <div style="display:flex;gap:12px;align-items:flex-start;">
                    <span style="font-size:18px;line-height:1.4;">📋</span>
                    <div>
                        <strong style="color:var(--text);">Demande de devis</strong><br>
                        Utilisez directement le formulaire disponible sur la page de votre ville pour obtenir des devis gratuits et sans engagement.
                    </div>
                </div>
                <div style="display:flex;gap:12px;align-items:flex-start;">
                    <span style="font-size:18px;line-height:1.4;">🤝</span>
                    <div>
                        <strong style="color:var(--text);">Partenariats</strong><br>
                        Pour toute proposition commerciale ou partenariat, contactez-nous par email.
                    </div>
                </div>
                <div style="display:flex;gap:12px;align-items:flex-start;">
                    <span style="font-size:18px;line-height:1.4;">⚖️</span>
                    <div>
                        <strong style="color:var(--text);">Données personnelles (RGPD)</strong><br>
                        Pour exercer vos droits d'accès, rectification ou suppression, adressez votre demande à <a href="mailto:contact@annuaire-isolant.fr" style="color:var(--forest);text-decoration:none;">contact@annuaire-isolant.fr</a>.
                    </div>
                </div>
            </div>
        </section>

    </div>
</main>
<?php require __DIR__ . '/../templates/footer.php'; ?>

