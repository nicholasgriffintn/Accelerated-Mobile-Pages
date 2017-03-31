<?php
// Global Constants -- Start Here --

global $redux_builder_amp;
// General Constants
define('AMPFORWP_VERSION','0.9.45.1');
// any changes to AMP_QUERY_VAR should be refelected here
define('AMPFORWP_AMP_QUERY_VAR', apply_filters( 'amp_query_var', 'amp' ) );

// Constants For Important Files
define( 'AMPFORWP_DESIGN_MANGER_FILE', AMPFORWP_PLUGIN_DIR . 'templates/features/general/design-manager.php');
define( 'AMPFORWP_CONTENT_ELEMENTS_FUNCTIONS_FILE', AMPFORWP_PLUGIN_DIR . 'templates/features/elements-single.php');
define( 'AMPFORWP_LOOP_FUNCTIONS_FILE', AMPFORWP_PLUGIN_DIR . 'templates/features/elements-general.php');
define( 'AMPFORWP_CUSTOMIZER_FILE', AMPFORWP_PLUGIN_DIR . 'templates/customizer/customizer.php');
define( 'AMPFORWP_CUSTOM_AMP_CONTENT_FILE', AMPFORWP_PLUGIN_DIR . 'templates/features/general/custom-amp-content.php');
define( 'AMPFORWP_SEARCH_FILE', AMPFORWP_PLUGIN_DIR . 'templates/features/general/search.php');
define('AMPFORWP_DISQUS_URL', AMPFORWP_PLUGIN_DIR.'includes/disqus.php');
define('AMPFORWP_IMAGE_DIR', WP_PLUGIN_URL.'/accelerated-mobile-pages/images');
define('AMPFORWP_BUG_REPORT_FILE', AMPFORWP_PLUGIN_DIR .'templates/features/general/report-bugs.php');
define('AMPFORWP_REDUX_ADMIN_CONFIG_FILE', AMPFORWP_PLUGIN_DIR . '/includes/options/admin-config.php');
define('AMPFORWP_REDUX_CORE_FILE', AMPFORWP_PLUGIN_DIR . '/includes/options/redux-core/framework.php');
define('AMPFORWP_WELCOME_FILE', AMPFORWP_PLUGIN_DIR .'/includes/welcome.php');
define('AMPFORWP_WIDGET_FILE', AMPFORWP_PLUGIN_DIR.'/templates/features/general/widget.php');
define('AMPFORWP_FEATURES_FILE', AMPFORWP_PLUGIN_DIR . '/templates/features/features.php');
define('AMPFORWP_SETTINGS_FILE', AMPFORWP_PLUGIN_DIR . 'templates/features/general/settings.php');
define('AMPFORWP_REWRITES_FILE', AMPFORWP_PLUGIN_DIR . 'includes/rewrites.php');
define('AMPFORWP_COMPATIIBLITY_FILE', AMPFORWP_PLUGIN_DIR . 'templates/features/compatibility.php');
define('AMPFORWP_GLOBAL_UTILS_FILE', AMPFORWP_PLUGIN_DIR . 'templates/global-untils.php');
define('AMPFORWP_SCRIPTS_FILE', AMPFORWP_PLUGIN_DIR . 'templates/features/scripts.php');
define('AMPFORWP_WOOCOMMERCE_FILE', AMPFORWP_PLUGIN_DIR . 'templates/features/single/woocommerece-shortcode.php');
define('AMPFORWP_DISQUS_HOST', "https://ampforwp.appspot.com/?api=". AMPFORWP_DISQUS_URL);


//includes related files
define('AMPFORWP_INCLUDES_FILE', AMPFORWP_PLUGIN_DIR .'/includes/includes.php');
define('AMPFORWP_REDIRECTION_FILE', AMPFORWP_PLUGIN_DIR.'/includes/redirect.php');
define('AMPFORWP_CLASS_INIT_FILE', AMPFORWP_PLUGIN_DIR .'/classes/class-init.php');

// 3rd Party plugin File Names with their plugin folder
define( 'AMPFORWP_CUSTOM_POST_TYPE_PLUGIN', 'amp-custom-post-type/amp-custom-post-type.php' );
define( 'AMPFORWP_WOO_COMMERCE_PLUGIN', 'amp-woocommerce/amp-woocommerce.php' );
define( 'AMPFORWP_WP_AMP_PLUGIN', 'amp/amp.php' );
define( 'AMPFORWP_ADS_PLUGIN', 'amp-incontent-ads/amptoolkit-incontent-ads.php' );
define( 'AMPFORWP_COMMENTS_PLUGIN', 'amp-comments/amp-comments.php' );

