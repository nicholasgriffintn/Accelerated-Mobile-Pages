<?php
//**************************************************************************
// Dont touch or Modify the code between the Comments
// The code is highly required for the things below to function correctly
global $redux_builder_amp;
$is_amp_front_page = is_amp_front_page();
if ( $is_amp_front_page ) {
  $front_page_id = $redux_builder_amp['amp-frontpage-select-option-pages'];
  $amp_post_template_object = new AMP_Post_Template( $front_page_id );
  $post_data_object = $amp_post_template_object;
} else {
  $post_data_object = $this;
}
//**************************************************************************

  $post_data_object->load_parts( array( 'd3-header' ) ); ?>

  <body class="<?php echo ampforwp_body_class() ?> "> <?php

    do_action('ampforwp_the_header_bar' , $post_data_object);

    do_action( 'ampforwp_after_header', $post_data_object );

    $post_data_object->load_parts( array( 'd3-footer' ) ); ?>
