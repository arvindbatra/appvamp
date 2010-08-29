<?php
/**
 * @package WordPress
 * @subpackage Selecta
 */

function fetch_post_image($use_id, $width, $height)
	{
		$attach_args = array("post_type" => "attachment", "post_parent" => $use_id);
		$attachments = get_posts($attach_args);
		$attach_id = $attachments[0]->ID;
		return  wp_get_attachment_image($attach_id, array($width, $height));
	}
function ocmx_pagination()
	{
		global $wp_query;
		$request = $wp_query->request;
		$showpages = 6;
		$numposts = $wp_query->found_posts;
		$pagenum = (ceil($numposts/get_option("ocmx_home_page_posts")));
		$currentpage = get_query_var('paged');
		if($currentpage >= $showpages) :
			$startrow = ($currentpage-1);
			$maxpages = ($startrow + $showpages - 1);
			if($maxpages > $pagenum) :
				$startrow = ($startrow - ($maxpages - $pagenum));
				$maxpages = ($maxpages - ($maxpages - $pagenum));
			endif;
		else :
			$startrow = 1;
			$maxpages  = $showpages;
		endif;
			
		if((get_option("ocmx_home_page_posts") && $numposts !== 0) && $numposts > get_option("ocmx_home_page_posts")) :
?>
     <div class="pagination-container">
        <ul class="page_button_content clearfix">
        	<?php if($currentpage !== 0) : ?>
	            <li class="previous-page"><?php previous_posts_link("Previous"); ?></li>
            <?php endif;
			
			if($startrow !== 1) : ?>
				<li><a href="<?php echo clean_url(get_pagenum_link(1)); ?>" class="other-page">1</a></li>  
			<?php endif;
			
			for($i = $startrow; $i <= $maxpages; $i++) : ?>
				<li><a href="<?php echo clean_url(get_pagenum_link($i)); ?>" class="<?php if($i == $currentpage || ($i == 1 && $currentpage == "")) :?>selected-page<?php else : ?>other-page<?php endif; ?>"><?php echo $i; ?></a></li>  
			<?php endfor;
			
			if($maxpages < $pagenum) : ?>
				<li><a href="<?php echo clean_url(get_pagenum_link($pagenum)); ?>" class="other-page"><?php echo $pagenum; ?></a></li>
			<?php endif; 
			
			if($currentpage !== ceil($numposts/get_option("ocmx_home_page_posts"))) : ?>
				<li class="next-page"><?php next_posts_link("Next"); ?></li>
			<?php endif; ?>
        </ul>
    </div>
<?php
		endif;
	}
function ocmx_set_colour()
	{setcookie("ocmx_theme_style", $_GET["use_colour"], 0, COOKIEPATH, COOKIE_DOMAIN);}
function fetch_post_tags($post_id)
	{
		global $wpdb;
		$tags = $wpdb->get_results("SELECT $wpdb->term_relationships.*, $wpdb->terms.* FROM $wpdb->terms INNER JOIN $wpdb->term_relationships ON $wpdb->term_relationships.term_taxonomy_id = $wpdb->terms.term_id WHERE $wpdb->term_relationships.object_id = ".$post_id);
		foreach($tags as $posttag) :
			if(!isset($tag_list)) :
				$tag_list = $posttag->name;
			else :
				$tag_list .= ", ".$posttag->name;
			endif;
		endforeach;
		return $tag_list;
	}
 // VARIABLES
global $themename, $input_prefix;
$themename = "Selecta";
$input_prefix = "ocmx_";

$template_path = get_bloginfo('template_directory');
$functions_path = TEMPLATEPATH . '/functions/';

//CREATE THEME OPTIONS

