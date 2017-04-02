<?php
// 2. Custom Design
// Add Homepage AMP file code
add_filter( 'amp_post_template_file', 'ampforwp_custom_template', 10, 3 );
function ampforwp_custom_template( $file, $type, $post ) {
  // Custom Homepage and Archive file
    global $redux_builder_amp;
    // Homepage and FrontPage
    if($redux_builder_amp['amp-frontpage-select-option'] == 0)  {
        if ( is_home() ) {
            if ( 'single' === $type ) {
              $file = AMPFORWP_DESIGN_SPECIFIC_INDEX_FILE ;
            }
        }
    } elseif ($redux_builder_amp['amp-frontpage-select-option'] == 1) {
        if ( is_home() ) {
            if ( 'single' === $type ) {
              $file = AMPFORWP_DESIGN_SPECIFIC_FRONTPAGE_FILE ;
            }
        }
    }

    // Archive Pages
    if ( is_archive() && $redux_builder_amp['ampforwp-archive-support'] )  {
        $file = AMPFORWP_DESIGN_SPECIFIC_ARCHIVE_FILE;
    }

    // Search pages
    if ( is_search() &&
        ( $redux_builder_amp['amp-design-1-search-feature'] ||
          $redux_builder_amp['amp-design-2-search-feature'] ||
          $redux_builder_amp['amp-design-3-search-feature'] )
        )  {
        $file = AMPFORWP_DESIGN_SPECIFIC_SEARCH_FILE ;
    }

    // Custom Single file
    if ( is_single() || is_page() ) {
      if('single' === $type && !('product' === $post->post_type )) {
        $file = AMPFORWP_DESIGN_SPECIFIC_SINGLE_FILE;
      }
    }

    if( ( ampforwp_design_selector() == 3 ) && ( is_home() || is_archive() || is_search() )) {
      $file = AMPFORWP_INDEX_FILE ;
    }

    if ( is_amp_front_page() && ampforwp_design_selector() == 3) {
      $file = AMPFORWP_SINGLE_FILE;
    }

    return $file;
}


// 3. Custom Style files
add_filter( 'amp_post_template_file', 'ampforwp_set_custom_style', 10, 3 );
function ampforwp_set_custom_style( $file, $type, $post ) {
  if ( 'style' === $type ) {
    $file = '';
  }
  return $file;
}

//3.5
add_filter( 'amp_post_template_file', 'ampforwp_empty_filter', 10, 3 );
function ampforwp_empty_filter( $file, $type, $post ) {
  if ( 'empty-filter' === $type ) {
    $file = AMPFORWP_EMPTY_FILTER_FILE;
  }
  return $file;
}


// 4. Custom Header files
// TODO: this filter will be removed in future and use ampforwp_the_header_bar hook instead
add_filter( 'amp_post_template_file', 'ampforwp_custom_header', 10, 3 );
function ampforwp_custom_header( $file, $type, $post ) {
  if ( 'header-bar' === $type ) {
    $file = AMPFORWP_DESIGN_SPECIFIC_HEADER_BAR_FILE;
  }
  return $file;
}

add_filter( 'amp_post_template_file', 'ampforwp_design_3_custom_header', 10, 3 );
function ampforwp_design_3_custom_header( $file, $type, $post ) {
  if ( 'd3-header' === $type ) {
    $file = AMPFORWP_HEADER_BAR_FILE;
  }
  return $file;
}

add_filter( 'amp_post_template_file', 'ampforwp_design_3_custom_footer', 10, 3 );
function ampforwp_design_3_custom_footer( $file, $type, $post ) {
  if ( 'd3-footer' === $type ) {
    $file = AMPFORWP_FOOTER_BAR_FILE;
  }
  return $file;
}

// 4.1 Custom Meta-Author files
add_filter( 'amp_post_template_file', 'ampforwp_set_custom_meta_author', 10, 3 );
function ampforwp_set_custom_meta_author( $file, $type, $post ) {
  if ( 'meta-author' === $type ) {
    $file = AMPFORWP_EMPTY_FILTER_FILE;
  }
  return $file;
}
// 4.2 Custom Meta-Taxonomy files
add_filter( 'amp_post_template_file', 'ampforwp_set_custom_meta_taxonomy', 10, 3 );
function ampforwp_set_custom_meta_taxonomy( $file, $type, $post ) {
  if ( 'meta-taxonomy' === $type ) {
    $file = AMPFORWP_EMPTY_FILTER_FILE;
  }
  return $file;
}


// 7. Footer for AMP Pages
// TODO: this filter will be removed in future and use ampforwp_the_footer - ampforwp_global_after_footer hook instead
add_filter( 'amp_post_template_file', 'ampforwp_custom_footer', 10, 3 );
function ampforwp_custom_footer( $file, $type, $post ) {
  if ( 'footer' === $type ) {
    $file = AMPFORWP_DESIGN_SPECIFIC_FOOTER_FILE;
  }
  return $file;
}
