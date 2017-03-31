<?php
// 14. Adds a meta box to the post editing screen for AMP on-off on specific pages.
/**
 * Adds a meta box to the post editing screen for AMP on-off on specific pages
*/
add_action( 'add_meta_boxes', 'ampforwp_title_custom_meta' );
function ampforwp_title_custom_meta() {
  global $redux_builder_amp;
  $args = array(
     'public'   => true,
  );

  $output = 'names'; // 'names' or 'objects' (default: 'names')
  $operator = 'and'; // 'and' or 'or' (default: 'and')

  $post_types = get_post_types( $args, $output, $operator );

  if ( $post_types ) { // If there are any custom public post types.

    foreach ( $post_types  as $post_type ) {

      if( $post_type == 'amp-cta' ) {
          continue;
      }

      if( $post_type !== 'page' ) {
        add_meta_box( 'ampforwp_title_meta', __( 'Show AMP for Current Page?' ), 'ampforwp_title_callback', $post_type,'side' );
      }

      if( $redux_builder_amp['amp-on-off-for-all-pages'] && $post_type == 'page' ) {
          add_meta_box( 'ampforwp_title_meta', __( 'Show AMP for Current Page?' ), 'ampforwp_title_callback','page','side' );
      }

    }
  }
}

/**
 * Outputs the content of the meta box for AMP on-off on specific pages
 */
function ampforwp_title_callback( $post ) {
  wp_nonce_field( basename( __FILE__ ), 'ampforwp_title_nonce' );
  $ampforwp_stored_meta = get_post_meta( $post->ID );

  // TODO: Move the data storage code, to Save meta Box area as it is not a good idea to update an option everytime, try adding this code inside ampforwp_title_meta_save()
  // This code needs a rewrite.
  if ( $ampforwp_stored_meta['ampforwp-amp-on-off'][0] == 'hide-amp') {
    $exclude_post_value = get_option('ampforwp_exclude_post');
    if ( $exclude_post_value == null ) {
      $exclude_post_value[] = 0;
    }
    if ( $exclude_post_value ) {
      if ( ! in_array( $post->ID, $exclude_post_value ) ) {
        $exclude_post_value[] = $post->ID;
        update_option('ampforwp_exclude_post', $exclude_post_value);
      }
    }
  } else {
    $exclude_post_value = get_option('ampforwp_exclude_post');
    if ( $exclude_post_value == null ) {
      $exclude_post_value[] = 0;
    }
    if ( $exclude_post_value ) {
      if ( in_array( $post->ID, $exclude_post_value ) ) {
        $exclude_ids = array_diff($exclude_post_value, array($post->ID) );
        update_option('ampforwp_exclude_post', $exclude_ids);
      }
    }

  } ?>
  <p>
    <div class="prfx-row-content">
      <label for="meta-radio-one">
        <input type="radio" name="ampforwp-amp-on-off" id="meta-radio-one" value="default"  checked="checked" <?php if ( isset ( $ampforwp_stored_meta['ampforwp-amp-on-off'] ) ) checked( $ampforwp_stored_meta['ampforwp-amp-on-off'][0], 'default' ); ?>>
        <?php _e( 'Show' )?>
      </label>
      <label for="meta-radio-two">
        <input type="radio" name="ampforwp-amp-on-off" id="meta-radio-two" value="hide-amp" <?php if ( isset ( $ampforwp_stored_meta['ampforwp-amp-on-off'] ) ) checked( $ampforwp_stored_meta['ampforwp-amp-on-off'][0], 'hide-amp' ); ?>>
        <?php _e( 'Hide' )?>
      </label>
    </div>
  </p> <?php
}

/**
 * Saves the custom meta input for AMP on-off on specific pages
 */
 add_action( 'save_post', 'ampforwp_title_meta_save' );
function ampforwp_title_meta_save( $post_id ) {

  // Checks save status
  $is_autosave = wp_is_post_autosave( $post_id );
  $is_revision = wp_is_post_revision( $post_id );
  $is_valid_nonce = ( isset( $_POST[ 'ampforwp_title_nonce' ] ) && wp_verify_nonce( $_POST[ 'ampforwp_title_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

  // Exits script depending on save status
  if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
      return;
  }

  // Checks for radio buttons and saves if needed
  if( isset( $_POST[ 'ampforwp-amp-on-off' ] ) ) {
      $ampforwp_amp_status = sanitize_text_field( $_POST[ 'ampforwp-amp-on-off' ] );
      update_post_meta( $post_id, 'ampforwp-amp-on-off', $ampforwp_amp_status );
  }

}

add_filter('amp_frontend_show_canonical','ampforwp_hide_amp_for_specific_pages');
function ampforwp_hide_amp_for_specific_pages($input){
  global $post;
  $ampforwp_amp_status = get_post_meta($post->ID, 'ampforwp-amp-on-off', true);
  if ( $ampforwp_amp_status == 'hide-amp' ) {
    $input = false;
  }
  return $input;
}

// 28. Properly removes AMP if turned off from Post panel
add_filter( 'amp_skip_post', 'ampforwp_skip_amp_post', 10, 3 );
function ampforwp_skip_amp_post( $skip, $post_id, $post ) {
  $ampforwp_amp_post_on_off_meta = get_post_meta( $post->ID , 'ampforwp-amp-on-off' , true );
  if( $ampforwp_amp_post_on_off_meta === 'hide-amp' ) {
    $skip = true;
  }
  return $skip;
}