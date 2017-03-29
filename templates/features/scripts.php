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

// Add required Javascripts for Design 3
add_filter( 'amp_post_template_data', 'ampforwp_add_design3_required_scripts', 100 );
function ampforwp_add_design3_required_scripts( $data ) {
	global $redux_builder_amp;
	$amp_menu_has_child = get_transient( 'ampforwp_has_nav_child' );
	// Add Scripts only when AMP Menu is Enabled
	if( has_nav_menu( 'amp-menu' ) ) {
		if ( empty( $data['amp_component_scripts']['amp-accordion'] ) ) {
			$data['amp_component_scripts']['amp-accordion'] = AMPFORWP_ACCORDIAN_SCRIPT;
		}
	}
	// Add Scripts only when Homepage AMP Featured Slider is Enabled
	if( is_home() ) {
		if ( $redux_builder_amp['amp-design-3-featured-slider'] == 1 && $redux_builder_amp['amp-design-selector'] == 3 && $redux_builder_amp['amp-frontpage-select-option'] == 0 ) {
			if ( empty( $data['amp_component_scripts']['amp-carousel'] ) ) {
				$data['amp_component_scripts']['amp-carousel'] = AMPFORWP_CAROUSEL_SCRIPT;
			}
		}
	}
	return $data;
}


//	add_action('amp_post_template_head','ampforwp_register_social_sharing_script');
function ampforwp_register_social_sharing_script() {
	if( is_socialshare_or_socialsticky_enabled_in_ampforwp() ) { ?>
		<script async custom-element="amp-social-share" src="https://cdn.ampproject.org/v0/amp-social-share-0.1.js"></script> <?php
 	}
}


// 6. Add required Javascripts for extra AMP features
add_action('amp_post_template_head','ampforwp_register_additional_scripts', 20);
function ampforwp_register_additional_scripts() {
	global $redux_builder_amp;
	if( is_page() ) { ?>
		<script async custom-element="amp-form" src="<?php echo AMPFORWP_FORM_SCRIPT; ?>"></script> <?php
	}

	if( $redux_builder_amp['enable-single-social-icons'] == true || AMPFORWP_DM_SOCIAL_CHECK === 'true' )  {
		if( is_singular() ) {
			if( is_socialshare_or_socialsticky_enabled_in_ampforwp() ) { ?>
				<script async custom-element="amp-social-share" src="<?php echo AMPFORWP_SOCIAL_SHARE_SCRIPT; ?>"></script> <?php
			 }
		}
	}

	if($redux_builder_amp['amp-frontpage-select-option'] == 1)  {
		 if( $redux_builder_amp['enable-single-social-icons'] == true || AMPFORWP_DM_SOCIAL_CHECK === 'true' )  {
			if( is_home() ) {
				if( is_socialshare_or_socialsticky_enabled_in_ampforwp() ) { ?>
				<script async custom-element="amp-social-share" src="<?php echo AMPFORWP_SOCIAL_SHARE_SCRIPT; ?>"></script> <?php
			 }
			}
		}
	}
	// Check if any of the ads are enabled then only load ads script
	//	moved this code to its own function and done the AMP way
}

// 6.1 Adding Analytics Scripts
add_action('amp_post_template_head','ampforwp_register_analytics_script', 20);
function ampforwp_register_analytics_script(){ ?>
	<script async custom-element="amp-analytics" src="<?php echo AMPFORWP_ANALYTICS_SCRIPT; ?>"></script> <?php
}
