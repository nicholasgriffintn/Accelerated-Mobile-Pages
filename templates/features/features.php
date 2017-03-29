<?php
// This file will contains list of all FEATURES.
	require AMPFORWP_FEATURES_LIST_FILE;
// Adding AMP-related things to the main theme
	global $redux_builder_amp;
	// 0.9. AMP Design Manager Files
	require AMPFORWP_DESIGN_MANGER_FILE;
	require AMPFORWP_CONTENT_ELEMENTS_FUNCTIONS_FILE;
	require AMPFORWP_LOOP_FUNCTIONS_FILE;
	require AMPFORWP_CUSTOMIZER_FILE;
	// Custom AMP Content
	require AMPFORWP_CUSTOM_AMP_CONTENT_FILE;


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


	//----------------------------------------AMP code Files Returning Functions Start---------------------------
	// 2. Custom Design
	// Add Homepage AMP file code
	add_filter( 'amp_post_template_file', 'ampforwp_custom_template', 10, 3 );
	function ampforwp_custom_template( $file, $type, $post ) {
   	// Custom Homepage and Archive file
      global $redux_builder_amp;
      // Homepage and FrontPage
      if($redux_builder_amp['amp-frontpage-select-option'] == 0)  {
          if ( is_home() ) {
              if ( 'single' === $type ) {
              	$file = AMPFORWP_DESIGN_SPECIFIC_INDEX_FILE ;
              }
          }
      } elseif ($redux_builder_amp['amp-frontpage-select-option'] == 1) {
          if ( is_home() ) {
              if ( 'single' === $type ) {
                $file = AMPFORWP_DESIGN_SPECIFIC_FRONTPAGE_FILE ;
              }
          }
      }

      // Archive Pages
      if ( is_archive() && $redux_builder_amp['ampforwp-archive-support'] )  {
          $file = AMPFORWP_DESIGN_SPECIFIC_ARCHIVE_FILE;
      }

			// Search pages
    	if ( is_search() &&
					( $redux_builder_amp['amp-design-1-search-feature'] ||
					  $redux_builder_amp['amp-design-2-search-feature'] ||
						$redux_builder_amp['amp-design-3-search-feature'] )
					)  {
          $file = AMPFORWP_DESIGN_SPECIFIC_SEARCH_FILE ;
      }

		  // Custom Single file
	    if ( is_single() || is_page() ) {
				if('single' === $type && !('product' === $post->post_type )) {
				 	$file = AMPFORWP_DESIGN_SPECIFIC_SINGLE_FILE;
			 	}
			}

			if( ( ampforwp_design_selector() == 3 ) && ( is_home() || is_archive() || is_search() )) {
				$file = AMPFORWP_INDEX_FILE ;
			}

	    if ( $redux_builder_amp['amp-frontpage-select-option'] && is_front_page() && ampforwp_design_selector() == 3) {
			 	$file = AMPFORWP_SINGLE_FILE;
		  }

	    return $file;
	}


	// 3. Custom Style files
	add_filter( 'amp_post_template_file', 'ampforwp_set_custom_style', 10, 3 );
	function ampforwp_set_custom_style( $file, $type, $post ) {
		if ( 'style' === $type ) {
			$file = '';
		}
		return $file;
	}

	//3.5
	add_filter( 'amp_post_template_file', 'ampforwp_empty_filter', 10, 3 );
	function ampforwp_empty_filter( $file, $type, $post ) {
		if ( 'empty-filter' === $type ) {
			$file = AMPFORWP_EMPTY_FILTER_FILE;
		}
		return $file;
	}


	// 4. Custom Header files
	add_filter( 'amp_post_template_file', 'ampforwp_custom_header', 10, 3 );
	function ampforwp_custom_header( $file, $type, $post ) {
		if ( 'header-bar' === $type ) {
			$file = AMPFORWP_DESIGN_SPECIFIC_HEADER_BAR_FILE;
		}
		return $file;
	}

	// 4.1 Custom Meta-Author files
	add_filter( 'amp_post_template_file', 'ampforwp_set_custom_meta_author', 10, 3 );
	function ampforwp_set_custom_meta_author( $file, $type, $post ) {
		if ( 'meta-author' === $type ) {
			$file = AMPFORWP_EMPTY_FILTER_FILE;
		}
		return $file;
	}
	// 4.2 Custom Meta-Taxonomy files
	add_filter( 'amp_post_template_file', 'ampforwp_set_custom_meta_taxonomy', 10, 3 );
	function ampforwp_set_custom_meta_taxonomy( $file, $type, $post ) {
		if ( 'meta-taxonomy' === $type ) {
			$file = AMPFORWP_EMPTY_FILTER_FILE;
		}
		return $file;
	}

	// 4.5 Added hook to add more layout.
	do_action('ampforwp_after_features_include');


	// 5.  Customize with Width of the site
	add_filter( 'amp_content_max_width', 'ampforwp_change_content_width' );
	function ampforwp_change_content_width( $content_max_width ) {
		return 1000;
	}


	// 7. Footer for AMP Pages
	add_filter( 'amp_post_template_file', 'ampforwp_custom_footer', 10, 3 );
	function ampforwp_custom_footer( $file, $type, $post ) {
		if ( 'footer' === $type ) {
			$file = AMPFORWP_DESIGN_SPECIFIC_FOOTER_FILE;
		}
		return $file;
	}
	//----------------------------------------AMP code Files Returning Functions End---------------------------


	//----------------------------------------Ads Functions Start---------------------------
	// 9. Advertisement code
		// Below Header Global
		add_action('ampforwp_after_header','ampforwp_header_advert');
		add_action('ampforwp_design_1_after_header','ampforwp_header_advert');
		function ampforwp_header_advert() {
			global $redux_builder_amp;

			if($redux_builder_amp['enable-amp-ads-1'] == true) {
				if($redux_builder_amp['enable-amp-ads-select-1'] == 1)  {
					$advert_width  = '300';
					$advert_height = '250';
	           	} elseif ($redux_builder_amp['enable-amp-ads-select-1'] == 2) {
		          	$advert_width  = '336';
					$advert_height = '280';
				} elseif ($redux_builder_amp['enable-amp-ads-select-1'] == 3)  {
		          	$advert_width  = '728';
					$advert_height = '90';
	           	} elseif ($redux_builder_amp['enable-amp-ads-select-1'] == 4)  {
		          	$advert_width  = '300';
					$advert_height = '600';
	            } elseif ($redux_builder_amp['enable-amp-ads-select-1'] == 5)  {
		          	$advert_width  = '320';
					$advert_height = '100';
	      		} elseif ($redux_builder_amp['enable-amp-ads-select-1'] == 6)  {
		          	$advert_width  = '200';
					$advert_height = '50';
	      		} elseif ($redux_builder_amp['enable-amp-ads-select-1'] == 7)  {
		          	$advert_width  = '320';
					$advert_height = '50';
	      		}
				$output = '<div class="amp-ad-wrapper amp_ad_1">';
				$output	.=	'<amp-ad class="amp-ad-1"
											type="adsense"
											width='. $advert_width .' height='. $advert_height . '
											data-ad-client="'. $redux_builder_amp['enable-amp-ads-text-feild-client-1'].'"
											data-ad-slot="'.  $redux_builder_amp['enable-amp-ads-text-feild-slot-1'] .'">';
				$output	.=	'</amp-ad>';
				$output	.= '</div>';
				echo $output;
			}
		}

		// Above Footer Global
		add_action('amp_post_template_footer','ampforwp_footer_advert',8);
		add_action('amp_post_template_above_footer','ampforwp_footer_advert',10);
        if ( $redux_builder_amp['amp-design-selector'] == 3) {
          remove_action('amp_post_template_footer','ampforwp_footer_advert',8);
        }

		function ampforwp_footer_advert() {
			global $redux_builder_amp;

			if($redux_builder_amp['enable-amp-ads-2'] == true) {
				if($redux_builder_amp['enable-amp-ads-select-2'] == 1)  {
					$advert_width  = '300';
					$advert_height = '250';
	           	} elseif ($redux_builder_amp['enable-amp-ads-select-2'] == 2) {
		          	$advert_width  = '336';
					$advert_height = '280';
				} elseif ($redux_builder_amp['enable-amp-ads-select-2'] == 3)  {
		          	$advert_width  = '728';
					$advert_height = '90';
	           	} elseif ($redux_builder_amp['enable-amp-ads-select-2'] == 4)  {
		          	$advert_width  = '300';
					$advert_height = '600';
	            } elseif ($redux_builder_amp['enable-amp-ads-select-2'] == 5)  {
		          	$advert_width  = '320';
					$advert_height = '100';
	      		} elseif ($redux_builder_amp['enable-amp-ads-select-2'] == 6)  {
		          	$advert_width  = '200';
					$advert_height = '50';
	      		} elseif ($redux_builder_amp['enable-amp-ads-select-2'] == 7)  {
		          	$advert_width  = '320';
					$advert_height = '50';
	      		}
				$output = '<div class="amp-ad-wrapper">';
				$output	.=	'<amp-ad class="amp-ad-2"
											type="adsense"
											width='. $advert_width .' height='. $advert_height . '
											data-ad-client="'. $redux_builder_amp['enable-amp-ads-text-feild-client-2'].'"
											data-ad-slot="'.  $redux_builder_amp['enable-amp-ads-text-feild-slot-2'] .'">';
				$output	.=	'</amp-ad>';
				$output	.= '</div>';
				echo $output;
			}
		}

		// Below Title Single
		add_action('ampforwp_before_post_content','ampforwp_before_post_content_advert');
		add_action('ampforwp_inside_post_content_before','ampforwp_before_post_content_advert');

		function ampforwp_before_post_content_advert() {
			global $redux_builder_amp;

			if($redux_builder_amp['enable-amp-ads-3'] == true) {
				if($redux_builder_amp['enable-amp-ads-select-3'] == 1)  {
					$advert_width  = '300';
					$advert_height = '250';
	           	} elseif ($redux_builder_amp['enable-amp-ads-select-3'] == 2) {
		          	$advert_width  = '336';
					$advert_height = '280';
				} elseif ($redux_builder_amp['enable-amp-ads-select-3'] == 3)  {
		          	$advert_width  = '728';
					$advert_height = '90';
	           	} elseif ($redux_builder_amp['enable-amp-ads-select-3'] == 4)  {
		          	$advert_width  = '300';
					$advert_height = '600';
	            } elseif ($redux_builder_amp['enable-amp-ads-select-3'] == 5)  {
		          	$advert_width  = '320';
					$advert_height = '100';
	      		} elseif ($redux_builder_amp['enable-amp-ads-select-3'] == 6)  {
		          	$advert_width  = '200';
					$advert_height = '50';
	      		} elseif ($redux_builder_amp['enable-amp-ads-select-3'] == 7)  {
		          	$advert_width  = '320';
					$advert_height = '50';
	      		}
				$output = '<div class="amp-ad-wrapper">';
				$output	.=	'<amp-ad class="amp-ad-3"
											type="adsense"
											width='. $advert_width .' height='. $advert_height . '
											data-ad-client="'. $redux_builder_amp['enable-amp-ads-text-feild-client-3'].'"
											data-ad-slot="'.  $redux_builder_amp['enable-amp-ads-text-feild-slot-3'] .'">';
				$output	.=	'</amp-ad>';
				$output	.= '</div>';
				echo $output;
			}
		}

		// Below Content Single
			add_action('ampforwp_after_post_content','ampforwp_after_post_content_advert');
			add_action('ampforwp_inside_post_content_after','ampforwp_after_post_content_advert');
		function ampforwp_after_post_content_advert() {
			global $redux_builder_amp;

			if($redux_builder_amp['enable-amp-ads-4'] == true) {
				if($redux_builder_amp['enable-amp-ads-select-4'] == 1)  {
					$advert_width  = '300';
					$advert_height = '250';
	           	} elseif ($redux_builder_amp['enable-amp-ads-select-4'] == 2) {
		          	$advert_width  = '336';
					$advert_height = '280';
				} elseif ($redux_builder_amp['enable-amp-ads-select-4'] == 3)  {
		          	$advert_width  = '728';
					$advert_height = '90';
	           	} elseif ($redux_builder_amp['enable-amp-ads-select-4'] == 4)  {
		          	$advert_width  = '300';
					$advert_height = '600';
	            } elseif ($redux_builder_amp['enable-amp-ads-select-4'] == 5)  {
		          	$advert_width  = '320';
					$advert_height = '100';
	      		} elseif ($redux_builder_amp['enable-amp-ads-select-4'] == 6)  {
		          	$advert_width  = '200';
					$advert_height = '50';
	      		} elseif ($redux_builder_amp['enable-amp-ads-select-4'] == 7)  {
		          	$advert_width  = '320';
					$advert_height = '50';
	      		}
				$output = '<div class="amp-ad-wrapper">';
				$output	.=	'<amp-ad class="amp-ad-4"
											type="adsense"
											width='. $advert_width .' height='. $advert_height . '
											data-ad-client="'. $redux_builder_amp['enable-amp-ads-text-feild-client-4'].'"
											data-ad-slot="'.  $redux_builder_amp['enable-amp-ads-text-feild-slot-4'] .'">';
				$output	.=	'</amp-ad>';
				$output	.= '</div>';
				echo $output;
			}
		}
