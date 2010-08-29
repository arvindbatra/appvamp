<?php 

function bloginfo($var)
{
  echo get_bloginfo($var);
}

function add_action($actionName, $actionFunc)
{
	return;

}

function get_categories($var)
{
	return '';
}


/**
 * Retrieve information about the blog.
 *
 * Some show parameter values are deprecated and will be removed in future
 * versions. These options will trigger the _deprecated_argument() function.
 * The deprecated blog info options are listed in the function contents.
 *
 * The possible values for the 'show' parameter are listed below.
 * <ol>
 * <li><strong>url<strong> - Blog URI to homepage.</li>
 * <li><strong>wpurl</strong> - Blog URI path to WordPress.</li>
 * <li><strong>description</strong> - Secondary title</li>
 * </ol>
 *
 * The feed URL options can be retrieved from 'rdf_url' (RSS 0.91),
 * 'rss_url' (RSS 1.0), 'rss2_url' (RSS 2.0), or 'atom_url' (Atom feed). The
 * comment feeds can be retrieved from the 'comments_atom_url' (Atom comment
 * feed) or 'comments_rss2_url' (RSS 2.0 comment feed).
 *
 * @since 0.71
 *
 * @param string $show Blog info to retrieve.
 * @param string $filter How to filter what is retrieved.
 * @return string Mostly string values, might be empty.
 */

function get_bloginfo( $show = '', $filter = 'raw' ) {

	switch( $show ) {
		case 'home' : // DEPRECATED
		case 'siteurl' : // DEPRECATED
			_deprecated_argument( __FUNCTION__, '2.2', sprintf( __('The <code>%s</code> option is deprecated for the family of <code>bloginfo()</code> functions.' ), $show ) . ' ' . sprintf( __( 'Use the <code>%s</code> option instead.' ), 'url'  ) );
		case 'url' :
			$output = get_option('url');
			break;
		case 'wpurl' :
			$output = get_option('wpurl');
			break;
		case 'description':
			$output = get_option('blogdescription');
			break;
		case 'charset':
			$output = get_option('blog_charset');
			if ('' == $output) $output = 'UTF-8';
			break;
		case 'html_type' :
			$output = get_option('html_type');
			break;
		case 'version':
			global $wp_version;
			$output = $wp_version;
			break;
		case 'language':
			$output = get_option();
			$output = str_replace('_', '-', $output);
			break;
		case 'text_direction':
			//_deprecated_argument( __FUNCTION__, '2.2', sprintf( __('The <code>%s</code> option is deprecated for the family of <code>bloginfo()</code> functions.' ), $show ) . ' ' . sprintf( __( 'Use the <code>%s</code> function instead.' ), 'is_rtl()'  ) );
			if ( function_exists( 'is_rtl' ) ) {
				$output = is_rtl() ? 'rtl' : 'ltr';
			} else {
				$output = 'ltr';
			}
			break;
		case 'name':
		default:
			$output = get_option($show);
			break;
	}


	return $output;
}


function add_option($key, $val)
{
	global $apperOptions;
	$apperOptions->$key = $val;
}


function get_option($key)
{
	global $apperOptions;
	if ( $apperOptions->exists($key)) {	
		return $apperOptions->$key;
	}
	return '';
}


function is_active_sidebar($index)
{
	return false;
}


$apperOptions = new Properties();
add_option('template_directory',   'views' . DS . 'themes' . DS . 'selecta');
add_option('stylesheet_url',  'views' . DS . 'themes' . DS . 'selecta' . DS . 'style.css');

$tmpLogger = AppLogger::getInstance()->getLogger();
$tmpLogger->debug(get_option('stylesheet_url'));
