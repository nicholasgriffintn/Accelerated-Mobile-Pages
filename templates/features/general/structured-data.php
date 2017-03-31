<?php
// 12. Add Logo URL in the structured metadata
add_filter( 'amp_post_template_metadata', 'ampforwp_update_metadata', 10, 2 );
function ampforwp_update_metadata( $metadata, $post ) {
  global $redux_builder_amp;

  if (! empty( $redux_builder_amp['opt-media']['url'] ) ) {
    $structured_data_main_logo = $redux_builder_amp['opt-media']['url'];
  }

  if (! empty( $redux_builder_amp['amp-structured-data-logo']['url'] ) ) {
    $structured_data_logo = $redux_builder_amp['amp-structured-data-logo']['url'];
  }

  if ( $structured_data_logo ) {
    $structured_data_logo = $structured_data_logo;
  } else {
    $structured_data_logo = $structured_data_main_logo;
  }

  $metadata['publisher']['logo'] = array(
    '@type' 	=> 'ImageObject',
    'url' 		=>  $structured_data_logo ,
    'height' 	=> 36,
    'width' 	=> 190,
  );

  //code for adding 'description' meta from Yoast SEO
  if($redux_builder_amp['ampforwp-seo-yoast-custom-description']){
    if ( class_exists('WPSEO_Frontend') ) {
      $front = WPSEO_Frontend::get_instance();
      $desc = $front->metadesc( false );
      if ( $desc ) {
        $metadata['description'] = $desc;
      }

      // Code for Custom Frontpage Yoast SEO Description
      $post_id = $redux_builder_amp['amp-frontpage-select-option-pages'];
      if ( class_exists('WPSEO_Meta') ) {
        $custom_fp_desc = WPSEO_Meta::get_value('metadesc', $post_id );
        if ( is_home() && $redux_builder_amp['amp-frontpage-select-option'] ) {
          if ( $custom_fp_desc ) {
            $metadata['description'] = $custom_fp_desc;
          } else {
            unset( $metadata['description'] );
          }
        }
      }
    }
  } //End of code for adding 'description' meta from Yoast SEO

  return $metadata;
}

// 13. Add Custom Placeholder Image for Structured Data.
// if there is no image in the post, then use this image to validate Structured Data.
add_filter( 'amp_post_template_metadata', 'ampforwp_update_metadata_featured_image', 10, 2 );
function ampforwp_update_metadata_featured_image( $metadata, $post ) {
  global $redux_builder_amp;
  global $post;
  $post_id = get_the_ID() ;
  $post_image_id = get_post_thumbnail_id( $post_id );
  $structured_data_image = wp_get_attachment_image_src( $post_image_id, 'full' );
  $post_image_check = $structured_data_image;

  if ( $post_image_check == false) {
    if (! empty( $redux_builder_amp['amp-structured-data-placeholder-image']['url'] ) ) {
      $structured_data_image_url = $redux_builder_amp['amp-structured-data-placeholder-image']['url'];
    }
      $structured_data_image = $structured_data_image_url;
      $structured_data_height = intval($redux_builder_amp['amp-structured-data-placeholder-image-height']);
      $structured_data_width = intval($redux_builder_amp['amp-structured-data-placeholder-image-width']);

      $metadata['image'] = array(
        '@type' 	=> 'ImageObject',
        'url' 		=> $structured_data_image ,
        'height' 	=> $structured_data_height,
        'width' 	=> $structured_data_width,
      );
  }
  // Custom Structured Data information for Archive, Categories and tag pages.
  if ( is_archive() ) {
      $structured_data_image = $redux_builder_amp['amp-structured-data-placeholder-image']['url'];
      $structured_data_height = intval($redux_builder_amp['amp-structured-data-placeholder-image-height']);
      $structured_data_width = intval($redux_builder_amp['amp-structured-data-placeholder-image-width']);

      $structured_data_archive_title 	= "Archived Posts";
      $structured_data_author				=  get_userdata( 1 );
          if ( $structured_data_author ) {
            $structured_data_author 		= $structured_data_author->display_name ;
          } else {
            $structured_data_author 		= "admin";
          }

      $metadata['image'] = array(
        '@type' 	=> 'ImageObject',
        'url' 		=> $structured_data_image ,
        'height' 	=> $structured_data_height,
        'width' 	=> $structured_data_width,
      );
      $metadata['author'] = array(
        '@type' 	=> 'Person',
        'name' 		=> $structured_data_author ,
      );
      $metadata['headline'] = $structured_data_archive_title;
  }

  if ( $metadata['image']['width'] < 696 ) {
    $metadata['image']['width'] = 700 ;
  }

  return $metadata;
}

// # Core Function
// 45. searchpage, frontpage, homepage structured data
add_filter( 'amp_post_template_metadata', 'ampforwp_search_or_homepage_or_staticpage_metadata', 10, 2 );
function ampforwp_search_or_homepage_or_staticpage_metadata( $metadata, $post ) {
  global $redux_builder_amp;
  global $wp;

  if( is_search() || is_home() || ( is_front_page() && $redux_builder_amp['amp-frontpage-select-option'] )) {

    if( is_home() || is_front_page() ){
      $current_url = home_url( $wp->request );
      $current_url = dirname( $current_url );
      $headline 	 =  get_bloginfo('name') . ' | ' . get_option( 'blogdescription' );
    } else {
      $current_url 	= trailingslashit(get_home_url())."?s=".get_search_query();
      $current_url 	= untrailingslashit( $current_url );
      $headline 		=  $redux_builder_amp['amp-translator-search-text'] . '  ' . get_search_query();
    }

    // placeholder Image area
    if (! empty( $redux_builder_amp['amp-structured-data-placeholder-image']['url'] ) ) {
      $structured_data_image_url = $redux_builder_amp['amp-structured-data-placeholder-image']['url'];
    }
    $structured_data_image =  $structured_data_image_url; //  Placeholder Image URL
    $structured_data_height = intval($redux_builder_amp['amp-structured-data-placeholder-image-height']); //  Placeholder Image width
    $structured_data_width = intval($redux_builder_amp['amp-structured-data-placeholder-image-width']); //  Placeholder Image height

    if( is_front_page() ) {
      $ID = $redux_builder_amp['amp-frontpage-select-option-pages']; // ID of slected front page
      $headline =  get_the_title( $ID ) . ' | ' . get_option('blogname');
      $static_page_data = get_post( $ID );

      $datePublished = $static_page_data->post_date;
      $dateModified = $static_page_data->post_modified;

      $featured_image_array = wp_get_attachment_image_src( get_post_thumbnail_id( $ID ) ); // Featured Image structured Data
      if( $featured_image_array ) {
        $structured_data_image = $featured_image_array[0];
        $structured_data_image = $featured_image_array[1];
        $structured_data_image = $featured_image_array[2];
      }
    } else {
      // TODO : check the entire else section .... time for search and homepage...wierd ???
      $datePublished = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) - 2 );
      // time difference is 2 minute between published and modified date
      $dateModified = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
    }
    $metadata['datePublished'] = $datePublished; // proper published date added
    $metadata['dateModified'] = $dateModified; // proper modified date

    $metadata['image'] = array(
      '@type' 	=> 'ImageObject',
      'url' 		=> $structured_data_image ,
      'height' 	=> $structured_data_height,
      'width' 	=> $structured_data_width,
    );

    $metadata['mainEntityOfPage'] = $current_url; // proper URL added
    $metadata['headline'] = $headline; // proper headline added
  }
  return $metadata;
}