//----------------------------------------Ads Functions End---------------------------



//----------------------------------------AMP metabox in Editor page Functions Start---------------------------
// 14. Adds a meta box to the post editing screen for AMP on-off on specific pages.
/**
 * Adds a meta box to the post editing screen for AMP on-off on specific pages
*/
add_action( 'add_meta_boxes', 'ampforwp_title_custom_meta' );
function ampforwp_title_custom_meta() {
  global $redux_builder_amp;
  $args = array(
     'public'   => true,
  );

  $output = 'names'; // 'names' or 'objects' (default: 'names')
  $operator = 'and'; // 'and' or 'or' (default: 'and')

  $post_types = get_post_types( $args, $output, $operator );

  if ( $post_types ) { // If there are any custom public post types.

    foreach ( $post_types  as $post_type ) {

      if( $post_type == 'amp-cta' ) {
					continue;
      }

      if( $post_type !== 'page' ) {
        add_meta_box( 'ampforwp_title_meta', __( 'Show AMP for Current Page?' ), 'ampforwp_title_callback', $post_type,'side' );
      }

      if( $redux_builder_amp['amp-on-off-for-all-pages'] && $post_type == 'page' ) {
          add_meta_box( 'ampforwp_title_meta', __( 'Show AMP for Current Page?' ), 'ampforwp_title_callback','page','side' );
      }

    }
  }
}


