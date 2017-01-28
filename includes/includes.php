<?php
/* This file will contain all the extra code that works like a supporting.
1. AMP menu code
2. Newsletter code
*/
// 1. AMP menu code
	// Registering Custom AMP menu for this plugin
	if (! function_exists( 'ampforwp_menu') ) {
		function ampforwp_menu() {
		  register_nav_menus(
		    array( 
		      'amp-menu' => __( 'AMP Menu','ampforwp' ),
		      'amp-footer-menu' => __( 'AMP Footer Menu','ampforwp' ),
		    )
		  );
		}
		add_action( 'init', 'ampforwp_menu' );
	}

// 2. Newsletter code
	require_once( AMPFORWP_PLUGIN_DIR . '/includes/newsletter.php' );
?>