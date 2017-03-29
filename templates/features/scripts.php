<?php
// 49. Properly adding ad Script the AMP way
add_filter( 'amp_post_template_data', 'ampforwp_add_ads_scripts' );
function ampforwp_add_ads_scripts( $data ) {
	global $redux_builder_amp;
	if (	$redux_builder_amp['enable-amp-ads-1'] ||
				$redux_builder_amp['enable-amp-ads-2'] ||
				$redux_builder_amp['enable-amp-ads-3'] ||
				$redux_builder_amp['enable-amp-ads-4'] ) {
		if ( empty( $data['amp_component_scripts']['amp-ad'] ) ) {
			$data['amp_component_scripts']['amp-ad'] = AMPFORWP_AMP_AD_SCRIPT;
		}
	}
	return $data;
}


// 50. Properly adding noditification Scritps the AMP way
add_filter( 'amp_post_template_data', 'ampforwp_add_notification_scripts' );
function ampforwp_add_notification_scripts( $data ) {
	global $redux_builder_amp;
	if ( $redux_builder_amp['amp-enable-notifications'] == true ) {
		if ( empty( $data['amp_component_scripts']['amp-user-notification'] ) ) {
			$data['amp_component_scripts']['amp-user-notification'] = AMPFORWP_NOTIFICATIONS_SCRIPT;
		}
	}
	return $data;
}


// 48. Remove all unwanted scripts on search pages
add_filter( 'amp_post_template_data', 'ampforwp_remove_scripts_search_page' );
function ampforwp_remove_scripts_search_page( $data ) {
	if( is_search() ) {
		// Remove all unwanted scripts on search pages
		unset( $data['amp_component_scripts']);
	}
	return $data;
}


function ampforwp_add_lightbox_and_form_scripts( $data ) {
	if ( ampforwp_is_search_enabled() ) {
		global $redux_builder_amp;
		// Add Scripts only when Search is Enabled
		if( ampforwp_is_search_enabled() ) {
			if ( empty( $data['amp_component_scripts']['amp-lightbox'] ) ) {
				$data['amp_component_scripts']['amp-lightbox'] = AMPFORWP_LIGHT_BOX_SCRIPT;
			}
			if ( empty( $data['amp_component_scripts']['amp-form'] ) ) {
				$data['amp_component_scripts']['amp-form'] = AMPFORWP_FORM_SCRIPT;
			}
		}
	}
	return $data;
}