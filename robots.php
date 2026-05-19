<?php
require_once __DIR__ . '/config.php';
header('Content-Type: text/plain; charset=utf-8');
?>
User-agent: *
Allow: /
Disallow: /api/
Disallow: /*?utm_*
Disallow: /*?utm_source*
Disallow: /*?utm_medium*
Disallow: /*?utm_campaign*
Disallow: /*?page=*
Disallow: /*?fbclid*
Disallow: /*?gclid*

User-agent: AhrefsBot
Crawl-delay: 10

User-agent: SemrushBot
Crawl-delay: 10

User-agent: MJ12bot
Disallow: /

Sitemap: <?= SITE_URL ?>/sitemap.xml