/**
 * Outputs the content of the meta box for AMP on-off on specific pages
 */
function ampforwp_title_callback( $post ) {
  wp_nonce_field( basename( __FILE__ ), 'ampforwp_title_nonce' );
  $ampforwp_stored_meta = get_post_meta( $post->ID );

	// TODO: Move the data storage code, to Save meta Box area as it is not a good idea to update an option everytime, try adding this code inside ampforwp_title_meta_save()
	// This code needs a rewrite.
	if ( $ampforwp_stored_meta['ampforwp-amp-on-off'][0] == 'hide-amp') {
		$exclude_post_value = get_option('ampforwp_exclude_post');
		if ( $exclude_post_value == null ) {
			$exclude_post_value[] = 0;
		}
		if ( $exclude_post_value ) {
			if ( ! in_array( $post->ID, $exclude_post_value ) ) {
				$exclude_post_value[] = $post->ID;
				update_option('ampforwp_exclude_post', $exclude_post_value);
			}
		}
	} else {
		$exclude_post_value = get_option('ampforwp_exclude_post');
		if ( $exclude_post_value == null ) {
			$exclude_post_value[] = 0;
		}
		if ( $exclude_post_value ) {
			if ( in_array( $post->ID, $exclude_post_value ) ) {
				$exclude_ids = array_diff($exclude_post_value, array($post->ID) );
				update_option('ampforwp_exclude_post', $exclude_ids);
			}
		}

	} ?>
  <p>
    <div class="prfx-row-content">
      <label for="meta-radio-one">
        <input type="radio" name="ampforwp-amp-on-off" id="meta-radio-one" value="default"  checked="checked" <?php if ( isset ( $ampforwp_stored_meta['ampforwp-amp-on-off'] ) ) checked( $ampforwp_stored_meta['ampforwp-amp-on-off'][0], 'default' ); ?>>
        <?php _e( 'Show' )?>
      </label>
      <label for="meta-radio-two">
        <input type="radio" name="ampforwp-amp-on-off" id="meta-radio-two" value="hide-amp" <?php if ( isset ( $ampforwp_stored_meta['ampforwp-amp-on-off'] ) ) checked( $ampforwp_stored_meta['ampforwp-amp-on-off'][0], 'hide-amp' ); ?>>
        <?php _e( 'Hide' )?>
      </label>
    </div>
  </p> <?php
}

/**
 * Saves the custom meta input for AMP on-off on specific pages
 */
 add_action( 'save_post', 'ampforwp_title_meta_save' );
