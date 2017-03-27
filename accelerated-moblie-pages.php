<?php
/*
Plugin Name: Accelerated Mobile Pages
Plugin URI: https://wordpress.org/plugins/accelerated-mobile-pages/
Description: AMP for WP - Accelerated Mobile Pages for WordPress
Version: 0.9.46-beta
Author: Ahmed Kaludi, Mohammed Kaludi
Author URI: https://ampforwp.com/
Donate link: https://www.paypal.me/Kaludi/5
License: GPL2
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/******************************************/
//Very Important Global Constants
define('AMPFORWP_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
define('AMPFORWP_CONSTANTS_FILE', AMPFORWP_PLUGIN_DIR . 'templates/constants.php' );
require AMPFORWP_CONSTANTS_FILE;

// any changes to AMP_QUERY_VAR should be refelected here
define('AMPFORWP_AMP_QUERY_VAR', apply_filters( 'amp_query_var', 'amp' ) );
/******************************************/


/******************************************/
//Very Important Global Functions

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
/******************************************/


//All rewrites in this file
require_once AMPFORWP_REWRITES_FILE;


add_action( 'admin_init','ampforwp_parent_plugin_check');
function ampforwp_parent_plugin_check() {
	$amp_plugin_activation_check = ampforwp_is_plugin_active( AMPFORWP_WP_AMP_PLUGIN );
	if ( $amp_plugin_activation_check ) {
		// set_transient( 'ampforwp_parent_plugin_check', true, 30 );
	} else {
		delete_option( 'ampforwp_parent_plugin_check');
	}
}


// Redux panel inclusion code
if ( !class_exists( 'ReduxFramework' ) ) {
  require_once AMPFORWP_REDUX_CORE_FILE;
}
// Register all the main options
require_once AMPFORWP_REDUX_ADMIN_CONFIG_FILE;
require_once  AMPFORWP_BUG_REPORT_FILE;


//ampforwp Settings Featured
require_once AMPFORWP_SETTINGS_FILE;


if ( ! class_exists( 'Ampforwp_Init', false ) ) {
	class Ampforwp_Init {
		public function __construct(){
			// Load Files required for the plugin to run
			require AMPFORWP_INCLUDES_FILE;
			require AMPFORWP_REDIRECTION_FILE;
			require AMPFORWP_CLASS_INIT_FILE;
			new Ampforwp_Loader;
		}
	}
}
/*
 * Start the plugin.
 * Gentlemen start your engines
 */
function ampforwp_plugin_init() {
	if ( defined( 'AMP__FILE__' ) && defined('AMPFORWP_PLUGIN_DIR') ) {
		new Ampforwp_Init;
	}
}
add_action('init','ampforwp_plugin_init',9);


/*
* customized output widget
* to be used be used before or after Loop
*/
require AMPFORWP_WIDGET_FILE;
