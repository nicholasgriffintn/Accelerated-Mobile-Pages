<?php

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

  define( 'AMPFORWP_DESIGN_SPECIFIC_STYLE_FILE', AMPFORWP_PLUGIN_DIR . 'templates/design-manager/design-'. $ampforwp_design_selector . '/style.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_TITLE_FILE', AMPFORWP_PLUGIN_DIR . 'templates/design-manager/design-'. ampforwp_design_selector() .'/elements/title.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_META_INFO_FILE', AMPFORWP_PLUGIN_DIR . 'templates/design-manager/design-'. ampforwp_design_selector() .'/elements/meta-info.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_FEATURED_IMAGE_FILE', AMPFORWP_PLUGIN_DIR . 'templates/design-manager/design-'. ampforwp_design_selector() .'/elements/featured-image.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_CONTENT_FILE', AMPFORWP_PLUGIN_DIR . 'templates/design-manager/design-'. ampforwp_design_selector() .'/elements/content.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_META_TAXONOMY_FILE', AMPFORWP_PLUGIN_DIR . 'templates/design-manager/design-'. ampforwp_design_selector() .'/elements/meta-taxonomy.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_SOCIAL_ICONS_FILE', AMPFORWP_PLUGIN_DIR . 'templates/design-manager/design-'. ampforwp_design_selector() .'/elements/social-icons.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_COMMENTS_FILE', AMPFORWP_PLUGIN_DIR . 'templates/design-manager/design-'. ampforwp_design_selector() .'/elements/comments.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_SIMPLE_COMMENTS_FILE', AMPFORWP_PLUGIN_DIR . 'templates/design-manager/design-'. ampforwp_design_selector() .'/elements/simple-comment-button.php');
  define( 'AMPFORWP_DESIGN_SPECIFIC_RELEATED_POST_FILE', AMPFORWP_PLUGIN_DIR . 'templates/design-manager/design-'. ampforwp_design_selector() .'/elements/related-posts.php');
}