function ampforwp_title_meta_save( $post_id ) {

  // Checks save status
  $is_autosave = wp_is_post_autosave( $post_id );
  $is_revision = wp_is_post_revision( $post_id );
  $is_valid_nonce = ( isset( $_POST[ 'ampforwp_title_nonce' ] ) && wp_verify_nonce( $_POST[ 'ampforwp_title_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

  // Exits script depending on save status
  if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
      return;
  }

  // Checks for radio buttons and saves if needed
  if( isset( $_POST[ 'ampforwp-amp-on-off' ] ) ) {
      $ampforwp_amp_status = sanitize_text_field( $_POST[ 'ampforwp-amp-on-off' ] );
      update_post_meta( $post_id, 'ampforwp-amp-on-off', $ampforwp_amp_status );
  }

}

add_filter('amp_frontend_show_canonical','ampforwp_hide_amp_for_specific_pages');
function ampforwp_hide_amp_for_specific_pages($input){
	global $post;
	$ampforwp_amp_status = get_post_meta($post->ID, 'ampforwp-amp-on-off', true);
	if ( $ampforwp_amp_status == 'hide-amp' ) {
		$input = false;
	}
	return $input;
}

// 28. Properly removes AMP if turned off from Post panel
add_filter( 'amp_skip_post', 'ampforwp_skip_amp_post', 10, 3 );
function ampforwp_skip_amp_post( $skip, $post_id, $post ) {
	$ampforwp_amp_post_on_off_meta = get_post_meta( $post->ID , 'ampforwp-amp-on-off' , true );
	if( $ampforwp_amp_post_on_off_meta === 'hide-amp' ) {
		$skip = true;
	}
  return $skip;
}
//----------------------------------------AMP metabox in Editor page Functions End---------------------------



//----------------------------------------Misccelenous Feature Functions Start---------------------------
// 22. Removing author links from comments Issue #180
if( ! function_exists( "disable_comment_author_links" ) ) {
	function ampforwp_disable_comment_author_links( $author_link ){
		$ampforwp_is_amp_endpoint = ampforwp_is_amp_endpoint();
		if ( $ampforwp_is_amp_endpoint ) {
				return strip_tags( $author_link );
		} else {
			return $author_link;
		}
	}
	add_filter( 'get_comment_author_link', 'ampforwp_disable_comment_author_links' );
}


//35. Disqus Comments Support
add_action('ampforwp_post_after_design_elements','ampforwp_add_disqus_support');
function ampforwp_add_disqus_support() {

	global $redux_builder_amp;
	if ( $redux_builder_amp['ampforwp-disqus-comments-support'] ) {
		if( $redux_builder_amp['ampforwp-disqus-comments-name'] !== '' ) {
			global $post; $post_slug=$post->post_name;

			$disqus_script_host_url = AMPFORWP_DISQUS_HOST;

			if( $redux_builder_amp['ampforwp-disqus-host-position'] == 0 ) {
				$disqus_script_host_url = esc_url( $redux_builder_amp['ampforwp-disqus-host-file'] );
			}

			$disqus_url = $disqus_script_host_url.'?disqus_title='.$post_slug.'&url='.get_permalink().'&disqus_name='. esc_url( $redux_builder_amp['ampforwp-disqus-comments-name'] ) ."/embed.js"  ; ?>
			<section class="amp-wp-content post-comments amp-wp-article-content amp-disqus-comments" id="comments">
				<amp-iframe
					height=200
					width=300
					layout="responsive"
					sandbox="allow-forms allow-modals allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts"
					frameborder="0"
					src="<?php echo $disqus_url ?>" >
					<div overflow tabindex="0" role="button" aria-label="Read more"> Disqus Comments Loading...</div>
				</amp-iframe>
			</section> <?php
		}
	}
}
//----------------------------------------Misccelenous Feature Functions End---------------------------


//----------------------------------------Analytics Functions Start---------------------------
	// 10. Analytics Area
		add_action('amp_post_template_footer','ampforwp_analytics',11);
		function ampforwp_analytics() {
			// 10.1 Analytics Support added for Google Analytics
			global $redux_builder_amp;
			if ( $redux_builder_amp['amp-analytics-select-option']=='1' ){ ?>
					<amp-analytics type="googleanalytics" id="analytics1">
						<script type="application/json">
						{
						  "vars": {
						    "account": "<?php global $redux_builder_amp; echo $redux_builder_amp['ga-feild']; ?>"
						  },
						  "triggers": {
						    "trackPageview": {
						      "on": "visible",
						      "request": "pageview"
						    }
						  }
						}
						</script>
					</amp-analytics> <?php
				}//code ends for supporting Google Analytics

			// 10.2 Analytics Support added for segment.com
			if ( $redux_builder_amp['amp-analytics-select-option']=='2' ) { ?>
					<amp-analytics type="segment">
						<script>
						{
						  "vars": {
						    "writeKey": "<?php global $redux_builder_amp; echo $redux_builder_amp['sa-feild']; ?>",
								"name": "<?php echo the_title(); ?>"
						  }
						}
						</script>
					</amp-analytics> <?php
			}

			// 10.3 Analytics Support added for Piwik
			if( $redux_builder_amp['amp-analytics-select-option']=='3' ) { ?>
					<amp-pixel src="<?php global $redux_builder_amp; echo $redux_builder_amp['pa-feild']; ?>"></amp-pixel> <?php
			}

			// 10.4 Analytics Support added for quantcast
			if ( $redux_builder_amp['amp-analytics-select-option']=='4' ) { ?>
					<amp-analytics type="quantcast">
						<script type="application/json">
						{
						  "vars": {
						    "pcode": "<?php echo $redux_builder_amp['amp-quantcast-analytics-code']; ?>",
								"labels": [ "AMPProject" ]
						  }
						}
						</script>
					</amp-analytics> <?php
				}

			// 10.5 Analytics Support added for comscore
			if ( $redux_builder_amp['amp-analytics-select-option']=='5' ) { ?>
					<amp-analytics type="comscore">
						<script type="application/json">
						{
						  "vars": {
						    "c1": "<?php echo $redux_builder_amp['amp-comscore-analytics-code-c1']; ?>",
						    "c2": "<?php echo $redux_builder_amp['amp-comscore-analytics-code-c2']; ?>"
						  }
						}
						</script>
					</amp-analytics> <?php
				}

		}//analytics function ends here
		// Create GTM support
		add_filter( 'amp_post_template_analytics', 'amp_gtm_add_gtm_support' );
		function amp_gtm_add_gtm_support( $analytics ) {
			global $redux_builder_amp;
			if ( ! is_array( $analytics ) ) {
				$analytics = array();
			}
			$analytics['amp-gtm-googleanalytics'] = array(
				'type' => $redux_builder_amp['amp-gtm-analytics-type'],
				'attributes' => array(
					'data-credentials' 	=> 'include',
					'config'			=> 'https://www.googletagmanager.com/amp.json?id='. $redux_builder_amp['amp-gtm-id'] .'&gtm.url=SOURCE_URL'
				),
				'config_data' => array(
					'vars' => array(
						'account' =>  $redux_builder_amp['amp-gtm-analytics-code']
					),
					'triggers' => array(
						'trackPageview' => array(
							'on' => 'visible',
							'request' => 'pageview',
						),
					),
				),
			);
			return $analytics;
		}

//----------------------------------------Analytics Functions End---------------------------


//----------------------------------------Compatibility Functions Start---------------------------
require AMPFORWP_COMPATIIBLITY_FILE;
//----------------------------------------Compatibility Functions End---------------------------


//----------------------------------------Design-3 Sepecific Functions Start---------------------------
//38. Extra Design Specific Features
add_action('pre_amp_render_post','ampforwp_add_extra_functions',12);
function ampforwp_add_extra_functions() {
	global $redux_builder_amp;
	if ( $redux_builder_amp['amp-design-selector'] == 3 ) {

		// Slide in Menu
		class AMPforWP_Menu_Walker extends Walker_Nav_Menu {
			protected $accordion_started = FALSE;
			protected $accordion_childs_started = FALSE;

			public function start_lvl( &$output, $depth = 0, $args = array() ) {
			}

			public function end_lvl( &$output, $depth = 0, $args = array() ) {
				if ( $this->accordion_childs_started ) {
					$this->end_accordion_child_wrapper( $output, $depth );
				}
				if ( $this->accordion_started ) {
					$this->end_accordion( $output, $depth );
				}
			}

			public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
				$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );
				$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
				$classes[] = 'menu-item-' . $item->ID;
				$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
				$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

				if ( $this->has_children ) {
					set_transient( 'ampforwp_has_nav_child', true, 3 );
					$this->start_accordion( $output, $depth );
					$output .= '<h6 ' . $class_names . '>';
					$output .= strip_tags( $this->get_anchor_tag( $item, $depth, $args, $id ) , '<a>');
					$output .= '</h6>';
					$this->start_accordion_child_wrapper( $output, $depth );
				} else {
					$output .= '<li ' . $class_names . '>';
					$output .= strip_tags( $this->get_anchor_tag( $item, $depth, $args, $id ) , '<a>');
					$output .= '</li>';
				}
			}

			public function end_el( &$output, $item, $depth = 0, $args = array() ) {
			}

			public function start_accordion( &$output, $depth = 0 ) {
				$output .= "<amp-accordion><section>";
				$this->accordion_started = TRUE;
				$this->enqueue_accordion = TRUE;
			}

			public function end_accordion( &$output, $depth = 0 ) {
				$output .= "</section></amp-accordion>";
				$this->accordion_started = FALSE;
			}

			public function start_accordion_child_wrapper( &$output, $depth = 0 ) {
				$output .= "\n<div>\n";
				$this->accordion_childs_started = TRUE;
			}

			public function end_accordion_child_wrapper( &$output, $depth = 0 ) {
				$output .= "</div>\n";
				$this->accordion_childs_started = FALSE;
			}

			public function get_anchor_tag( $item, $depth, $args, $id ) {
				$current_el = '';
				parent::start_el( $current_el, $item, $depth, $args, $id );
				// Unwrap li tag
				if ( preg_match( '#<\s*li\s* [^>]* > (.+) #ix', $current_el, $matched ) ) {
					return $matched[1];
				}
				return $this->make_anchor_tag( $item, $args, $depth );
			}

			protected function make_anchor_tag( $item, $args, $depth ) {
				$atts           = array();
				$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
				$atts['target'] = ! empty( $item->target ) ? $item->target : '';
				$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
				$atts['href']   = ! empty( $item->url ) ? $item->url : '';

				$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
				$attributes = '';
				foreach ( $atts as $attr => $value ) {
					if ( ! empty( $value ) ) {
						$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
						$attributes .= ' ' . $attr . '="' . $value . '"';
					}
				}
				$title = apply_filters( 'the_title', $item->title, $item->ID );
				$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
				$item_output = $args->before;
				$item_output .= '<a' . $attributes . '>';
				$item_output .= $args->link_before . $title . $args->link_after;
				$item_output .= '</a>';
				$item_output .= $args->after;
				$item_output =  $item_output ;
				return $item_output;
			}
		}

		// Add required Fonts for Design 3
		add_filter( 'amp_post_template_data', 'ampforwp_add_design3_required_fonts' );
		function ampforwp_add_design3_required_fonts( $data ) {
			$data['font_urls']['roboto_slab_pt_serif'] = 'https://fonts.googleapis.com/css?family=Roboto+Slab:400,700|PT+Serif:400,700';
			unset($data['font_urls']['merriweather']);
			return $data;
		}

	}
}
//----------------------------------------Design-3 Sepecific Functions End---------------------------