/*
include_once (TEMPLATEPATH."/ocmx/custom.php");
include_once (TEMPLATEPATH."/ocmx/ocmx-setup.php");
include_once (TEMPLATEPATH."/ocmx/ocmx-create-options.php");
include_once (TEMPLATEPATH."/ocmx/ocmx-functions.php");
include_once (TEMPLATEPATH."/ocmx/ocmx-install-options.php");
include_once (TEMPLATEPATH."/ocmx/ocmx-general-options.php");
include_once (TEMPLATEPATH."/ocmx/ocmx-advert-options.php");
include_once (TEMPLATEPATH."/ocmx/ocmx-comment-options.php");
include_once (TEMPLATEPATH."/ocmx/ocmx-comment-functions.php");
include_once (TEMPLATEPATH."/ocmx/ocmx-widgets.php");
*/
function ocmx_add_admin() {
	global $wpdb;
	
		
	if(function_exists(add_object_page)) :
		add_object_page("OCMX Options", "OCMX Options", 'edit_themes', basename(__FILE__), '', 'http://obox-design.com/images/ocmx-favicon.png');
	else :
		add_menu_page("OCMX Options", "OCMX Options", 'edit_themes', basename(__FILE__), '', 'http://obox-design.com/images/ocmx-favicon.png');
	endif;
	
	$comment_table = $wpdb->prefix ."ocmx_comment_meta";
	$gallery_hdr_table = $wpdb->prefix . "ocmx_gallery";
	if(check_table_existance($comment_table)) :
		add_submenu_page(basename(__FILE__), "General Options", "General & Layout", 8, basename(__FILE__), 'ocmx_general_options');
		add_submenu_page(basename(__FILE__), "Adverts", "Adverts", 8,  "ocmx-adverts", 'ocmx_advert_options');
		add_submenu_page(basename(__FILE__), "Comments", "Comments", 8, "ocmx-comments", 'ocmx_comment_options');
	else :
		add_option("white-red");
		add_submenu_page(basename(__FILE__), "Install Options", "Install", 8, basename(__FILE__), 'ocmx_install_options');
	endif;
};
// Add the Custom Functions to Wordpress
if(isset($_GET["install_ocmx"])) : 
	add_action('init', 'install_ocmx');
endif;
if(isset($_GET["use_colour"])) :
	add_action('init', 'ocmx_set_colour');
endif;

add_action('admin_menu', 'ocmx_add_admin');
add_action('comment_post', create_function('$a', 'ocmx_commentmeta_update($a);'));

if(!get_option("ocmx_theme_style")) :
	add_option("ocmx_theme_style", "blue");
endif;

//wp_enqueue_script( "jquery");
//wp_enqueue_script( "selecta-jquery", get_bloginfo("template_directory")."/scripts/selecta_jquery.js", array( "jquery" ) );

// Create Dynamic Sidebars
if (function_exists('register_sidebar')) :
    register_sidebar(array("name" => "Header Panel"));
    register_sidebar(array("name" => "Index Header Panel"));	
    register_sidebar(array("name" => "Advert Sidebar"));
	
    register_sidebar(
		array(
			  	"name" => "Sidebar",
				"before_widget" => "
					<li class=\"widget widget_links\">
					<div class=\"container-header-dark-normal\"><span></span></div>
						<div class=\"container-dark\">",
				"before_title" => "
							<h2 class=\"recent-comments-title\">",
				"after_title" => "
							</h2>
							<ul class=\"xoxo blogroll\">",
				"after_widget" => "
							</ul>
						</div>
						<div class=\"container-footer-dark-normal\"><span></span></div>
				</li>"
			)
	);
    register_sidebar(array("name" => "Footer Left", "before_title" => "<h3>", "after_title" => "</h3>", "before_widget" => "<ul><li>", "after_widget" => "</li></ul>"));
    register_sidebar(array("name" => "Footer Middle", "before_title" => "<h3>", "after_title" => "</h3>", "before_widget" => "<ul><li>", "after_widget" => "</li></ul>"));
    register_sidebar(array("name" => "Footer Right", "before_title" => "<h3>", "after_title" => "</h3>", "before_widget" => "<ul><li>", "after_widget" => "</li></ul>"));
