<?php
// $placeholder (string) — texte du placeholder
// $size        (string) — 'sm' | 'lg'
$placeholder ??= 'Entrez le nom de votre ville...';
$size        ??= 'lg';
$inputClass = $size === 'lg'
    ? 'w-full pl-5 pr-12 py-4 text-base border-2 border-gray-300 rounded-2xl focus:outline-none focus:border-blue-500 shadow-sm'
    : 'w-full pl-4 pr-10 py-2.5 text-sm border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500';
?>
<div x-data="searchVille()" class="relative w-full">
    <input
        type="text"
        x-model="query"
        @input.debounce.300ms="search()"
        @keydown.escape="results = []"
        @keydown.arrow-down.prevent="focusNext()"
        @keydown.arrow-up.prevent="focusPrev()"
        @keydown.enter.prevent="selectFocused()"
        placeholder="<?= htmlspecialchars($placeholder) ?>"
        class="<?= $inputClass ?>"
        autocomplete="off"
    >
    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>

    <div x-show="results.length > 0" x-cloak
         class="absolute top-full mt-2 w-full bg-white rounded-xl shadow-xl border border-gray-200 z-50 overflow-hidden">
        <template x-for="(r, i) in results" :key="r.url">
            <a :href="r.url"
               :class="{ 'bg-blue-50': focused === i }"
               @mouseenter="focused = i"
               class="flex items-center justify-between px-5 py-3 hover:bg-blue-50 border-b border-gray-100 last:border-0 transition-colors">
                <span class="font-medium text-gray-800" x-text="r.nom"></span>
                <div class="text-right ml-4">
                    <span class="text-gray-400 text-sm" x-text="r.cp"></span>
                    <br>
                    <span class="text-blue-500 text-xs font-semibold" x-text="r.artisans + ' installateurs'"></span>
                </div>
            </a>
        </template>
    </div>
</div>

<script>
function searchVille() {
    return {
        query: '',
        results: [],
        focused: -1,
        async search() {
            if (this.query.length < 2) { this.results = []; return; }
            const res = await fetch('/api/search?q=' + encodeURIComponent(this.query));
            this.results = await res.json();
            this.focused = -1;
        },
        focusNext() { this.focused = Math.min(this.focused + 1, this.results.length - 1); },
        focusPrev() { this.focused = Math.max(this.focused - 1, 0); },
        selectFocused() {
            if (this.focused >= 0 && this.results[this.focused]) {
                window.location.href = this.results[this.focused].url;
            }
        }
    }
}
</script>
