<?php
/*
 * Load Files only in the backend
 * As we don't need plugin activation code to run everytime the site loads
*/
if ( is_admin() ) {

	//Include Welcome page only on Admin pages
	require AMPFORWP_WELCOME_FILE;

  add_action('init','ampforwp_plugin_notice');
	function  ampforwp_plugin_notice() {

		if ( ! defined( 'AMP__FILE__' ) ) {
			add_action( 'admin_notices', 'ampforwp_plugin_not_found_notice' );
			function ampforwp_plugin_not_found_notice() {

        $current_screen = get_current_screen();
        if( $current_screen ->id == "plugin-install" || $current_screen ->id == "dashboard_page_ampforwp-welcome-page" || $current_screen ->id == "ampforwp-welcome-page" ) {
            return;
        } ?>

				<div class="notice notice-warning is-dismissible ampinstallation"> <?php
         add_thickbox(); ?>
	       <p>
          <strong><?php _e( 'AMP Installation requires one last step:', 'ampforwp' ); ?></strong> <?php _e( 'AMP by Automattic plugin is not active', 'ampforwp' ); ?>
	        <strong>
            <span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;"><a href="index.php?page=ampforwp-welcome-page"><?php _e( 'Continue Installation', 'ampforwp' ); ?></a> | <a href="https://www.youtube.com/embed/zzRy6Q_VGGc?TB_iframe=true&?rel=0&?autoplay=1" onclick="javascript:_gaq.push(['_trackEvent','outbound-article','https://www.youtube.com/embed/zzRy6Q_VGGc?TB_iframe=true&?rel=0&?autoplay=1']);" class="thickbox"><?php _e( 'More Information', 'ampforwp' ); ?></a>
            </span>
          </strong>
	       </p>
				</div> <?php

		  } // end of ampforwp_plugin_not_found_notice

  		add_action('admin_head','ampforwp_required_plugin_styling');
  		function ampforwp_required_plugin_styling() {
  			if ( ! defined( 'AMP__FILE__' ) ) { ?>
  				<style>
  					#toplevel_page_amp_options a .wp-menu-name:after {
  						content: "1";
  						background-color: #d54e21;
  						color: #fff;
  						border-radius: 10px;
  						font-size: 9px;
  				    line-height: 17px;
  				    font-weight: 600;
  				    padding: 3px 7px;
  				    margin-left: 5px;
  					}
  				</style> <?php
  			} ?>
  				<style>
  	        .notice, .notice-error, .is-dismissible, .ampinstallation{}
  					.plugin-card.plugin-card-amp:before{
              content: "FINISH INSTALLATION: Install & Activate this plugin ↓";
              font-weight: bold;
              float: right;
              position: relative;
              color: #dc3232;
              top: -28px;
              font-size: 18px;
  					}
            .plugin-action-buttons a{
              color: #fff
            }
  					.plugin-card.plugin-card-amp {
  						background: rgb(0, 165, 92);
  						color: #fff;
  					}
  					.plugin-card.plugin-card-amp .column-name a,
  					.plugin-card.plugin-card-amp .column-description a,
  					.plugin-card.plugin-card-amp .column-description p {
  						color: #fff;
  					}
  					.plugin-card-amp .plugin-card-bottom {
  						background: rgba(229, 255, 80, 0);
  					}
  				</style> <?php
  		}
		}
	}

 	// Add Settings Button in Plugin backend
 	if ( ! function_exists( 'ampforwp_plugin_settings_link' ) ) {
 		add_filter( 'plugin_action_links', 'ampforwp_plugin_settings_link', 10, 5 );
 		function ampforwp_plugin_settings_link( $actions, $plugin_file )  {
	 			static $plugin;
	 			if (!isset($plugin))
	 				$plugin = plugin_basename(__FILE__);
	 				if ($plugin == $plugin_file) {

	 					$settings = array('settings' => '<a href="admin.php?page=amp_options&tab=8">' . __('Settings', 'ampforwp') . '</a> | <a href="https://ampforwp.com/priority-support/#utm_source=options-panel&utm_medium=extension-tab_priority_support&utm_campaign=AMP%20Plugin">' . __('Premium Support', 'ampforwp') . '</a>');

						if ( ampforwp_is_plugin_active( AMPFORWP_WP_AMP_PLUGIN ) ) {
						    //if parent plugin is activated
								$actions = array_merge( $actions, $settings );
						} else{

							if( ampforwp_is_plugin_active( AMPFORWP_WP_AMP_PLUGIN ) ){
								$actions = array_merge( $actions, $settings );
							}else{
								$please_activate_parent_plugin = array('Please Activate Parent plugin' => '<a href="'.get_admin_url() .'index.php?page=ampforwp-welcome-page">' . __('<span style="color:#b30000">Action Required: Continue Installation</span>', 'ampforwp') . '</a>');
								$actions = array_merge( $please_activate_parent_plugin,$actions );
						 }
						}

	 				}
	 		return $actions;
 		}
 	}
} // is_admin() closing
