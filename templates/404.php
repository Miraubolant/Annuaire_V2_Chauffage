<?php
http_response_code(404);
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';
$title       = 'Page introuvable — ' . SITE_NAME;
$description = 'La page que vous recherchez n\'existe pas. Trouvez un chauffagiste près de chez vous.';
$robots      = 'noindex,follow';
$canonical_url = SITE_URL . '/';
$jsonLd      = [];
require __DIR__ . '/header.php';
?>

<main class="max-w-2xl mx-auto px-4 py-24 text-center">
    <div class="text-8xl mb-6">🔍</div>
    <h1 class="text-4xl font-bold text-gray-800 mb-4">Page introuvable</h1>
    <p class="text-gray-500 text-lg mb-8">
        Cette page n'existe pas ou a été déplacée.
        Cherchez un chauffagiste dans votre ville ci-dessous.
    </p>

    <!-- Barre de recherche -->
    <div class="max-w-sm mx-auto" x-data="searchVille()">
        <div class="relative">
            <input
                type="text"
                x-model="query"
                @input.debounce.300ms="search()"
                placeholder="Entrez votre ville..."
                class="w-full pl-4 pr-12 py-3 border-2 border-gray-300 rounded-xl text-base focus:outline-none focus:border-blue-500"
            >
            <span class="absolute right-4 top-3 text-gray-400 text-lg">🔍</span>
            <div x-show="results.length > 0" x-cloak
                 class="absolute top-full mt-1 w-full bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                <template x-for="r in results" :key="r.url">
                    <a :href="r.url"
                       class="flex items-center justify-between px-4 py-3 hover:bg-blue-50 text-sm border-b border-gray-100 last:border-0">
                        <span x-text="r.nom + ' (' + r.cp + ')'"></span>
                        <span class="text-gray-400 text-xs" x-text="r.artisans + ' installateurs'"></span>
                    </a>
                </template>
            </div>
        </div>
    </div>

    <a href="<?= SITE_URL ?>/"
       class="inline-block mt-8 text-blue-600 hover:text-blue-800 font-semibold transition-colors">
        ← Retour à l'accueil
    </a>
</main>

<?php require __DIR__ . '/footer.php'; ?>