endif;
// Widgets
function ocmx_page_menu()
	{
		if(get_option("ocmx_page_order") !== "post_title") :
			$page_args = array("sort_column" => get_option("ocmx_page_order"), "sort_order" => get_option("ocmx_page_updown"), "depth" => "1");
		else :
			$page_args = array("sort_order" => get_option("ocmx_page_updown"), "depth" => "1");
		endif;
		$fetch_pages = get_pages($page_args);
		foreach ($fetch_pages as $this_page) :
			$this_option = "ocmx_menu_page_".$this_page->ID;
			if(get_option($this_option)) :
				$sub_page_count = 0;
				if(get_option("ocmx_page_order") !== "post_title") :
					$sub_page_defaults = array("child_of" => $this_page->ID, "sort_column" => get_option("ocmx_page_order"), "sort_order" => get_option("ocmx_page_updown"));
				else :
					$sub_page_defaults = array("child_of" => $this_page->ID, "sort_order" => get_option("ocmx_page_updown"));
				endif;
				$sub_pages = get_pages($sub_page_defaults);
				foreach ($sub_pages as $sub_page) :
					$this_sub_page_option = "ocmx_subpage_".$sub_page->ID;
					if(get_option($this_sub_page_option)) :
						$sub_page_count++;
					endif;
				endforeach; 
?>
				<li class="parent-item">
                	<a href="<?php echo get_page_link($this_page->ID); ?>" id="main-menu-page-item-<?php echo $this_page->ID; ?>" class="parent-link"><span><?php echo $this_page->post_title; ?></span></a>
					<?php if($sub_page_count !== 0) : ?>
                        <div class="sub-menu-container" id="sub-page-menu-<?php echo $this_page->ID; ?>" style="display: none;">
                            <ul class="sub-menu">
                                <?php
                                    foreach ($sub_pages as $sub_page) :
                                        $this_sub_page_option = "ocmx_subpage_".$sub_page->ID;
                                        if(get_option($this_sub_page_option)) :
                                ?>
                                    <li><a href="<?php echo get_page_link($sub_page->ID); ?>"><?php echo $sub_page->post_title; ?></a></li>
                                <?php
                                        endif;
                                    endforeach;
                                ?>
                            </ul>      
							<div class="sub-menu-footer"></div>   
                        </div>         
                    <?php endif; ?>
				</li>
<?php
			endif;
		endforeach;
		$parent_count = 0;

	}
function ocmx_category_menu()
	{
        $defaults = array("type" => "post", "child_of" => 0, "orderby" => get_option("ocmx_category_order"), "order" => get_option("ocmx_category_updown"), "hide_empty" => false);
		$parent_categories = get_categories($defaults);
		// Count the Parent Categories (That is Categories without Parents themselves (To be used in the loop, explained below)
		foreach ($parent_categories as $this_category) :
			$this_option = "ocmx_maincategory_".$this_category->cat_ID;				
			if(get_option($this_option)) :
				$sub_category_count = 0;
				$sub_category_defaults = array('type' => 'post', 'child_of' => $this_category->cat_ID, 'orderby' => 'name', 'order' => 'ASC', 'hide_empty' => false);
				$sub_categories = get_categories($sub_category_defaults);
				// Below will loop through the sub categories and populate the sub_category_count if there is an option selected for the category
				foreach ($sub_categories as $sub_category) :
					$this_sub_option = "ocmx_subcategory_".$sub_category->cat_ID;
					if(get_option($this_sub_option)) :
						$sub_category_count++;
					endif;
				endforeach; ?>
				<li class="parent-item">
					<a href="<?php echo get_category_link($this_category->term_id); ?>" class="parent-link" id="main-menu-item-<?php echo $this_category->cat_ID; ?>">
						<span>
							<?php echo $this_category->cat_name; ?>
                        </span>
					</a>
<?php
				if($sub_category_count !== 0) :
?>
					<div class="sub-menu-container" id="sub-menu-<?php echo $this_category->cat_ID; ?>" style="display: none;">
						<ul class="sub-menu">
<?php
							foreach ($sub_categories as $sub_category) :
								$this_sub_option = "ocmx_subcategory_".$sub_category->cat_ID;
								if(get_option($this_sub_option)) :
?>
									<li><a href="<?php echo get_category_link($sub_category->term_id); ?>"><?php echo $sub_category->cat_name; ?></a></li>
<?php
								endif;
							endforeach;
?>
						</ul>
                        <div class="sub-menu-footer"></div>
					</div>       
<?php 
				endif;
?>
				</li>         
<?php
			endif;
		endforeach;
	}
function ocmx_menu()
	{
		if(get_option("ocmx_page_category_order") == "page_first") :
//			ocmx_page_menu();
//			ocmx_category_menu();
		else :
//			ocmx_category_menu();
//			ocmx_page_menu();
		endif;
	}
?>
