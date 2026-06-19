<?php
// Direct XML response - bypasses Laravel middleware entirely
header('Content-Type: application/xml; charset=UTF-8');
header('Cache-Control: public, max-age=86400');
header('X-Robots-Tag: noindex');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
echo '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";
echo '  <url>' . "\n";
echo '    <loc>https://keshk-portfolio.vercel.app/</loc>' . "\n";
echo '    <lastmod>' . date('Y-m-d') . '</lastmod>' . "\n";
echo '    <changefreq>weekly</changefreq>' . "\n";
echo '    <priority>1.0</priority>' . "\n";
echo '    <image:image>' . "\n";
echo '      <image:loc>https://keshk-portfolio.vercel.app/assets/img/hero-img.png</image:loc>' . "\n";
echo '      <image:title>Mohamed Keshk — Backend Developer Portfolio</image:title>' . "\n";
echo '    </image:image>' . "\n";
echo '    <image:image>' . "\n";
echo '      <image:loc>https://keshk-portfolio.vercel.app/assets/img/profile-img.png</image:loc>' . "\n";
echo '      <image:title>Mohamed Keshk Profile Photo</image:title>' . "\n";
echo '    </image:image>' . "\n";
echo '  </url>' . "\n";
echo '</urlset>';