//----------------------------------------TItles Functions Start---------------------------
//26. Extending Title Tagand De-Hooking the Standard one from AMP
add_action('amp_post_template_include_single','ampforwp_remove_title_tags');
function ampforwp_remove_title_tags(){
	remove_action('amp_post_template_head','amp_post_template_add_title');
	add_action('amp_post_template_head','ampforwp_add_custom_title_tag');

	function ampforwp_add_custom_title_tag(){
		global $redux_builder_amp; ?>
		<title> <?php

			// title for a single post and single page
			if( is_single() || is_page() ){
				global $post;
				$title = $post->post_title;
				$site_title =  $title . ' | ' . get_option( 'blogname' ) ;
			}

			// title for archive pages
			if ( is_archive() && $redux_builder_amp['ampforwp-archive-support'] )  {
				$site_title = strip_tags(get_the_archive_title( '' )) . ' | ' . strip_tags(get_the_archive_description( '' ));
			}

			if ( is_home() ) {
				$site_title = get_bloginfo('name') . ' | ' . get_option( 'blogdescription' ) ;
				if  ( $redux_builder_amp['amp-frontpage-select-option']== 1) {
					$ID = $redux_builder_amp['amp-frontpage-select-option-pages'];
					$site_title =  get_the_title( $ID ) . ' | ' . get_option('blogname');
				} else {
					global $wp;
					$current_archive_url = home_url( $wp->request );
					$current_url_in_pieces = explode('/',$current_archive_url);
					$cnt = count($current_url_in_pieces);
					if( is_numeric( $current_url_in_pieces[  $cnt-1 ] ) ) {
						$site_title .= ' | Page '.$current_url_in_pieces[$cnt-1];
					}
				}
			}

			if( is_search() ) {
				$site_title =  $redux_builder_amp['amp-translator-search-text'] . '  ' . get_search_query();
			}

			if ( class_exists('WPSEO_Frontend') ) {
				$front = WPSEO_Frontend::get_instance();
				$title = $front->title( $site_title );

				// Code for Custom Frontpage Yoast SEO Title
				if ( class_exists('WPSEO_Meta') ) {

					// Yoast SEO Title
					$yaost_title = WPSEO_Options::get_option( 'wpseo' );
					if ( $yaost_title['website_name']) {
						$site_title  = $yaost_title['website_name'];
					} else {
						$site_title  =  get_bloginfo('name');
					}

					// Yoast SEO Title Seperator
					$wpseo_titles = WPSEO_Options::get_option( 'wpseo_titles' );
					$seperator_options = WPSEO_Option_Titles::get_instance()->get_separator_options();
					if ( $wpseo_titles['separator'] ) {
						$seperator = $seperator_options[ $wpseo_titles['separator'] ];
					} else {
						$seperator = ' - ';
					}

					$post_id = $redux_builder_amp['amp-frontpage-select-option-pages'];
					$custom_fp_title = WPSEO_Meta::get_value('title', $post_id );
					if ( is_home() && $redux_builder_amp['amp-frontpage-select-option'] ) {
						if ( $custom_fp_title ) {
							$title = $custom_fp_title;
						} else {
							$title = get_the_title($post_id) .' '. $seperator .' '. $site_title ;
						}
					}
				}

				echo $title;
			} else {
				echo $site_title;
			} ?>
		</title> <?php
	}
}


