<?php
// # Util Function
// Function to Check Plugin Enabled or Not
if ( !function_exists( 'ampforwp_is_plugin_active' ) ) {
  function ampforwp_is_plugin_active( $plugin_name ) {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    return is_plugin_active( $plugin_name );
  }
}


// # Util Function
// AMP endpoint Verifier
if ( !function_exists( 'ampforwp_is_amp_endpoint' ) ) {
  function ampforwp_is_amp_endpoint() {
  	return false !== get_query_var( 'amp', false );
  }
}


// checking if parent plugin is active or not ?
add_action( 'admin_init','ampforwp_parent_plugin_check');
function ampforwp_parent_plugin_check() {
	$amp_plugin_activation_check = ampforwp_is_plugin_active( AMPFORWP_WP_AMP_PLUGIN );
	if ( $amp_plugin_activation_check ) {
		// set_transient( 'ampforwp_parent_plugin_check', true, 30 );
	} else {
		delete_option( 'ampforwp_parent_plugin_check');
	}
}
