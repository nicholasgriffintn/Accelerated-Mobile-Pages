<?php
// Adding AMP-related things to the main theme
	global $redux_builder_amp;
	// 0.9. AMP Design Manager Files
	require AMPFORWP_DESIGN_MANGER_FILE;
	require AMPFORWP_CONTENT_ELEMENTS_FUNCTIONS_FILE;
	require AMPFORWP_LOOP_FUNCTIONS_FILE;
	require AMPFORWP_CUSTOMIZER_FILE;
	// Custom AMP Content
	require AMPFORWP_CUSTOM_AMP_CONTENT_FILE;

//----------------------------------------AMPHTML Functions Start---------------------------
	// 1. Add Home REL canonical
	// Add AMP rel-canonical for home and archive pages
	add_action('amp_init','ampforwp_allow_homepage');
	function ampforwp_allow_homepage() {
		add_action( 'wp', 'ampforwp_add_endpoint_actions' );
	}

	function ampforwp_add_endpoint_actions() {
			$ampforwp_is_amp_endpoint = ampforwp_is_amp_endpoint();
			if ( $ampforwp_is_amp_endpoint ) {
				amp_prepare_render();
			} else {
				add_action( 'wp_head', 'ampforwp_home_archive_rel_canonical' );
			}
	}

	function ampforwp_home_archive_rel_canonical() {
		global $redux_builder_amp;
		global $wp;
		global $post;

	    if( is_attachment() ) {
	        return;
	    }
	    if( is_home() && !$redux_builder_amp['ampforwp-homepage-on-off-support'] ) {
	        return;
	    }
	    if( is_front_page() && ! $redux_builder_amp['ampforwp-homepage-on-off-support'] ) {
	        return;
	    }
	    if ( is_archive() && !$redux_builder_amp['ampforwp-archive-support'] ) {
					return;
		  }
      	if( is_page() && !$redux_builder_amp['amp-on-off-for-all-pages'] ) {
					return;
		  }

	    if ( is_home()  || is_front_page() || is_archive() ){
	        global $wp;
	        $current_archive_url = home_url( $wp->request );
	        $amp_url = trailingslashit( $current_archive_url ).'amp';
	    } else {
	      $amp_url = amp_get_permalink( get_queried_object_id() );
	    }

      $ampforwp_amp_post_on_off_meta = get_post_meta( get_the_ID(),'ampforwp-amp-on-off',true);
      if( $ampforwp_amp_post_on_off_meta === 'hide-amp' ) {
        //dont Echo anything
      } else {
				$supported_types = array('post','page');

				if( ampforwp_is_plugin_active( AMPFORWP_CUSTOM_POST_TYPE_PLUGIN ) ) {
					if ( $redux_builder_amp['ampforwp-custom-type'] ) {
						foreach($redux_builder_amp['ampforwp-custom-type'] as $custom_post){
							$supported_types[] = $custom_post;
						}
					}
				}

				if( ampforwp_is_plugin_active( AMPFORWP_WOO_COMMERCE_PLUGIN ) ) {
					if( !in_array( "product" , $supported_types) ){
						$supported_types[]= 'product';
					}
				}

				$type = get_post_type();
				$supported_amp_post_types = in_array( $type , $supported_types );

				if ( is_home() && $wp->query_vars['paged'] >= '2' ) {
					$new_url =  home_url('/');
					$new_url = $new_url . AMPFORWP_AMP_QUERY_VAR . '/' . $wp->request ;
					$amp_url = $new_url ;
				}

				if ( is_archive() && $wp->query_vars['paged'] >= '2' ) {
					$new_url 		=  home_url('/');
				 	$category_path 	= $wp->request;
				 	$explode_path  	= explode("/",$category_path);
				 	$inserted 		= array(AMPFORWP_AMP_QUERY_VAR);
					array_splice( $explode_path, -2, 0, $inserted );
					$impode_url = implode('/', $explode_path);
					$amp_url = $new_url . $impode_url ;
				}

        if( is_search() ) {
          $current_search_url =trailingslashit(get_home_url())."?amp=1&s=".get_search_query();
	          if ( $wp->query_vars['paged'] >= '2' ) {
	          	$current_search_url =trailingslashit(get_home_url()) . $wp->request .'/'."?amp=1&s=".get_search_query();
	          }
          $amp_url = untrailingslashit($current_search_url);
        }

				if( $supported_amp_post_types) {
					printf( '<link rel="amphtml" href="%s" />', esc_url( $amp_url ) );
				}
  		}
	} //end of ampforwp_home_archive_rel_canonical()
//----------------------------------------AMPHTML Functions End---------------------------


//----------------------------------------AMP code Files Returning Functions Start---------------------------
	require AMPFORWP_FILE_RETURNING_FILE;
//----------------------------------------AMP code Files Returning Functions End---------------------------


//----------------------------------------Ads Functions Start---------------------------
	require AMPFORWP_ADS_FILE;
//----------------------------------------Ads Functions End---------------------------


//----------------------------------------AMP metabox in Editor page Functions Start---------------------------
	require AMPFORWP_METABOX_FILE;
//----------------------------------------AMP metabox in Editor page Functions End---------------------------

//----------------------------------------Misccelenous Feature Functions Start---------------------------
	require AMPFORWP_MISC_FILE;
//----------------------------------------Misccelenous Feature Functions End---------------------------

//----------------------------------------Analytics Functions Start---------------------------
	require AMPFORWP_ANALYTICS_FILE;
//----------------------------------------Analytics Functions End---------------------------


//----------------------------------------Compatibility Functions Start---------------------------
	require AMPFORWP_COMPATIIBLITY_FILE;
//----------------------------------------Compatibility Functions End---------------------------


//----------------------------------------Design-3 Sepecific Functions Start---------------------------
	require AMPFORWP_DESIGN_SPECIFIC_FUNCTIONS;
//----------------------------------------Design-3 Sepecific Functions End---------------------------


//----------------------------------------TItles Functions Start---------------------------
	require AMPFORWP_TITLE_FILE;
//----------------------------------------TItles Functions End---------------------------


//----------------------------------------Widgets output Functions Start--------------------------
	// Code moved from here to widgets.php
	// file and it id required in accelarated-mobile-pages.php file
//----------------------------------------Widgets output Functions Functions End---------------------------


//----------------------------------------SEO Functions Start---------------------------
	require AMPFORWP_SEO_FILE;
//----------------------------------------SEO Functions End---------------------------


//----------------------------------------Structured Data Functions Start---------------------------
	require AMPFORWP_STRUCTURED_DATA_FILE;
//----------------------------------------Structured Data Functions End---------------------------


//----------------------------------------Search Functions Start---------------------------
	require AMPFORWP_SEARCH_FILE;
//----------------------------------------Search Functions End---------------------------


//----------------------------------------Woocommerece ShortCode Functions Start---------------------------
	require AMPFORWP_WOOCOMMERCE_FILE;
//----------------------------------------Woocommerece ShortCode Functions End---------------------------


//----------------------------------------Scripts Functions End---------------------------
	 require AMPFORWP_SCRIPTS_FILE;
//----------------------------------------Scripts Functions End---------------------------
