<?php global $redux_builder_amp;  ?>
<!doctype html>
<html amp <?php echo AMP_HTML_Utils::build_attributes_string( $this->get( 'html_tag_attributes' ) ); ?>>
<head>
	<meta charset="utf-8">
  <link rel="dns-prefetch" href="https://cdn.ampproject.org">
	<?php
	if ( is_home() || is_front_page()  || ( is_archive() && $redux_builder_amp['ampforwp-archive-support'] ) ){
		global $wp;
		$current_archive_url = home_url( $wp->request );
		$amp_url 	= trailingslashit($current_archive_url);
		$remove 	= '/'. AMPFORWP_AMP_QUERY_VAR;
		$amp_url 	= str_replace($remove, '', $amp_url) ;
	} ?>
	<link rel="canonical" href="<?php echo $amp_url ?>">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<?php ampforwp_the_template_head( $this ); ?>

	<?php ampforwp_the_css( $this ); ?>
</head>
<body class="amp_home_body archives_body design_3_wrapper">
<?php $this->load_parts( array( 'header-bar' ) ); ?>

<?php do_action( 'ampforwp_after_header', $this );
?>
<main> <?php
	do_action('ampforwp_post_before_loop') ;
	do_action('ampforwp_loop') ;
	do_action('ampforwp_post_after_loop') ; ?>
</main>
<?php do_action( 'amp_post_template_above_footer', $this ); ?>
<?php $this->load_parts( array( 'footer' ) ); ?>
<?php do_action( 'amp_post_template_footer', $this ); ?>
</body>
</html>