//38. #529 editable archives
add_filter( 'get_the_archive_title', 'ampforwp_editable_archvies_title' );
function ampforwp_editable_archvies_title($title) {
	global $redux_builder_amp;
  if ( is_category() ) {
    $title = single_cat_title( $redux_builder_amp['amp-translator-archive-cat-text'].' ', false );
  } elseif ( is_tag() ) {
    $title = single_tag_title( $redux_builder_amp['amp-translator-archive-tag-text'].' ', false );
  }
  return $title;
}
//----------------------------------------TItles Functions End---------------------------


//----------------------------------------Auto AMP nav URLS Functions Start--------------------------
// 44. auto adding /amp for the menu
add_action('amp_init','ampforwp_auto_add_amp_menu_link_insert');
function ampforwp_auto_add_amp_menu_link_insert() {
	add_action( 'wp', 'ampforwp_auto_add_amp_in_link_check' );
}

function ampforwp_auto_add_amp_in_link_check() {
	global $redux_builder_amp;
	$ampforwp_is_amp_endpoint = ampforwp_is_amp_endpoint();

	if ( $ampforwp_is_amp_endpoint && $redux_builder_amp['ampforwp-auto-amp-menu-link'] == 1 ) {
		add_filter( 'nav_menu_link_attributes', 'ampforwp_auto_add_amp_in_menu_link', 10, 3 );
	}
}

function ampforwp_auto_add_amp_in_menu_link( $atts, $item, $args ) {
    $atts['href'] = trailingslashit( $atts['href'] ) . AMPFORWP_AMP_QUERY_VAR;
    return $atts;
}
//----------------------------------------Auto AMP nav URLS  Functions End--------------------------


//----------------------------------------Widgets output Functions Start--------------------------
// 42. registeing AMP sidebars
if( function_exists('register_sidebar') ) {

	register_sidebar(
		array(
			'name' => 'AMP Above Loop',
			'id'   => 'ampforwp-above-loop',
			'description'   => 'Widget area for above the Loop Output',
			'before_widget' => '<div class="category-widget-wrapper"><div class="category-widget-gutter">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>'
		)
	);
	register_sidebar(
		array(
			'name' => 'AMP Below Loop',
			'id'   => 'ampforwp-below-loop',
			'description'   => 'Widget area for below the Loop Output',
			'before_widget' => '<div class="category-widget-wrapper"><div class="category-widget-gutter">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>'
		)
	);

}


// 43. custom actions for widgets output
add_action( 'ampforwp_home_above_loop' , 'ampforwp_output_widget_content_above_loop' );
add_action( 'ampforwp_frontpage_above_loop' , 'ampforwp_output_widget_content_above_loop' );
function ampforwp_output_widget_content_above_loop() {
    dynamic_sidebar( 'ampforwp-above-loop' );
}

add_action( 'ampforwp_home_below_loop' , 'ampforwp_output_widget_content_below_loop' );
add_action( 'ampforwp_frontpage_below_loop' , 'ampforwp_output_widget_content_below_loop' );
function ampforwp_output_widget_content_below_loop() {
    dynamic_sidebar( 'ampforwp-below-loop' );
}
//----------------------------------------Widgets output Functions Functions End---------------------------

//----------------------------------------SEO Functions Start---------------------------

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
//----------------------------------------SEO Functions End---------------------------

