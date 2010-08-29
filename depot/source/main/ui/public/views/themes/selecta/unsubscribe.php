<?php
	global $wpdb, $comment_id, $the_post;
	if(file_exists("../../../wp-blog-header.php")) :
		require_once("../../../wp-blog-header.php");
	elseif(file_exists("../../../../wp-blog-header.php")) :
		require_once("../../../../wp-blog-header.php");
	endif;
	ocmx_unsubscribe($comment_id);
	get_header(); 
?>
	<div id="content">
		<div class="post">
            <div class="container-header-light-normal"><span></span></div>
            <div class="copy clearfix">
                <h1>Thank You</h1>
                <p>You have unsubscribed from <a href="<?php echo get_permalink($the_post->ID); ?>#comments"><?php echo $the_post->post_title; ?></a>.</p>
            </div>
            <div class="container-footer-light-normal"><span></span></div>
        </div>
	</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>