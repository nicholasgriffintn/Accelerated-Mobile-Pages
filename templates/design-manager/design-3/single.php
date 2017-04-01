<?php
// TODO : all direct functions to be converted into hooks
global $redux_builder_amp;
$is_amp_front_page = is_amp_front_page();
if ( $is_amp_front_page ) {
  $front_page_id = $redux_builder_amp['amp-frontpage-select-option-pages'];
  $amp_post_template_object = new AMP_Post_Template( $front_page_id );
  $page_data = $amp_post_template_object;
} else {
  $page_data = $this;
}?>
<!doctype html>
<html amp <?php echo apply_filters( 'ampforwp_lang_filter', $page_data); ?> >
  <head>
    <?php do_action('ampforwp_head', $page_data); ?>
  </head>
  <?php // TODO: a seperate function for body class ?>
  <body class="<?php echo is_front_page() ? "single-post design_3_wrapper" : is_single() ? "design_3_wrapper single-post" : "design_3_wrapper single-post amp-single-page" ?> ">
    <?php
    // TODO : all the hooks needs to pass the $this or $post_data parameter so we ca access the data of the current page and be in context
    do_action('ampforwp_the_header_bar');
    // TODO : add this code from elements-general.php and add use hook to add the code
    if( $front_page_id ) {?>
      <header class="amp-wp-article-header ampforwp-title amp-wp-content">
        <h1 class="amp-wp-title">
          <?php echo get_the_title( $front_page_id );?>
        </h1>
      </header> <?php
    }
    do_action( 'ampforwp_after_header', $page_data ); ?>
    <main>
      <article class="amp-wp-article">
        <?php do_action('ampforwp_post_before_design_elements');
        // TODO: add this code from elements-general.php and add use hook to add the code
        if ( $is_amp_front_page ) { ?>
          <div class="amp-wp-content the_content"> <?php
            $amp_custom_content_enable = get_post_meta($page_data->data['post_id'], 'ampforwp_custom_content_editor_checkbox', true);
            if ( ! $amp_custom_content_enable ) {
              echo $page_data->data['post_amp_content'];
            } else {
              echo $page_data->data['ampforwp_amp_content'];
            }
            do_action( 'ampforwp_after_post_content', $page_data ); ?>
          </div>
            <?php ampforwp_comments_pagination( $page_data->data['post_id'] ); ?>
          <div class="amp-wp-content post-pagination-meta">
            <?php $page_data->load_parts( apply_filters( 'amp_post_template_meta_parts', array( 'meta-taxonomy' ) ) ); ?>
          </div>
          <?php ampforwp_the_social_share();
        } else {
          $page_data->load_parts( apply_filters( 'ampforwp_design_elements', array( 'empty-filter' ) ) );
        } ?>
        <?php do_action('ampforwp_post_after_design_elements') ?>
      </article>
    </main> <?php
    do_action( 'amp_post_template_above_footer', $page_data );
      do_action('ampforwp_the_footer');
      do_action('ampforwp_global_after_footer');
    do_action( 'amp_post_template_footer', $page_data );?>
  </body>
</html>