<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<?php
//if(is_single()) :
//	global $wpdb;
//	setup_postdata($post);
//	echo "\n<meta name=\"keywords\" content=\"".fetch_post_tags($post->ID)."\" />";
//	echo "\n<meta name=\"description\" content=\"".str_replace("\n", "", $post->post_excerpt)."\" />";
//endif; ?>

<title><?php echo $title; ?></title>
<!--title><?php //bloginfo('name'); ?><?php //wp_title(" - ", true, "left"); ?></title-->
<link href="<?php bloginfo('stylesheet_url'); ?>" rel="stylesheet" type="text/css" />
<?php if(isset($_GET["use_colour"])) :?>
	<link href="<?php bloginfo('template_directory'); ?>/color-styles/<?php echo $_GET["use_colour"]; ?>/style.css" rel="stylesheet" type="text/css" />
<?php elseif(isset($_COOKIE["ocmx_theme_style"])) :?>
	<link href="<?php bloginfo('template_directory'); ?>/color-styles/<?php echo $_COOKIE["ocmx_theme_style"]; ?>/style.css" rel="stylesheet" type="text/css" />
<?php elseif(get_option("ocmx_theme_style") !== "") : ?>
	<link href="<?php bloginfo('template_directory'); ?>/color-styles/<?php echo get_option("ocmx_theme_style"); ?>/style.css" rel="stylesheet" type="text/css" />
<?php endif; ?>

<?php if(get_option("ocmx_rss_url")) : ?>
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?>" href="<?php echo get_option("ocmx_rss_url"); ?>">
<?php else : ?>
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?>" href="<?php bloginfo("rss2_url"); ?>">
<?php endif; ?>

<script src="scripts/jquery.js" type="text/javascript" language="javascript1.5"></script>
<script src="<?php bloginfo('template_directory'); ?>/scripts/selecta_jquery.js" type="text/javascript" language="javascript1.5"></script>



<?php //wp_head(); ?>
</head>
<body>
<!--[if lt IE 7]>
	<div class="no_ie">
		<h1>Please note, your browser is out of date!</h1>
		<p class="error">We highly recommend that you upgrade your browser. Our suggestion: <a href="http://www.getfirefox.com">Mozilla Firefox</a>.</p>
	</div>
<![endif]-->
<div id="header-container">
	<div class="container-header-dark-normal"><span></span></div>
	<div id="header" class="clearfix">
		<h1 class="logo">
			<?php if(get_option("ocmx_custom_logo")) : ?>
                <a href="<?php bloginfo('url'); ?>" class="logo"><img src="<?php echo get_bloginfo("wpurl"); ?>/wp-content/uploads/<?php echo get_option("ocmx_custom_logo"); ?>" alt="<?php bloginfo('name'); ?>" /></a>
            <?php else : ?>
                <a href="<?php bloginfo('url'); ?>" class="logo"><img src="<?php bloginfo('template_directory'); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>" /></a>
            <?php endif; ?>
		</h1>
        <ul id="menu">
            <li class="parent-item"><a class="parent-link" href="<?php bloginfo('url'); ?>">Home</a></li> 
			<li class="parent-item"><a class="parent-link" href="<?php bloginfo('url'); ?>/archives"><span>Archives</span></a></li>
            <li class="parent-item"><a class="parent-link" href="<?php bloginfo('url'); ?>/about"><span>About</span></a></li>
            <li class="parent-item"><a class="parent-link" href="<?php bloginfo('url'); ?>/contact"><span>Reach out and touch me</span></a></li>
            <li class="parent-item"><a class="parent-link" href="<?php bloginfo('url'); ?>/team"><span>The team</span></a></li>
            <!--?php ocmx_menu(); ?-->
        </ul>
	</div>
	<div class="container-footer-dark-dark"><span></span></div>
</div>
<div id="content-container">
