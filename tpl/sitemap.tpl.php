<?php
header("Content-type: application/xml; charset=utf-8");
echo '<?xml version="1.0" encoding="utf-8"?>
<?xml-stylesheet type="text/xsl" href="' . APP_URL . '/gss.xsl"?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">
<url><loc>' . APP_URL . '</loc><lastmod>' . $date . '</lastmod><changefreq>daily</changefreq><priority>0.70</priority></url>
<url><loc>' . APP_URL . '?rss</loc><lastmod>' . $date . '</lastmod><changefreq>daily</changefreq><priority>0.25</priority></url>
';

if (iterable($items)) {
	foreach ($items as $item) {
		echo '<url><loc>' . $item->url . '</loc>
		<lastmod>' . $item->date . '</lastmod>
		<changefreq>daily</changefreq>
		<priority>' . $item->priority . '</priority></url>' . ENDL;
	}
}

echo "</urlset>";