// Scripts Constants
define( 'AMPFORWP_FORM_SCRIPT', 'https://cdn.ampproject.org/v0/amp-form-0.1.js' );
define( 'AMPFORWP_SOCIAL_SHARE_SCRIPT', 'https://cdn.ampproject.org/v0/amp-social-share-0.1.js' );
define( 'AMPFORWP_ANALYTICS_SCRIPT', 'https://cdn.ampproject.org/v0/amp-analytics-0.1.js' );
define( 'AMPFORWP_SIDE_BAR_SCRIPT', 'https://cdn.ampproject.org/v0/amp-sidebar-0.1.js' );
define( 'AMPFORWP_NOTIFICATIONS_SCRIPT', 'https://cdn.ampproject.org/v0/amp-user-notification-0.1.js' );
define( 'AMPFORWP_AMP_AD_SCRIPT', 'https://cdn.ampproject.org/v0/amp-ad-0.1.js' );
define( 'AMPFORWP_LIGHT_BOX_SCRIPT', 'https://cdn.ampproject.org/v0/amp-lightbox-0.1.js' );
define( 'AMPFORWP_CAROUSEL_SCRIPT', 'https://cdn.ampproject.org/v0/amp-carousel-0.1.js' );
define( 'AMPFORWP_ACCORDIAN_SCRIPT', 'https://cdn.ampproject.org/v0/amp-accordion-0.1.js' );
define( 'AMPFORWP_I_FRAME_SCRIPT', 'https://cdn.ampproject.org/v0/amp-iframe-0.1.js' );

// Global Constants -- End Here --


// Design Selector
add_action('pre_amp_render_post','ampforwp_design_selector', 11 );
function ampforwp_design_selector() {

    global $redux_builder_amp;
    if ( $redux_builder_amp ) {
        return $redux_builder_amp['amp-design-selector'];
    } else {
        return 2;
    }

}

add_action('pre_amp_render_post','ampforwp_defining_constants', 11 );
function ampforwp_defining_constants() {
  if ( ! ampforwp_design_selector() ) {
    $ampforwp_design_selector   = 2;
  } else {
    $ampforwp_design_selector  = ampforwp_design_selector();
  }


  //elemetns FIle paths
  define( 'AMPFORWP_DESIGN_SPECIFIC_STYLE_FILE', AMPFORWP_PLUGIN_DIR . 'templates/design-manager/design-'. ampforwp_design_selector() . '/style.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_TITLE_FILE', AMPFORWP_PLUGIN_DIR . 'templates/design-manager/design-'. ampforwp_design_selector() .'/elements/title.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_META_INFO_FILE', AMPFORWP_PLUGIN_DIR . 'templates/design-manager/design-'. ampforwp_design_selector() .'/elements/meta-info.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_FEATURED_IMAGE_FILE', AMPFORWP_PLUGIN_DIR . 'templates/design-manager/design-'. ampforwp_design_selector() .'/elements/featured-image.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_CONTENT_FILE', AMPFORWP_PLUGIN_DIR . 'templates/design-manager/design-'. ampforwp_design_selector() .'/elements/content.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_META_TAXONOMY_FILE', AMPFORWP_PLUGIN_DIR . 'templates/design-manager/design-'. ampforwp_design_selector() .'/elements/meta-taxonomy.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_SOCIAL_ICONS_FILE', AMPFORWP_PLUGIN_DIR . 'templates/design-manager/design-'. ampforwp_design_selector() .'/elements/social-icons.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_COMMENTS_FILE', AMPFORWP_PLUGIN_DIR . 'templates/design-manager/design-'. ampforwp_design_selector() .'/elements/comments.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_SIMPLE_COMMENTS_FILE', AMPFORWP_PLUGIN_DIR . 'templates/design-manager/design-'. ampforwp_design_selector() .'/elements/simple-comment-button.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_RELEATED_POST_FILE', AMPFORWP_PLUGIN_DIR . 'templates/design-manager/design-'. ampforwp_design_selector() .'/elements/related-posts.php');


  //Main Files Constants
  define( 'AMPFORWP_DESIGN_SPECIFIC_INDEX_FILE',  AMPFORWP_PLUGIN_DIR . '/templates/design-manager/design-'. ampforwp_design_selector() .'/index.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_FRONTPAGE_FILE',  AMPFORWP_PLUGIN_DIR . '/templates/design-manager/design-'. ampforwp_design_selector() .'/frontpage.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_ARCHIVE_FILE',  AMPFORWP_PLUGIN_DIR . '/templates/design-manager/design-'. ampforwp_design_selector() .'/archive.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_SEARCH_FILE',  AMPFORWP_PLUGIN_DIR . '/templates/design-manager/design-'. ampforwp_design_selector() .'/search.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_SINGLE_FILE',  AMPFORWP_PLUGIN_DIR . '/templates/design-manager/design-'. ampforwp_design_selector() .'/single.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_HEADER_BAR_FILE', AMPFORWP_PLUGIN_DIR . '/templates/design-manager/design-'. ampforwp_design_selector() .'/header-bar.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_FOOTER_FILE', AMPFORWP_PLUGIN_DIR . '/templates/design-manager/design-'. ampforwp_design_selector() .'/footer.php');
  define( 'AMPFORWP_INDEX_FILE',  AMPFORWP_PLUGIN_DIR . '/templates/design-manager/design-3/index.php');
  define( 'AMPFORWP_SINGLE_FILE',  AMPFORWP_PLUGIN_DIR . '/templates/design-manager/design-3/single.php');
  define( 'AMPFORWP_EMPTY_FILTER_FILE',  AMPFORWP_PLUGIN_DIR . '/templates/design-manager/empty-filter.php');

}