<?php
//40. Meta Robots
add_action('amp_post_template_head' , 'ampforwp_talking_to_robots');
function ampforwp_talking_to_robots() {

  global $redux_builder_amp;
  $message_to_robots = '<meta name="robots" content="noindex,nofollow"/>';
  $talk_to_robots=false;

   //author arhives  index/noindex
   if( is_author() && !$redux_builder_amp['ampforwp-robots-archive-author-pages'] ) {
    $talk_to_robots = true;
   }

  //date ke archives index/noindex
  if( is_date() && !$redux_builder_amp['ampforwp-robots-archive-date-pages'] ) {
    $talk_to_robots = true;
  }

  //Search pages noindexing by default
  if( is_search() ) {
    $talk_to_robots = true;
  }

  //categorys index/noindex
  if( is_category()  && !$redux_builder_amp['ampforwp-robots-archive-category-pages'] ) {
    $talk_to_robots = true;
  }

  //categorys index/noindex
  if( is_tag() && !$redux_builder_amp['ampforwp-robots-archive-tag-pages'] ) {
    $talk_to_robots = true;
  }

  if( is_archive() || is_home() ) {
    if ( get_query_var( 'paged' ) ) {
          $paged = get_query_var('paged');
      } elseif ( get_query_var( 'page' ) ) {
          $paged = get_query_var('page');
      } else {
          $paged = 1;
      }
      //sitewide archives sub pages index/noindex  ie page 2 onwards
      if( $paged >= 2 && !$redux_builder_amp['ampforwp-robots-archive-sub-pages-sitewide'] ) {
        $talk_to_robots = true;
      }
    }

    if( $talk_to_robots ) {
        echo $message_to_robots;
    }

}

//	25. Yoast meta Support
function ampforwp_custom_yoast_meta(){
  global $redux_builder_amp;
  if ($redux_builder_amp['ampforwp-seo-yoast-meta']) {
    if(! class_exists('YoastSEO_AMP') ) {
        if ( class_exists('WPSEO_Options')) {
          $options = WPSEO_Options::get_option( 'wpseo_social' );
          if ( $options['twitter'] === true ) {
            WPSEO_Twitter::get_instance();
          }
          if ( $options['opengraph'] === true ) {
            $GLOBALS['wpseo_og'] = new WPSEO_OpenGraph;
          }
          do_action( 'wpseo_opengraph' );
        }
    }//execute only if Glue is deactive
    echo strip_tags($redux_builder_amp['ampforwp-seo-custom-additional-meta'], '<link><meta>' );
  } else {
    echo strip_tags($redux_builder_amp['ampforwp-seo-custom-additional-meta'], '<link><meta>' );
  }
}

function ampforwp_custom_yoast_meta_homepage(){
  global $redux_builder_amp;
  if ($redux_builder_amp['ampforwp-seo-yoast-meta']) {
    if(! class_exists('YoastSEO_AMP') ) {
        if ( class_exists('WPSEO_Options')) {
          $options = WPSEO_Options::get_option( 'wpseo_social' );
          if ( $options['twitter'] === true ) {
            WPSEO_Twitter::get_instance();
          }
          if ( $options['opengraph'] === true ) {
            $GLOBALS['wpseo_og'] = new WPSEO_OpenGraph;
          }
        }
        do_action( 'wpseo_opengraph' );

    }//execute only if Glue is deactive
   echo strip_tags($redux_builder_amp['ampforwp-seo-custom-additional-meta'], '<link><meta>' );
  }
}

function ampforwp_add_proper_post_meta(){
  $check_custom_front_page = get_option('show_on_front');
  if ( $check_custom_front_page == 'page' ) {
    add_action( 'amp_post_template_head', 'ampforwp_custom_yoast_meta_homepage' );
    add_filter('wpseo_opengraph_title', 'custom_twitter_title_homepage');
    add_filter('wpseo_twitter_title', 'custom_twitter_title_homepage');
    add_filter('wpseo_opengraph_desc', 'custom_twitter_description_homepage');
    add_filter('wpseo_twitter_description', 'custom_twitter_description_homepage');
    add_filter('wpseo_opengraph_url', 'custom_og_url_homepage');
    add_filter('wpseo_twitter_image', 'custom_og_image_homepage');
    add_filter('wpseo_opengraph_image', 'custom_og_image_homepage');
  } else {
    add_action( 'amp_post_template_head', 'ampforwp_custom_yoast_meta' );
  }
}
add_action('pre_amp_render_post','ampforwp_add_proper_post_meta');


function custom_twitter_title_homepage() {
  return  esc_attr( get_bloginfo( 'name' ) );
}
function custom_twitter_description_homepage() {
  return  esc_attr( get_bloginfo( 'description' ) );
}
function custom_og_url_homepage() {
  return esc_url( get_bloginfo( 'url' ) );
}
function custom_og_image_homepage() {
  if ( class_exists('WPSEO_Options') ) {
    $options = WPSEO_Options::get_option( 'wpseo_social' );
    return  $options['og_default_image'] ;
  }
}

// 29. Remove analytics code if Already added by Glue or Yoast SEO (#370)
add_action('init','remove_analytics_code_if_available',20);
function remove_analytics_code_if_available(){
  if ( class_exists('WPSEO_Options') && class_exists('YoastSEO_AMP') ) {
    $yoast_glue_seo = get_option('wpseo_amp');

    if ( $yoast_glue_seo['analytics-extra'] ) {
      remove_action('amp_post_template_head','ampforwp_register_analytics_script', 20);
      remove_action('amp_post_template_footer','ampforwp_analytics',11);
    }

    if ( class_exists('Yoast_GA_Options') ) {
      $UA = Yoast_GA_Options::instance()->get_tracking_code();
      if ( $UA ) {
        remove_action('amp_post_template_head','ampforwp_register_analytics_script', 20);
        remove_action('amp_post_template_footer','ampforwp_analytics',11);
      }
    }
  }
}