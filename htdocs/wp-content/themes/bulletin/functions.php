<?php
/**
 * Functions.php contains all the core functions for your theme to work properly.
 * Please do not edit this file!!
 *
 * @package Bulletin WordPress Theme
 * @since 1.0
 * @author WPExplorer : http://www.wpexplorer.com
 * @copyright Copyright (c) 2012, WPExplorer
 * @link http://www.wpexplorer.com
 */
 

/**
* Define Constants
*/
define( 'WPEX_FUNCTION', get_template_directory_uri().'/js' );
define( 'WPEX_JS_DIR', get_template_directory_uri().'/js' );
define( 'WPEX_CSS_DIR', get_template_directory_uri().'/css' );

/*
 * Helper function to return the theme option value. If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 * This code allows the theme to work without errors if the Options Framework plugin has been disabled.
 * @since Authentic Corp 1.0
 */
if ( !function_exists( 'of_get_option' ) ) {
	function of_get_option($name, $default = false) {
		$optionsframework_settings = get_option('optionsframework');
		// Gets the unique option id
		$option_name = $optionsframework_settings['id'];
		if ( get_option($option_name) ) {
			$options = get_option($option_name);
		}
		if ( isset($options[$name]) ) {
			return $options[$name];
		} else {
			return $default;
		}
	}
}

/**
* Get functions
* @since 1.0
*/

// TGM Plugin Activation => https://github.com/thomasgriffin/TGM-Plugin-Activation
require_once ( get_template_directory() .'/functions/recommend-plugins.php' );

// Add basic hooks
require_once ( get_template_directory() .'/functions/hooks.php' );

// Load CSS and JS
require_once( get_template_directory() .'/functions/scripts.php' );

// Define widget areas and add custom widgets
require_once( get_template_directory() .'/functions/widgets/widget-areas.php' );
require_once( get_template_directory() .'/functions/widgets/widget-featured-posts.php' );

// Setup some useful functions
if( of_get_option('custom_wp_gallery','1') == 1 ) {
	require_once( get_template_directory() .'/functions/custom-wp-gallery.php' );
}
require_once( get_template_directory() .'/functions/load-more.php' );
require_once( get_template_directory() .'/functions/pagination.php' );
require_once( get_template_directory() .'/functions/page-nav.php' );
require_once( get_template_directory() .'/functions/default-image-sizes.php' );
require_once( get_template_directory() .'/functions/comments-output.php' );

if ( !function_exists('aq_resize') ) {
	require_once( get_template_directory() .'/functions/aqua-resizer.php' );
}

//load these functions only in the admin dashboard
if( defined('WP_ADMIN') && WP_ADMIN ) {
	require_once( get_template_directory() .'/functions/meta/meta-slides.php' ); // Slides meta
	require_once( get_template_directory() .'/functions/meta/meta-post.php' ); // Post meta
}


/**
* Theme Setup
*/

// Add Post Formats Support
add_theme_support( 'post-formats', array( 'video', 'link', 'image', 'gallery' ) );

//localization support
load_theme_textdomain( 'wpex', get_template_directory() .'/lang' );

function wpex_setup() {

	//default width of primary content area
	$content_width = 980;
	
	//theme support
	add_theme_support('automatic-feed-links');
	add_theme_support('custom-background');
	add_theme_support('post-thumbnails');
	
	//register navigation menus
	if ( ! function_exists ( 'wpex_register_nav_menus' ) ) {
		function wpex_register_nav_menus ( ) {
			$wpex_menus = array(
				'primary' => __( 'Primary', 'att' )
			);
			$wpex_menus = apply_filters ( 'wpex_nav_menus', $wpex_menus );
			register_nav_menus ( $wpex_menus );
		}
	}
	wpex_register_nav_menus();

}
add_action( 'after_setup_theme', 'wpex_setup' );


/**
* Change default read more style
* @since 1.0
*/
if ( !function_exists( 'wpex_post_feat_img_caption' ) ) :
	function wpex_post_feat_img_caption() {
	  global $post;
	  $thumbnail_image = get_posts( array( 'p' => get_post_thumbnail_id( get_the_ID() ), 'post_type' => 'attchment') );
	   if ( $thumbnail_image[0]->post_content !== '' ) {
    		echo '<p class="single-portfolio-image-description clearfix">'.$thumbnail_image[0]->post_content.'</p>';
  		}
	}
endif;


/**
* Change default excerpt read more style
* @since 1.0
*/
if ( !function_exists( 'wpex_new_excerpt_more' ) ) :
	function wpex_new_excerpt_more($more) {
		global $post;
		return '...';
	}
	add_filter('excerpt_more', 'wpex_new_excerpt_more');
endif;


/**
* Change excerpt length
* @since 1.0
*/
function wpex_custom_excerpt_length( $length ) {
	return 17;
}
add_filter( 'excerpt_length', 'wpex_custom_excerpt_length', 999 );


/**
* Add home page option to WordPress Menu
* @since 1.0
*/
add_filter( 'wp_page_menu_args', 'home_page_menu_args' );
function home_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}