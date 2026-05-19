<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';
$title         = 'Politique de confidentialité — ' . SITE_NAME;
$description   = 'Politique de confidentialité et RGPD du site ' . SITE_NAME . '.';
$canonical_url = SITE_URL . '/politique-confidentialite/';
$robots        = 'noindex,follow';
$jsonLd        = [];
$trail = [
    ['name' => 'Accueil',                     'url' => SITE_URL . '/'],
    ['name' => 'Politique de confidentialité', 'url' => $canonical_url],
];
require __DIR__ . '/../templates/header.php';
?>
<main class="max-w-3xl mx-auto px-4 py-12">
    <?php require __DIR__ . '/../components/breadcrumb.php'; ?>

    <h1 style="font-family:var(--font-display);font-size:28px;font-weight:700;color:var(--text);margin-bottom:32px;">
        Politique de confidentialité
    </h1>

    <div style="display:flex;flex-direction:column;gap:28px;font-size:14px;color:var(--text-muted);line-height:1.8;">

        <section style="background:#fff;border:1px solid var(--stone);border-radius:var(--radius);padding:24px;">
            <h2 style="font-weight:700;color:var(--text);font-size:16px;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid var(--stone);">
                Responsable du traitement
            </h2>
            <p>
                Le responsable du traitement des données est la société
                <strong style="color:var(--text);">Miraubolant</strong>, entreprise individuelle.<br>
                SIREN : <strong style="color:var(--text);">995 105 442</strong><br>
                Siège social : 113 rue Saint-Honoré, 75001 Paris<br>
                Email : <a href="mailto:contact@annuaire-isolant.fr" style="color:var(--forest);text-decoration:none;">contact@annuaire-isolant.fr</a>
            </p>
        </section>

        <section style="background:#fff;border:1px solid var(--stone);border-radius:var(--radius);padding:24px;">
            <h2 style="font-weight:700;color:var(--text);font-size:16px;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid var(--stone);">
                Données collectées
            </h2>
            <p>
                <strong style="color:var(--text);">Ce site ne collecte aucune donnée personnelle directement.</strong>
                Nous n'installons pas de formulaire de contact enregistrant vos informations sur nos serveurs.<br><br>
                Les formulaires de demande de devis sont fournis et gérés par notre partenaire
                <strong style="color:var(--text);">ViteUnDevis</strong>, qui agit en tant que responsable de traitement
                indépendant pour ces données. Nous vous invitons à consulter leur politique de confidentialité pour
                connaître les conditions dans lesquelles vos données sont collectées et traitées lorsque vous utilisez
                ces formulaires.
            </p>
        </section>

        <section style="background:#fff;border:1px solid var(--stone);border-radius:var(--radius);padding:24px;">
            <h2 style="font-weight:700;color:var(--text);font-size:16px;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid var(--stone);">
                Cookies et traceurs
            </h2>
            <p>
                Ce site utilise uniquement des cookies techniques strictement nécessaires à son bon fonctionnement
                (navigation, affichage des pages). Ces cookies ne collectent aucune donnée à caractère personnel
                et ne nécessitent pas votre consentement conformément à la recommandation de la CNIL.<br><br>
                Nous n'utilisons pas de cookies publicitaires, de traceurs tiers, ni d'outils d'analyse comportementale
                (Google Analytics, Facebook Pixel, etc.).
            </p>
        </section>

        <section style="background:#fff;border:1px solid var(--stone);border-radius:var(--radius);padding:24px;">
            <h2 style="font-weight:700;color:var(--text);font-size:16px;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid var(--stone);">
                Hébergement des données
            </h2>
            <p>
                Ce site est hébergé par <strong style="color:var(--text);">Hostinger International Ltd</strong>,
                61 Lordou Vironos Street, 6023 Larnaca, Chypre. Les données techniques de navigation
                (adresses IP, logs de connexion) peuvent être conservées par l'hébergeur conformément
                à ses obligations légales.
            </p>
        </section>

        <section style="background:#fff;border:1px solid var(--stone);border-radius:var(--radius);padding:24px;">
            <h2 style="font-weight:700;color:var(--text);font-size:16px;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid var(--stone);">
                Vos droits (RGPD)
            </h2>
            <p>
                Conformément au Règlement (UE) 2016/679 (RGPD) et à la loi Informatique et Libertés modifiée,
                vous disposez des droits suivants sur vos données personnelles :
            </p>
            <ul style="margin-top:10px;padding-left:20px;display:flex;flex-direction:column;gap:4px;">
                <li><strong style="color:var(--text);">Droit d'accès</strong> — obtenir une copie des données vous concernant ;</li>
                <li><strong style="color:var(--text);">Droit de rectification</strong> — faire corriger des données inexactes ;</li>
                <li><strong style="color:var(--text);">Droit à l'effacement</strong> — demander la suppression de vos données ;</li>
                <li><strong style="color:var(--text);">Droit à la portabilité</strong> — recevoir vos données dans un format structuré ;</li>
                <li><strong style="color:var(--text);">Droit d'opposition</strong> — vous opposer à un traitement vous concernant ;</li>
                <li><strong style="color:var(--text);">Droit à la limitation</strong> — demander la suspension d'un traitement.</li>
            </ul>
            <p style="margin-top:12px;">
                Pour exercer ces droits, contactez-nous à :
                <a href="mailto:contact@annuaire-isolant.fr" style="color:var(--forest);text-decoration:none;">contact@annuaire-isolant.fr</a><br>
                Vous pouvez également introduire une réclamation auprès de la
                <strong style="color:var(--text);">CNIL</strong> (<a href="https://www.cnil.fr" target="_blank" rel="noopener" style="color:var(--forest);text-decoration:none;">www.cnil.fr</a>).
            </p>
        </section>

        <section style="background:#fff;border:1px solid var(--stone);border-radius:var(--radius);padding:24px;">
            <h2 style="font-weight:700;color:var(--text);font-size:16px;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid var(--stone);">
                Sécurité
            </h2>
            <p>
                Nous mettons en œuvre des mesures techniques et organisationnelles appropriées pour protéger
                les données traitées dans le cadre de ce site contre tout accès non autorisé, perte ou divulgation.
                Les communications entre votre navigateur et ce site sont chiffrées via le protocole HTTPS.
            </p>
        </section>

        <p style="font-size:12px;color:var(--text-muted);opacity:.6;text-align:right;">
            Dernière mise à jour : <?= date('d/m/Y') ?>
        </p>

    </div>
</main>
<?php require __DIR__ . '/../templates/footer.php'; ?>

