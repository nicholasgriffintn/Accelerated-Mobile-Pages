<?php
/************************************************************************
*
* All the functions
*
* Will be Merged and this file will be deleted
*
*************************************************************************/
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

add_filter( 'amp_post_template_data', 'ampforwp_add_lightbox_and_form_scripts');
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
	if( ampforwp_design_selector() == 3) {
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
	}
	return $data;
}


//	add_action('amp_post_template_head','ampforwp_register_social_sharing_script');
function ampforwp_register_social_sharing_script( $data ) {
	if( is_socialshare_or_socialsticky_enabled_in_ampforwp() ) {
		if ( empty( $data['amp_component_scripts']['amp-social-share'] ) ) {
			$data['amp_component_scripts']['amp-social-share'] = AMPFORWP_SOCIAL_SHARE_SCRIPT;
		}
 	}
	return $data;
}


// 6. Add required Javascripts for extra AMP features
add_filter('amp_post_template_data','ampforwp_register_additional_scripts', 20);
function ampforwp_register_additional_scripts( $data ) {
	global $redux_builder_amp;
	if( is_page() ) {
		if ( empty( $data['amp_component_scripts']['amp-form'] ) ) {
			$data['amp_component_scripts']['amp-form'] = AMPFORWP_FORM_SCRIPT;
		}
	}

	if( $redux_builder_amp['enable-single-social-icons'] == true || AMPFORWP_DM_SOCIAL_CHECK === 'true' )  {
		if( is_singular() ) {
			if( is_socialshare_or_socialsticky_enabled_in_ampforwp() ) {
				if ( empty( $data['amp_component_scripts']['amp-social-share'] ) ) {
					$data['amp_component_scripts']['amp-social-share'] = AMPFORWP_SOCIAL_SHARE_SCRIPT;
				}
			 }
		}
	}

	if($redux_builder_amp['amp-frontpage-select-option'] == 1)  {
		 if( $redux_builder_amp['enable-single-social-icons'] == true || AMPFORWP_DM_SOCIAL_CHECK === 'true' )  {
			if( is_home() ) {
				if( is_socialshare_or_socialsticky_enabled_in_ampforwp() ) {
					if ( empty( $data['amp_component_scripts']['amp-social-share'] ) ) {
						$data['amp_component_scripts']['amp-social-share'] = AMPFORWP_SOCIAL_SHARE_SCRIPT;
					}
			 }
			}
		}
	}
	return $data;
}


// 6.1 Adding Analytics Scripts
add_filter('amp_post_template_data','ampforwp_register_analytics_script', 20);
function ampforwp_register_analytics_script( $data ){
	if ( empty( $data['amp_component_scripts']['amp-analytics'] ) ) {
		$data['amp_component_scripts']['amp-analytics'] = AMPFORWP_ANALYTICS_SCRIPT;
	}
	return $data;
}


add_filter( 'amp_post_template_data', 'ampforwp_add_amp_related_scripts', 20 );
function ampforwp_add_amp_related_scripts( $data ) {
	global $redux_builder_amp;
	// Adding Sidebar Script
	if ( empty( $data['amp_component_scripts']['amp-sidebar'] ) ) {
		$data['amp_component_scripts']['amp-sidebar'] = AMPFORWP_SIDE_BAR_SCRIPT;
	}
	return $data;
}


add_filter( 'amp_post_template_data', 'ampforwp_add_disqus_scripts' );
function ampforwp_add_disqus_scripts( $data ) {
	global $redux_builder_amp;
	if ( $redux_builder_amp['ampforwp-disqus-comments-support'] && is_singular() ) {
		if( $redux_builder_amp['ampforwp-disqus-comments-name'] !== '' ) {
			if ( empty( $data['amp_component_scripts']['amp-iframe'] ) ) {
				$data['amp_component_scripts']['amp-iframe'] = AMPFORWP_I_FRAME_SCRIPT;
			}
		}
	}
	// remove direction attribute from the AMP HTMl #541
	unset( $data['html_tag_attributes']['dir'] );
	return $data;
}
