<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';
$title         = 'Mentions légales — ' . SITE_NAME;
$description   = 'Mentions légales du site ' . SITE_NAME . '.';
$canonical_url = SITE_URL . '/mentions-legales/';
$robots        = 'noindex,follow';
$jsonLd        = [];
$trail = [
    ['name' => 'Accueil',          'url' => SITE_URL . '/'],
    ['name' => 'Mentions légales', 'url' => $canonical_url],
];
require __DIR__ . '/../templates/header.php';
?>
<main class="max-w-3xl mx-auto px-4 py-12">
    <?php require __DIR__ . '/../components/breadcrumb.php'; ?>

    <h1 style="font-family:var(--font-display);font-size:28px;font-weight:700;color:var(--text);margin-bottom:32px;">
        Mentions légales
    </h1>

    <div style="display:flex;flex-direction:column;gap:28px;font-size:14px;color:var(--text-muted);line-height:1.8;">

        <section style="background:#fff;border:1px solid var(--stone);border-radius:var(--radius);padding:24px;">
            <h2 style="font-weight:700;color:var(--text);font-size:16px;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid var(--stone);">
                Éditeur du site
            </h2>
            <p>
                <strong style="color:var(--text);"><?= SITE_NAME ?></strong> est édité par la société
                <strong style="color:var(--text);">Miraubolant</strong>, entreprise individuelle.<br>
                SIREN : <strong style="color:var(--text);">995 105 442</strong><br>
                Siège social : 113 rue Saint-Honoré, 75001 Paris<br>
                Email : <a href="mailto:contact@annuaire-isolant.fr" style="color:var(--forest);text-decoration:none;">contact@annuaire-isolant.fr</a>
            </p>
        </section>

        <section style="background:#fff;border:1px solid var(--stone);border-radius:var(--radius);padding:24px;">
            <h2 style="font-weight:700;color:var(--text);font-size:16px;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid var(--stone);">
                Hébergeur
            </h2>
            <p>
                <strong style="color:var(--text);">Hostinger International Ltd</strong><br>
                61 Lordou Vironos Street, 6023 Larnaca, Chypre<br>
                <a href="https://www.hostinger.fr" target="_blank" rel="noopener" style="color:var(--forest);text-decoration:none;">www.hostinger.fr</a>
            </p>
        </section>

        <section style="background:#fff;border:1px solid var(--stone);border-radius:var(--radius);padding:24px;">
            <h2 style="font-weight:700;color:var(--text);font-size:16px;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid var(--stone);">
                Propriété intellectuelle
            </h2>
            <p>
                L'ensemble des contenus présents sur ce site (textes, graphismes, structure) sont protégés par le droit d'auteur.
                Toute reproduction, représentation ou diffusion, totale ou partielle, sans autorisation écrite préalable de
                Miraubolant est interdite et constitue une contrefaçon sanctionnée par le Code de la propriété intellectuelle.
            </p>
        </section>

        <section style="background:#fff;border:1px solid var(--stone);border-radius:var(--radius);padding:24px;">
            <h2 style="font-weight:700;color:var(--text);font-size:16px;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid var(--stone);">
                Données personnelles & RGPD
            </h2>
            <p>
                Ce site ne collecte aucune donnée personnelle directement.
                Les formulaires de devis sont gérés par notre partenaire ViteUnDevis, soumis à leur propre politique de confidentialité.<br><br>
                Conformément au Règlement (UE) 2016/679 (RGPD), vous disposez d'un droit d'accès, de rectification,
                d'effacement et de portabilité de vos données. Pour exercer ces droits, contactez :
                <a href="mailto:contact@annuaire-isolant.fr" style="color:var(--forest);text-decoration:none;">contact@annuaire-isolant.fr</a>
            </p>
        </section>

        <section style="background:#fff;border:1px solid var(--stone);border-radius:var(--radius);padding:24px;">
            <h2 style="font-weight:700;color:var(--text);font-size:16px;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid var(--stone);">
                Responsabilité
            </h2>
            <p>
                Les informations présentes sur ce site sont fournies à titre indicatif. Miraubolant ne saurait être tenu
                responsable des erreurs ou omissions dans les fiches des artisans référencés, ni des dommages résultant
                d'une utilisation du site. Les liens vers des sites tiers sont fournis à titre informatif ; leur contenu
                n'engage pas la responsabilité de l'éditeur.
            </p>
        </section>

        <p style="font-size:12px;color:var(--text-muted);opacity:.6;text-align:right;">
            Dernière mise à jour : <?= date('d/m/Y') ?>
        </p>

    </div>
</main>
<?php require __DIR__ . '/../templates/footer.php'; ?>

