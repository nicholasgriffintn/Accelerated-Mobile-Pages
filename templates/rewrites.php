<?php
// Rewrite the Endpoints after the plugin is activate, as priority is set to 11
add_action( 'init', 'ampforwp_add_custom_post_support',11);
function ampforwp_add_custom_post_support() {
	global $redux_builder_amp;
	if( $redux_builder_amp['amp-on-off-for-all-pages'] ) {
		add_rewrite_endpoint( AMPFORWP_AMP_QUERY_VAR, EP_PAGES | EP_PERMALINK | EP_ALL_ARCHIVES | EP_ROOT );
		add_post_type_support( 'page', AMPFORWP_AMP_QUERY_VAR );
	}
}


// Add Custom Rewrite Rule to make sure pagination & redirection is working correctly
add_action( 'init', 'ampforwp_add_custom_rewrite_rules' );
function ampforwp_add_custom_rewrite_rules() {

  // For Homepage
  add_rewrite_rule(
    'amp/?$',
    'index.php?amp',
    'top'
  );

  // For Homepage with Pagination
  add_rewrite_rule(
    'amp/page/([0-9]{1,})/?$',
    'index.php?amp&paged=$matches[1]',
    'top'
  );

  // For category pages
  $rewrite_category = get_option('category_base');
  if (! empty($rewrite_category)) {
    $rewrite_category = get_option('category_base');
  } else {
    $rewrite_category = 'category';
  }

  add_rewrite_rule(
    $rewrite_category.'\/(.+?)\/amp/?$',
    'index.php?amp&category_name=$matches[1]',
    'top'
  );

  // For category pages with Pagination
  add_rewrite_rule(
    $rewrite_category.'\/(.+?)\/amp\/page\/?([0-9]{1,})\/?$',
    'index.php?amp&category_name=$matches[1]&paged=$matches[2]',
    'top'
  );

  // For tag pages
  $rewrite_tag = get_option('tag_base');
  if (! empty($rewrite_tag)) {
    $rewrite_tag = get_option('tag_base');
  } else {
    $rewrite_tag = 'tag';
  }

  add_rewrite_rule(
    $rewrite_tag.'\/(.+?)\/amp/?$',
    'index.php?amp&tag=$matches[1]',
    'top'
  );

  // For tag pages with Pagination
  add_rewrite_rule(
    $rewrite_tag.'\/(.+?)\/amp\/page\/?([0-9]{1,})\/?$',
    'index.php?amp&tag=$matches[1]&paged=$matches[2]',
    'top'
  );
}


register_activation_hook( __FILE__, 'ampforwp_rewrite_activation', 20 );
function ampforwp_rewrite_activation() {

  ampforwp_add_custom_post_support();
  ampforwp_add_custom_rewrite_rules();
  
  // Flushing rewrite urls ONLY on activation
	global $wp_rewrite;
	$wp_rewrite->flush_rules();

  // Set transient for Welcome page
	set_transient( 'ampforwp_welcome_screen_activation_redirect', true, 30 );

}


register_deactivation_hook( __FILE__, 'ampforwp_rewrite_deactivate', 20 );
function ampforwp_rewrite_deactivate() {
	// Flushing rewrite urls ONLY on deactivation
	global $wp_rewrite;
	$wp_rewrite->flush_rules();

	// Remove transient for Welcome page
	delete_transient( 'ampforwp_welcome_screen_activation_redirect');
}