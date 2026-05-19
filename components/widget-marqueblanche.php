<?php
// Widget ViteUnDevis marqueblanche — texte basé sur le titre de la page
// Pas de variables requises
?>
<section style="background:var(--white);border:1px solid var(--stone);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);">
    <div style="background:var(--forest);padding:18px 24px;display:flex;align-items:center;gap:10px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.8)" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        <span style="font-weight:700;color:#fff;font-size:14px;">Demandez votre devis gratuit</span>
        <span style="margin-left:auto;font-size:11px;color:rgba(255,255,255,.5);font-weight:500;">Sans engagement · 24h</span>
    </div>

    <!-- Container widget — fond blanc explicite pour éviter héritage texte blanc -->
    <div style="background:#ffffff;color:#1a1a1a;padding:16px;">
        <div id="vdfd271c8d2d"></div>
        <script>
            vud_partenaire_id = '<?= VUD_PARTENAIRE_ID ?>';
            vud_keyword = document.title;
            vud_keyword = encodeURI(vud_keyword);
            vud_box_id = 'dfd271c8d2';
            var vud_js = document.createElement('script');
            vud_js.type = 'text/javascript';
            vud_js.src = '//www.viteundevis.com/marqueblanche/?b=' + vud_box_id + '&p=' + vud_partenaire_id + '&c=' + vud_keyword;
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(vud_js, s);
        </script>
    </div>

    <div style="padding:12px 24px;border-top:1px solid var(--stone);background:var(--cream);">
        <a href="<?= VUD_DEVIS_URL ?>"
           style="display:flex;align-items:center;justify-content:center;gap:6px;background:var(--gold);color:#fff;font-weight:700;font-size:14px;padding:12px 20px;border-radius:100px;text-decoration:none;box-shadow:0 3px 12px rgba(200,150,62,.35);"
           target="_blank" rel="noopener sponsored">
            Obtenir mon devis gratuit
            <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
        </a>
    </div>
</section>