//----------------------------------------Structured Data Functions Start---------------------------
	// 12. Add Logo URL in the structured metadata
	add_filter( 'amp_post_template_metadata', 'ampforwp_update_metadata', 10, 2 );
	function ampforwp_update_metadata( $metadata, $post ) {
		global $redux_builder_amp;

		if (! empty( $redux_builder_amp['opt-media']['url'] ) ) {
			$structured_data_main_logo = $redux_builder_amp['opt-media']['url'];
		}

		if (! empty( $redux_builder_amp['amp-structured-data-logo']['url'] ) ) {
			$structured_data_logo = $redux_builder_amp['amp-structured-data-logo']['url'];
		}

		if ( $structured_data_logo ) {
			$structured_data_logo = $structured_data_logo;
		} else {
			$structured_data_logo = $structured_data_main_logo;
		}

		$metadata['publisher']['logo'] = array(
			'@type' 	=> 'ImageObject',
			'url' 		=>  $structured_data_logo ,
			'height' 	=> 36,
			'width' 	=> 190,
		);

		//code for adding 'description' meta from Yoast SEO
		if($redux_builder_amp['ampforwp-seo-yoast-custom-description']){
			if ( class_exists('WPSEO_Frontend') ) {
				$front = WPSEO_Frontend::get_instance();
				$desc = $front->metadesc( false );
				if ( $desc ) {
					$metadata['description'] = $desc;
				}

				// Code for Custom Frontpage Yoast SEO Description
				$post_id = $redux_builder_amp['amp-frontpage-select-option-pages'];
				if ( class_exists('WPSEO_Meta') ) {
					$custom_fp_desc = WPSEO_Meta::get_value('metadesc', $post_id );
					if ( is_home() && $redux_builder_amp['amp-frontpage-select-option'] ) {
						if ( $custom_fp_desc ) {
							$metadata['description'] = $custom_fp_desc;
						} else {
							unset( $metadata['description'] );
						}
					}
				}
			}
		} //End of code for adding 'description' meta from Yoast SEO

		return $metadata;
	}


	// 13. Add Custom Placeholder Image for Structured Data.
	// if there is no image in the post, then use this image to validate Structured Data.
	add_filter( 'amp_post_template_metadata', 'ampforwp_update_metadata_featured_image', 10, 2 );
	function ampforwp_update_metadata_featured_image( $metadata, $post ) {
		global $redux_builder_amp;
		global $post;
		$post_id = get_the_ID() ;
		$post_image_id = get_post_thumbnail_id( $post_id );
		$structured_data_image = wp_get_attachment_image_src( $post_image_id, 'full' );
		$post_image_check = $structured_data_image;

		if ( $post_image_check == false) {
			if (! empty( $redux_builder_amp['amp-structured-data-placeholder-image']['url'] ) ) {
				$structured_data_image_url = $redux_builder_amp['amp-structured-data-placeholder-image']['url'];
			}
				$structured_data_image = $structured_data_image_url;
				$structured_data_height = intval($redux_builder_amp['amp-structured-data-placeholder-image-height']);
				$structured_data_width = intval($redux_builder_amp['amp-structured-data-placeholder-image-width']);

				$metadata['image'] = array(
					'@type' 	=> 'ImageObject',
					'url' 		=> $structured_data_image ,
					'height' 	=> $structured_data_height,
					'width' 	=> $structured_data_width,
				);
		}
		// Custom Structured Data information for Archive, Categories and tag pages.
		if ( is_archive() ) {
				$structured_data_image = $redux_builder_amp['amp-structured-data-placeholder-image']['url'];
				$structured_data_height = intval($redux_builder_amp['amp-structured-data-placeholder-image-height']);
				$structured_data_width = intval($redux_builder_amp['amp-structured-data-placeholder-image-width']);

				$structured_data_archive_title 	= "Archived Posts";
				$structured_data_author				=  get_userdata( 1 );
						if ( $structured_data_author ) {
							$structured_data_author 		= $structured_data_author->display_name ;
						} else {
							$structured_data_author 		= "admin";
						}

				$metadata['image'] = array(
					'@type' 	=> 'ImageObject',
					'url' 		=> $structured_data_image ,
					'height' 	=> $structured_data_height,
					'width' 	=> $structured_data_width,
				);
				$metadata['author'] = array(
					'@type' 	=> 'Person',
					'name' 		=> $structured_data_author ,
				);
				$metadata['headline'] = $structured_data_archive_title;
		}

		if ( $metadata['image']['width'] < 696 ) {
 			$metadata['image']['width'] = 700 ;
   	}

		return $metadata;
	}


// # Core Function
// 45. searchpage, frontpage, homepage structured data
add_filter( 'amp_post_template_metadata', 'ampforwp_search_or_homepage_or_staticpage_metadata', 10, 2 );
function ampforwp_search_or_homepage_or_staticpage_metadata( $metadata, $post ) {
	global $redux_builder_amp;
	global $wp;

	if( is_search() || is_home() || ( is_front_page() && $redux_builder_amp['amp-frontpage-select-option'] )) {

		if( is_home() || is_front_page() ){
			$current_url = home_url( $wp->request );
			$current_url = dirname( $current_url );
			$headline 	 =  get_bloginfo('name') . ' | ' . get_option( 'blogdescription' );
		} else {
			$current_url 	= trailingslashit(get_home_url())."?s=".get_search_query();
			$current_url 	= untrailingslashit( $current_url );
			$headline 		=  $redux_builder_amp['amp-translator-search-text'] . '  ' . get_search_query();
		}

		// placeholder Image area
		if (! empty( $redux_builder_amp['amp-structured-data-placeholder-image']['url'] ) ) {
			$structured_data_image_url = $redux_builder_amp['amp-structured-data-placeholder-image']['url'];
		}
		$structured_data_image =  $structured_data_image_url; //  Placeholder Image URL
		$structured_data_height = intval($redux_builder_amp['amp-structured-data-placeholder-image-height']); //  Placeholder Image width
		$structured_data_width = intval($redux_builder_amp['amp-structured-data-placeholder-image-width']); //  Placeholder Image height

		if( is_front_page() ) {
			$ID = $redux_builder_amp['amp-frontpage-select-option-pages']; // ID of slected front page
			$headline =  get_the_title( $ID ) . ' | ' . get_option('blogname');
			$static_page_data = get_post( $ID );

			$datePublished = $static_page_data->post_date;
			$dateModified = $static_page_data->post_modified;

			$featured_image_array = wp_get_attachment_image_src( get_post_thumbnail_id( $ID ) ); // Featured Image structured Data
			if( $featured_image_array ) {
				$structured_data_image = $featured_image_array[0];
				$structured_data_image = $featured_image_array[1];
				$structured_data_image = $featured_image_array[2];
			}
		} else {
			// TODO : check the entire else section .... time for search and homepage...wierd ???
			$datePublished = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) - 2 );
			// time difference is 2 minute between published and modified date
			$dateModified = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
		}
		$metadata['datePublished'] = $datePublished; // proper published date added
		$metadata['dateModified'] = $dateModified; // proper modified date

		$metadata['image'] = array(
			'@type' 	=> 'ImageObject',
			'url' 		=> $structured_data_image ,
			'height' 	=> $structured_data_height,
			'width' 	=> $structured_data_width,
		);

		$metadata['mainEntityOfPage'] = $current_url; // proper URL added
		$metadata['headline'] = $headline; // proper headline added
	}
	return $metadata;
}
//----------------------------------------Structured Data Functions End---------------------------


//----------------------------------------Search Functions Start---------------------------
// 46. search search search everywhere #615
add_action('pre_amp_render_post','ampforwp_search_related_functions',12);
function ampforwp_search_related_functions(){
	global $redux_builder_amp;
	if ( ampforwp_is_search_enabled() ) {
				add_action('ampforwp_search_form','ampforwp_the_search_form');
	}
}

