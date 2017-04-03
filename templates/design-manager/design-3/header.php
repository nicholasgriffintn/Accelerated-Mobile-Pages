<?php global $redux_builder_amp;
// TODO: Also what is this check in line no 4 "is_home() && $redux_builder_amp['amp-frontpage-select-option'] " can you explain and why you are using? and don't we have a better check? Same thing in footer.php too.

if( is_singular() || ( is_home() && $redux_builder_amp['amp-frontpage-select-option'] ) ){
  $is_amp_front_page = is_amp_front_page();
  if ( $is_amp_front_page ) {
    $front_page_id = $redux_builder_amp['amp-frontpage-select-option-pages'];
    $amp_post_template_object = new AMP_Post_Template( $front_page_id );
    $post_data_object = $amp_post_template_object;
  } else {
    $post_data_object = $this;
  }
  // TODO: Jab we have same hook name and creating the object on the top, then why we need else and the code between line 19 and 24 remove that code

  ?>
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
