<?php global $redux_builder_amp;
if( is_singular() || ( is_home() && $redux_builder_amp['amp-frontpage-select-option'] ) ){
  // TODO : all direct functions to be converted into hooks
  $is_amp_front_page = is_amp_front_page();
  if ( $is_amp_front_page ) {
    $front_page_id = $redux_builder_amp['amp-frontpage-select-option-pages'];
    $amp_post_template_object = new AMP_Post_Template( $front_page_id );
    $post_data_object = $amp_post_template_object;
  } else {
    $post_data_object = $this;
  }?>
  <!doctype html>
  <html amp <?php echo apply_filters( 'ampforwp_lang_filter', $post_data_object); ?> >
    <head>
      <?php do_action('ampforwp_head', $post_data_object); ?>
    </head> <?php
} elseif( is_home() || is_archive() || is_search() ) { ?>
  <!doctype html>
  <html amp <?php echo apply_filters( 'ampforwp_lang_filter', $this); ?> >
  	<head>
  		<?php do_action('ampforwp_head', $this); ?>
  	</head>
<?php } ?>