add_action('ampforwp_global_after_footer','ampforwp_lightbox_html_output');
function ampforwp_lightbox_html_output() {
	if ( ampforwp_is_search_enabled() ) {
	  global $redux_builder_amp;
		if( ampforwp_is_search_enabled() ) { ?>
				<amp-lightbox id="search-icon" layout="nodisplay">
				    <?php do_action('ampforwp_search_form'); ?>
				    <button on="tap:search-icon.close" class="closebutton">X</button>
				    <i class="icono-cross"></i>
				</amp-lightbox> <?php
	  }
	}
}

add_action( 'ampforwp_header_search' , 'ampforwp_search_button_html_output' );
function ampforwp_search_button_html_output(){
	if ( ampforwp_is_search_enabled() ) {
	 global $redux_builder_amp;
	 if( ampforwp_is_search_enabled() ) { ?>
        <div class="searchmenu">
					<button on="tap:search-icon">
						<i class="icono-search"></i>
					</button>
				</div> <?php
    }
 	}
}


function ampforwp_the_search_form() {
    echo ampforwp_get_search_form();
}
function ampforwp_get_search_form() {
	if ( ampforwp_is_search_enabled() ) {
		global $redux_builder_amp;
		$label = $redux_builder_amp['ampforwp-search-label'];
		$placeholder = $redux_builder_amp['ampforwp-search-placeholder'];
	  $form = '<form role="search" method="get" id="searchform" class="searchform" target="_top" action="' . get_bloginfo('url')  .'">
							<div>
								<label class="screen-reader-text" for="s">' . $label . '</label>
								<input type="text" placeholder="AMP" value="1" name="amp" class="hide" id="ampsomething" />
								<input type="text" placeholder="'.$placeholder.'" value="' . get_search_query() . '" name="s" id="s" />
								<input type="submit" id="searchsubmit" value="'. esc_attr_x( 'Search', 'submit button' ) .'" />
							</div>
						</form>';
	    return $form;
		}
}
//----------------------------------------Search Functions End---------------------------


//----------------------------------------Woocommerece ShortCode Functions Start---------------------------
//53. Adding the Markup for AMP Woocommerce latest Products
/*******************************
Examples:

[amp-woocommerce num=5]
[amp-woocommerce num=5 link=noamp]
[amp-woocommerce num=5 link=amp]
*******************************/
 function get_amp_latest_prodcuts_markup( $atts ) {
	 // initializing these to avoid debug errors
	 global $post;
	 $atts[] = shortcode_atts( array(
																 'num' => get_permalink($atts['num']),
																 'link' => get_permalink($atts['link'])
												 		 		), $atts );

	 $exclude_ids = get_option('ampforwp_exclude_post');
	 $number_of_latest_prcts = $atts['num'] ;

		$q = new WP_Query( array(
		 'post_type'           => 'product',
		 'orderby'             => 'date',
		 'paged'               => esc_attr($paged),
		 'post__not_in' 		  => $exclude_ids,
		 'has_password' => false,
		 'post_status'=> 'publish',
		 'posts_per_page' => $number_of_latest_prcts
		) );

	  if ( $q->have_posts() ) :  $content .= '<ul class="ampforwp_wc_shortcode">';
          while ( $q->have_posts() ) : $q->the_post();
			if( $atts['link'] === 'amp' ) {
				$ampforwp_post_url = trailingslashit( get_permalink() ) . AMPFORWP_AMP_QUERY_VAR ;
			} else {
				$ampforwp_post_url = trailingslashit( get_permalink() ) ;
			}
					$content .= '<li class="ampforwp_wc_shortcode_child"><a href="'.$ampforwp_post_url.'">';
					global $redux_builder_amp;
					// $content .= '<div class="amp-wp-content ampforwp-wc-parent"><div class="amp-wp-content featured-image-content">';
					if ( has_post_thumbnail() ) {
						$thumb_id = get_post_thumbnail_id();
						$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail', true);
						$thumb_url = $thumb_url_array[0];

						$content .= '<amp-img src='.$thumb_url.' width="150" height="150" ></amp-img>' ;
					}
					// $content .= '</div>';
					$content .= '<div class="ampforwp-wc-title">'.get_the_title().'</div>';
					if (  class_exists( 'WooCommerce' )  ) {
						// $content .= '<div class="ampforwp-wc-price">';
						global $woocommerce;
						$amp_product_price 	=  $woocommerce->product_factory->get_product()->get_price_html();
						$context = '';
						$allowed_tags 		= wp_kses_allowed_html( $context );

						if ( $amp_product_price ) {
							$content .= '<div class="ampforwp-wc-price">' .  wp_kses( $amp_product_price,  $allowed_tags  ) .'</div>' ;
						} else {
							// $content .= "Sorry, this item is not for sale at the moment, please check out more products <a href=" . esc_url( home_url('/shop') ) . "> Here </a> " ;
						}
					}
        $content .= '</a></li>';  ?>
				<?php endwhile;  $content .= '</ul>'; ?>
		<?php endif; ?>
		<?php wp_reset_postdata();

		 // Add AMP Woocommerce latest Products only on AMP Endpoint
		 $endpoint_check = is_amp_endpoint();
		 if ( $endpoint_check ) {
			 return $content;
		 }
 }

 // Generating Short code for AMP Woocommerce latest Products
 function ampforwp_latest_products_register_shortcodes() {
	 add_shortcode('amp-woocommerce', 'get_amp_latest_prodcuts_markup');
 }
 add_action( 'amp_init', 'ampforwp_latest_products_register_shortcodes');

 // Adding the styling for AMP Woocommerce latest Products
 add_action('amp_post_template_css','amp_latest_products_styling',PHP_INT_MAX);
 function amp_latest_products_styling() { ?>
	.ampforwp_wc_shortcode{padding:0}
	.ampforwp_wc_shortcode li{ font-size:12px; line-height: 1; float: left;max-width: 150px;list-style-type: none;margin: 10px;}
	.single-post .ampforwp_wc_shortcode li amp-img{margin:0}
	.ampforwp-wc-title{ margin: 10px 0px; }
	.ampforwp-wc-price{ color:#444 } <?php
 }
 //----------------------------------------Woocommerece ShortCode Functions End---------------------------


//----------------------------------------Scripts Functions End---------------------------
require AMPFORWP_SCRIPTS_FILE;
//----------------------------------------Scripts Functions End---------------------------
