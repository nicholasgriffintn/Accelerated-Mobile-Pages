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


// 27. Clean the Defer issue
	// TODO : Get back to this issue. #407
	function ampforwp_the_content_filter_full( $content_buffer ) {
    $ampforwp_is_amp_endpoint = ampforwp_is_amp_endpoint();
		if ( $ampforwp_is_amp_endpoint ) {
			$content_buffer = preg_replace("/' defer='defer/", "", $content_buffer);
			$content_buffer = preg_replace("/' defer onload='/", "", $content_buffer);
			$content_buffer = preg_replace("/' defer /", "", $content_buffer);
			$content_buffer = preg_replace("/onclick=[^>]*/", "", $content_buffer);
      $content_buffer = preg_replace("/<\\/?thrive_headline(.|\\s)*?>/",'',$content_buffer);
      // Remove Extra styling added by other Themes/ Plugins
     	$content_buffer = preg_replace('/(<style(.*?)>(.*?)<\/style>)<!doctype html>/','<!doctype html>',$content_buffer);
     	$content_buffer = preg_replace('/(<style(.*?)>(.*?)<\/style>)(\/\*)/','$4',$content_buffer);
      $content_buffer = preg_replace("/<\\/?g(.|\\s)*?>/",'',$content_buffer);
      $content_buffer = preg_replace('/(<[^>]+) spellcheck="false"/', '$1', $content_buffer);
      $content_buffer = preg_replace('/(<[^>]+) spellcheck="true"/', '$1', $content_buffer);
			// $content_buffer = preg_replace('/<style type=(.*?)>|\[.*?\]\s\{(.*)\}|<\/style>(?!(<\/noscript>)|(\n<\/head>)|(<noscript>))/','',$content_buffer);
    }
    return $content_buffer;
	}
  ob_start('ampforwp_the_content_filter_full');


  // 23. The analytics tag appears more than once in the document. This will soon be an error
  remove_action( 'amp_post_template_head', 'quads_amp_add_amp_ad_js');


  // 21. Remove Schema data from All In One Schema.org Rich Snippets Plugin
  add_action( 'pre_amp_render_post', 'ampforwp_remove_schema_data' );
  function ampforwp_remove_schema_data() {
  	remove_filter('the_content','display_rich_snippet');
    // Ultimate Social Media PLUS Compatiblity Added
  	remove_filter('the_content','sfsi_plus_beforaftereposts');
  	remove_filter('the_content','sfsi_plus_beforeafterblogposts');

  	// Thrive Content Builder
  	$amp_custom_content_enable = get_post_meta( get_the_ID() , 'ampforwp_custom_content_editor_checkbox', true);
  	if ($amp_custom_content_enable == 'yes') {
  		remove_filter( 'the_content', 'tve_editor_content', 10 );
  	}

  }


  // 16. Remove Unwanted Scripts
  if ( function_exists( 'ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint() ) {
  	add_action( 'wp_enqueue_scripts', 'ampforwp_remove_unwanted_scripts',20 );
  }
  function ampforwp_remove_unwanted_scripts() {
    wp_dequeue_script('jquery');
  }
  // Remove Print Scripts and styles
  function ampforwp_remove_print_scripts() {
  	if ( ampforwp_is_amp_endpoint() ) {

      function ampforwp_remove_all_scripts() {
        global $wp_scripts;
        $wp_scripts->queue = array();
      }
      add_action('wp_print_scripts', 'ampforwp_remove_all_scripts', 100);
      function ampforwp_remove_all_styles() {
        global $wp_styles;
        $wp_styles->queue = array();
      }
      add_action('wp_print_styles', 'ampforwp_remove_all_styles', 100);

  		// Remove Print Emoji for Nextgen Gallery support
  		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  		remove_action( 'wp_print_styles', 'print_emoji_styles' );

  	}
  }
  add_action( 'template_redirect', 'ampforwp_remove_print_scripts' );


  // 19. Remove Canonical tags
  function ampforwp_amp_remove_actions() {
    if ( is_home() || is_front_page() || is_archive() || is_search() ) {
      remove_action( 'amp_post_template_head', 'amp_post_template_add_canonical' );
    }
  }
  add_action( 'amp_post_template_head', 'ampforwp_amp_remove_actions', 9 );


  // 15. Disable New Relic's extra script that its adds in AMP pages.
  add_action( 'amp_post_template_data', 'ampforwp_disable_new_relic_scripts' );
  if ( ! function_exists('ampforwp_disable_new_relic_scripts') ) {
  	function ampforwp_disable_new_relic_scripts( $data ) {
  		if ( ! function_exists( 'newrelic_disable_autorum' ) ) {
  			return $data;
  		}
  		if ( function_exists( 'ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint() ) {
  			newrelic_disable_autorum();
  		}
  		return $data;
  	}
  }



  	// 11. Strip unwanted codes and tags from the_content
  	add_action( 'pre_amp_render_post','ampforwp_strip_invalid_content');
  	function ampforwp_strip_invalid_content() {
  		add_filter( 'the_content', 'ampforwp_the_content_filter', 2 );
  	}
  	function ampforwp_the_content_filter( $content ) {
  		 $content = preg_replace('/property=[^>]*/', '', $content);
  		 $content = preg_replace('/vocab=[^>]*/', '', $content);
  		 $content = preg_replace('/value=[^>]*/', '', $content);
  		 $content = preg_replace('/noshade=[^>]*/', '', $content);
  		 $content = preg_replace('/contenteditable=[^>]*/', '', $content);
  		 $content = preg_replace('/non-refundable=[^>]*/', '', $content);
  		 $content = preg_replace('/security=[^>]*/', '', $content);
  		 $content = preg_replace('/deposit=[^>]*/', '', $content);
  		 $content = preg_replace('/for=[^>]*/', '', $content);
  		 $content = preg_replace('/nowrap="nowrap"/', '', $content);
  		 $content = preg_replace('#<comments-count.*?>(.*?)</comments-count>#i', '', $content);
  		 $content = preg_replace('#<time.*?>(.*?)</time>#i', '', $content);
  		 $content = preg_replace('#<badge.*?>(.*?)</badge>#i', '', $content);
  		 $content = preg_replace('#<plusone.*?>(.*?)</plusone>#i', '', $content);
  		 $content = preg_replace('#<col.*?>#i', '', $content);
  		 $content = preg_replace('#<table.*?>#i', '<table width="100%">', $content);
  		 $content = preg_replace('/href="javascript:void*/', ' ', $content);
  		 $content = preg_replace('/<script[^>]*>.*?<\/script>/i', '', $content);
  		 //for removing attributes within html tags
  		 $content = preg_replace('/(<[^>]+) onclick=".*?"/', '$1', $content);
  		 $content = preg_replace('/(<[^>]+) rel=".*?"/', '$1', $content);
  		 $content = preg_replace('/(<[^>]+) ref=".*?"/', '$1', $content);
  		 $content = preg_replace('/(<[^>]+) date=".*?"/', '$1', $content);
  		 $content = preg_replace('/(<[^>]+) time=".*?"/', '$1', $content);
  		 $content = preg_replace('/(<[^>]+) imap=".*?"/', '$1', $content);
  		 $content = preg_replace('/(<[^>]+) date/', '$1', $content);
  		 $content = preg_replace('/(<[^>]+) spellcheck/', '$1', $content);
  		 $content = preg_replace('/<font(.*?)>(.*?)<\/font>/', '$2', $content);
  		 //removing scripts and rel="nofollow" from Body and from divs
  		 //issue #268
  		 $content = str_replace(' rel="nofollow"',"",$content);
  		 $content = preg_replace('/<script[^>]*>.*?<\/script>/i', '', $content);
  		/// simpy add more elements to simply strip tag but not the content as so
  		/// Array ("p","font");
  		$tags_to_strip = Array("thrive_headline","type","date","time","place","state","city" );
  		foreach ($tags_to_strip as $tag)
  		{
  		   $content = preg_replace("/<\\/?" . $tag . "(.|\\s)*?>/",'',$content);
  		}
  		// regex on steroids from here on
  		 // issue #420
  		 $content = preg_replace("/<div\s(class=.*?)(href=((".'"|'."'".')(.*?)("|'."'".')))\s(width=("|'."'".')(.*?)("|'."'"."))>(.*)<\/div>/i", '<div $1>$11</div>', $content);
  		 $content = preg_replace('/<like\s(.*?)>(.*)<\/like>/i', '', $content);
  		 $content = preg_replace('/<g:plusone\s(.*?)>(.*)<\/g:plusone>/i', '', $content);
  		 $content = preg_replace('/imageanchor="1"/i', '', $content);
  		 $content = preg_replace('/<plusone\s(.*?)>(.*?)<\/plusone>/', '', $content);
  		 // $content = preg_replace('/date=[^>]*/', '', $content);
  		 // $content = preg_replace('/type=[^>]*/', '', $content);
  		 // $content = preg_replace('/time=[^>]*/', '', $content);
  		 // $content = preg_replace('/<img*/', '<amp-img', $content); // Fallback for plugins

  		 /* Removed So Inline style can work
  		 $content = preg_replace('#<style scoped.*?>(.*?)</style>#i', '', $content); */
  		 /* Removed So Inline style can work
  		 $content = preg_replace('/(<[^>]+) style=".*?"/', '$1', $content);
  		 */

  		return $content;
  	}


  	// 11.1 Strip unwanted codes and tags from wp_footer for better compatibility with Plugins
  	if ( ! is_customize_preview() ) {
  		add_action( 'pre_amp_render_post','ampforwp_strip_invalid_content_footer');
  	}
  	function ampforwp_strip_invalid_content_footer() {
  		add_filter( 'wp_footer', 'ampforwp_the_content_filter_footer', 1 );
  	}
  	function ampforwp_the_content_filter_footer( $content ) {
      remove_all_actions('wp_footer');
  		return $content;
  	}

  	// 11.5 Strip unwanted codes the_content of Frontpage
    add_action( 'pre_amp_render_post','ampforwp_strip_invalid_content_frontpage');
  	function ampforwp_strip_invalid_content_frontpage(){
      if ( is_front_page() || is_home() ) {
  			add_filter( 'the_content', 'ampforwp_the_content_filter_frontpage', 20 );
      }
  	}
  	function ampforwp_the_content_filter_frontpage( $content ) {
  		$content = preg_replace('/<img*/', '<amp-img', $content); // Fallback for plugins
  		return $content;
  	}
