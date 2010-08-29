</div>

<div id="footer-container">
	<div id="footer">
		<div class="container-header-dark-dark"><span></span></div>
		<div class="container-dark">
			<ul class="footer-columns clearfix">  
				<?php if(is_active_sidebar(5)) : ?>
                	<li class="column-one">
	                    <?php if (function_exists('dynamic_sidebar') && dynamic_sidebar(5) );  ?>
                    </li>
                <?php endif; ?>
				<?php if(is_active_sidebar(6)) : ?>
	            	<li class="column-two">
	                    <?php if (function_exists('dynamic_sidebar') && dynamic_sidebar(6) );  ?>
                    </li>
                <?php endif; ?>
				<?php if(is_active_sidebar(7)) : ?>
	            	<li class="column-three">
	                    <?php if (function_exists('dynamic_sidebar') && dynamic_sidebar(7) );  ?>
                    </li>
                <?php endif; ?>
			</ul>
		</div>
		<div class="container-footer-dark-dark"><span></span></div>
		<div class="footer-copy clearfix">
			<p class="copyright"> Copyright &copy; 2009. Powered by <a href="http://www.wordpress.com/" rel="nofollow">Wordpress</a>, Enhanced with <a href="http://www.obox-design.com/ocmx-live.cfm" target="_blank">OCMX-Live</a>.</p>
			<a href="http://www.obox-design.com/premium-wordpress-themes.cfm" title="Premium Wordpress Themes created by Obox Design" class="obox-logo">Obox Signature Series</a>
		</div>
	</div>
</div>
<div id="template-directory" class="no_display"><?php echo bloginfo("template_directory"); ?></div>


<?php wp_footer(); ?>
<?php 
	if(get_option("ocmx_googleAnalytics")) :
		echo stripslashes(get_option("ocmx_googleAnalytics"));
	endif;
?>

</body>
</html>

