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
	require AMPFORWP_METABOX_FILE;
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
