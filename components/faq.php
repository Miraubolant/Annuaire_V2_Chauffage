<?php
// $questions (array) — [{q: '...', r: '...'}]
// $title     (string) — titre optionnel de la section
$title     ??= 'Questions fréquentes';
$questions ??= FAQ_ACCUEIL;
if (empty($questions)) return;
?>
<section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-5">❓ <?= htmlspecialchars($title) ?></h2>

    <div class="space-y-2" x-data="{ open: null }">
        <?php foreach ($questions as $i => $qa): ?>
        <div class="border border-gray-100 rounded-xl overflow-hidden">
            <button
                @click="open = open === <?= $i ?> ? null : <?= $i ?>"
                class="w-full flex items-center justify-between px-5 py-4 text-left font-semibold text-gray-800 hover:bg-gray-50 transition-colors text-sm"
                :aria-expanded="open === <?= $i ?>"
            >
                <span><?= htmlspecialchars($qa['q']) ?></span>
                <span class="ml-3 flex-shrink-0 text-blue-500 transition-transform"
                      :class="{ 'rotate-180': open === <?= $i ?> }">▾</span>
            </button>
            <div x-show="open === <?= $i ?>"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-cloak
                 class="px-5 pb-4 text-sm text-gray-600 leading-relaxed border-t border-gray-50">
                <p class="pt-3"><?= htmlspecialchars($qa['r']) ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
