<?php global $redux_builder_amp;
if( is_singular() || ( is_home() && $redux_builder_amp['amp-frontpage-select-option'] ) ){
  $is_amp_front_page = is_amp_front_page();
  if ( $is_amp_front_page ) {
    $front_page_id = $redux_builder_amp['amp-frontpage-select-option-pages'];
    $amp_post_template_object = new AMP_Post_Template( $front_page_id );
    $post_data_object = $amp_post_template_object;
  } else {
    $post_data_object = $this;
  }
// TODO: Jab we have same hook name and creating the object on the top, then why we need else and the code between line 17 and 24 remove that code
  do_action( 'amp_post_template_above_footer', $post_data_object );
  do_action('ampforwp_the_footer' , $post_data_object);
  do_action('ampforwp_global_after_footer' , $post_data_object);
  do_action( 'amp_post_template_footer', $post_data_object );

} elseif( is_home() || is_archive() || is_search() ) {

  do_action( 'amp_post_template_above_footer', $this );
  do_action('ampforwp_the_footer', $this );
  do_action('ampforwp_global_after_footer', $this );
  do_action( 'amp_post_template_footer', $this );

} ?>
</body>
</html>
