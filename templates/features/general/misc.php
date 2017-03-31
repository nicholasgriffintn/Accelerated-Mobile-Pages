<?php
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
