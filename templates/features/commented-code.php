<?php
/*
*/

// --------------------------------------------------------------------------
// --------------------------------------------------------------------------
// --------------------------------------------------------------------------

// commented features are bought here to save actual code space

// 17. Archives Canonical in AMP version
// function ampforwp_rel_canonical_archive() {
//
// 			//    $archivelink = esc_url( get_permalink( $id ) . AMPFORWP_AMP_QUERY_VAR . '/' );
//   		echo "<link rel='canonical' href='$current_archive_url' />\n";
// }
// add_action( 'amp_post_template_head', 'ampforwp_rel_canonical_archive' );


// 18. Custom Canonical for Homepage
// function ampforwp_rel_canonical() {
//     if ( !is_home() )
//     return;
// //    $link = esc_url( get_permalink( $id ) . AMPFORWP_AMP_QUERY_VAR . '/' );
//     $homelink = get_home_url();
//     echo "<link rel='canonical' href='$homelink' />\n";
// }
// add_action( 'amp_post_template_head', 'ampforwp_rel_canonical' );


// 18.5. Custom Canonical for Frontpage
// function ampforwp_rel_canonical_frontpage() {
//    if ( is_home() || is_front_page() )
//    return;
// //    $link = esc_url( get_permalink( $id ) . AMPFORWP_AMP_QUERY_VAR . '/' );
//    $homelink = get_home_url();
//    echo "<link rel='canonical' href='$homelink' />\n";
// }
// add_action( 'amp_post_template_head', 'ampforwp_rel_canonical_frontpage' );


// 20. Remove the default Google font for performance
// add_action( 'amp_post_template_head', function() {
//     remove_action( 'amp_post_template_head', 'amp_post_template_add_fonts' );
// }, 9 );


// Feature 32 commented Code
// Disable Concatenate Google Fonts
// add_filter( 'get_rocket_option_minify_google_fonts', '__return_false', PHP_INT_MAX );
// Disable CSS & JS magnification
// add_filter( 'get_rocket_option_minify_js', '__return_false', PHP_INT_MAX );
// add_filter( 'get_rocket_option_minify_css', '__return_false', PHP_INT_MAX );


// This Caused issue, Please see: https://github.com/ahmedkaludi/accelerated-mobile-pages/issues/713
//
//add_action('amp_init','ampforwp_cache_compatible_activator');
//function ampforwp_cache_compatible_activator(){
//    add_action('template_redirect','ampforwp_cache_plugin_compatible');
//}
//function ampforwp_cache_plugin_compatible(){
//    $ampforwp_is_amp_endpoint = ampforwp_is_amp_endpoint();
//    if ( ! $ampforwp_is_amp_endpoint ) {
//        return;
//    }
//    /**
//     * W3 total cache
//     */
//    add_filter( 'w3tc_minify_js_enable', array( $this, '_return_false' ) );
//    add_filter( 'w3tc_minify_css_enable', array( $this, '_return_false' ) );
//}


//36. remove photon support in AMP
//add_action('amp_init','ampforwp_photon_remove');
//function ampforwp_photon_remove(){
//	if ( class_exists( 'Jetpack' ) ) {
//		add_filter( 'jetpack_photon_development_mode', 'ampforwp_diable_photon' );
//	}
//}
//
//function ampforwp_diable_photon() {
//	return true;
//}


/************************************************************************
* TODO
* Only Move this back to general features file when
*
* all scripts issues are resolved once and for all
*
* ITs ok to leaave it there in its own file
*
*************************************************************************/
	//TODO checkon Search pages properly
	// Seemes to work perfectly
	// if( is_search() ) {
	// 	// Remove all unwanted scripts on search pages
	// 	unset( $data['amp_component_scripts'] );
	// }

  //TODO put this code conditionally or this will throw Fatal Error
    // 1. if file_exisits for require_once( WP_PLUGIN_DIR . '/amp/amp.php' );
    // 2. if function_exists for amp_load_classes();

 //TODO all direct functions to be converted into hooks

 //TODO add a filter to add classes to body <body body_class(); > 
