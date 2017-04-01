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


// # Util Function
//52. Adding a generalized sanitizer function for purifiying normal html to amp-html
// @param $input : content to be sanitized
function ampforwp_sanitize_html_to_amphtml( $input ) {
	$amp_custom_post_content_input = $input;
	if ( !empty( $amp_custom_post_content_input ) ) {
		$amp_custom_content = new AMP_Content( $amp_custom_post_content_input,
				apply_filters( 'amp_content_embed_handlers', array(
						'AMP_Twitter_Embed_Handler' => array(),
						'AMP_YouTube_Embed_Handler' => array(),
						'AMP_Instagram_Embed_Handler' => array(),
						'AMP_Vine_Embed_Handler' => array(),
						'AMP_Facebook_Embed_Handler' => array(),
						'AMP_Gallery_Embed_Handler' => array(),
				) ),
				apply_filters(  'amp_content_sanitizers', array(
						 'AMP_Style_Sanitizer' => array(),
						 'AMP_Blacklist_Sanitizer' => array(),
						 'AMP_Img_Sanitizer' => array(),
						 'AMP_Video_Sanitizer' => array(),
						 'AMP_Audio_Sanitizer' => array(),
						 'AMP_Iframe_Sanitizer' => array(
							 'add_placeholder' => true,
						 ),
				)  )
		);

		if ( $amp_custom_content ) {
			global $data;
			$data['amp_component_scripts'] 	= $amp_custom_content->get_amp_scripts();
			$data['post_amp_styles'] 		= $amp_custom_content->get_amp_styles();
			return $amp_custom_content->get_amp_content();
		}
		return '';
	}
}


if ( ! function_exists( 'is_amp_front_page' ) ) {
    function is_amp_front_page() {
        global $redux_builder_amp;
        if ( $redux_builder_amp['amp-frontpage-select-option'] == true && is_home() ) {
            return true;
        } else {
            return false;
        }
    }
}