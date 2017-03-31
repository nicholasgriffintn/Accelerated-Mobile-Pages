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
	// TODO: this filter will be removed in future and use ampforwp_the_header_bar hook instead
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


	// 7. Footer for AMP Pages
	// TODO: this filter will be removed in future and use ampforwp_the_footer - ampforwp_global_after_footer hook instead
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
	// 4.5 Added hook to add more layout.
	do_action('ampforwp_after_features_include');


	// 5.  Customize with Width of the site
	add_filter( 'amp_content_max_width', 'ampforwp_change_content_width' );
	function ampforwp_change_content_width( $content_max_width ) {
		return 1000;
	}


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
