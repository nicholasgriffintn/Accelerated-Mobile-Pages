<?php
// TODO : all direct functions to be converted into hooks
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
  <?php // TODO: a seperate function for body class
        // TODO: Check rel-canonical for Frontpage ?>
  <body class="<?php echo ampforwp_body_class() ?> ">
    <?php
    // TODO : all the hooks needs to pass the $this or $post_data parameter so we ca access the data of the current page and be in context
    do_action('ampforwp_the_header_bar' , $post_data_object);
    // TODO : add this code from elements-general.php and add use hook to add the code
    if( $front_page_id ) {?>
      <header class="amp-wp-article-header ampforwp-title amp-wp-content">
        <h1 class="amp-wp-title">
          <?php echo get_the_title( $front_page_id );?>
        </h1>
      </header> <?php
    }
    do_action( 'ampforwp_after_header', $post_data_object );

    $post_data_object->load_parts( array( 'd3-footer' ) ); ?>
