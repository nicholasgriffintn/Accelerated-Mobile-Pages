<?php
//51. Adding Digg Digg compatibility with AMP
function ampforwp_dd_exclude_from_amp() {
if(ampforwp_is_amp_endpoint()) {
    remove_filter('the_excerpt', 'dd_hook_wp_content');
    remove_filter('the_content', 'dd_hook_wp_content');
	}
}
add_action('template_redirect', 'ampforwp_dd_exclude_from_amp');


//37. compatibility with wp-html-compression
function ampforwp_copat_wp_html_compression() {
	remove_action('template_redirect', 'wp_html_compression_start', -1);
	remove_action('get_header', 'wp_html_compression_start');
}
add_action('amp_init','ampforwp_copat_wp_html_compression');


//34. social share boost compatibility Ticket #387
function social_sharing_removal_code() {
    remove_filter('the_content','ssb_in_content');
}
add_action('amp_init','social_sharing_removal_code', 9);


//33. Google tag manager support added
// Remove any old scripts that have been loaded by other Plugins
add_action('init', 'amp_gtm_remove_analytics_code');
function amp_gtm_remove_analytics_code() {
  global $redux_builder_amp;
  if( $redux_builder_amp['amp-use-gtm-option'] ) {
    remove_action('amp_post_template_footer','ampforwp_analytics',11);
  	remove_action('amp_post_template_head','ampforwp_register_analytics_script', 20);
  } else {
    remove_filter( 'amp_post_template_analytics', 'amp_gtm_add_gtm_support' );
  }
}





//30. TagDiv menu issue removed
add_action('init','ampforwp_remove_tagdiv_mobile_menu');
function ampforwp_remove_tagdiv_mobile_menu() {
	if( class_exists( 'Mobile_Detect' )) {
		remove_action('option_stylesheet', array('td_mobile_theme', 'mobile'));
	}
}


//31. removing scripts added by cleantalk
add_action('amp_init','ampforwp_remove_js_script_cleantalk');
function ampforwp_remove_js_script_cleantalk() {
    remove_action('wp_loaded', 'ct_add_nocache_script', 1);
}


//32. various lazy loading plugins Support
add_filter( 'amp_init', 'ampforwp_lazy_loading_plugins_compatibility' );
function ampforwp_lazy_loading_plugins_compatibility() {

 //WP Rocket
  add_filter( 'do_rocket_lazyload', '__return_false', PHP_INT_MAX );
  add_filter( 'do_rocket_lazyload_iframes', '__return_false', PHP_INT_MAX );
   if ( ! defined( 'DONOTMINIFYCSS' ) ) {
              define( 'DONOTMINIFYCSS', TRUE );
  }
  if ( ! defined( 'DONOTMINIFYJS' ) ) {
      define( 'DONOTMINIFYJS', TRUE );
  }
  // Disable HTTP protocol removing on script, link, img, srcset and form tags.
  remove_filter( 'rocket_buffer', '__rocket_protocol_rewrite', PHP_INT_MAX );
  remove_filter( 'wp_calculate_image_srcset', '__rocket_protocol_rewrite_srcset', PHP_INT_MAX );

  //Lazy Load XT
	global $lazyloadxt;
	remove_filter( 'the_content', array( $lazyloadxt, 'filter_html' ) );
	remove_filter( 'widget_text', array( $lazyloadxt, 'filter_html' ) );
	remove_filter( 'post_thumbnail_html', array( $lazyloadxt, 'filter_html' ) );
	remove_filter( 'get_avatar', array( $lazyloadxt, 'filter_html' ) );

  // Lazy Load
	add_filter( 'lazyload_is_enabled', '__return_false', PHP_INT_MAX );
}


//Removing bj loading for amp
function ampforwp_remove_bj_load() {
 	if ( function_exists( 'ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint() ) {
 		add_filter( 'bjll/enabled', '__return_false' );
 	}
}
add_action( 'bjll/compat', 'ampforwp_remove_bj_load' );