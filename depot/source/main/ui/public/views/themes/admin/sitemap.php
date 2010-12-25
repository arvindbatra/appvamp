Sitemap data:

<div class="shaded-box">
<xml id="smap">
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" >

<url>
		<loc>http://appvamp.com/</loc>
		<changefreq>daily</changefreq>
		<priority>1.0</priority>
</url>

<?php foreach($previousPostsArr as $previousPost) { 
	$mdate = $previousPost->onDate;
	echo "\n<url> <loc> \n";
	echo "http://appvamp.com/app/" .  date('Y/m/d', strtotime($mdate)) .'/'.  get_seo_string($previousPost->appInfo->appName); 
	echo "\n</loc> </url>";
 } ?>

</urlset>
</xml>
</div>
