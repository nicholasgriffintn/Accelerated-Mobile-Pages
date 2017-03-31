<?php
//26. Extending Title Tagand De-Hooking the Standard one from AMP
add_action('amp_post_template_include_single','ampforwp_remove_title_tags');
function ampforwp_remove_title_tags(){
  remove_action('amp_post_template_head','amp_post_template_add_title');
  add_action('amp_post_template_head','ampforwp_add_custom_title_tag');

  function ampforwp_add_custom_title_tag(){
    global $redux_builder_amp; ?>
    <title> <?php

      // title for a single post and single page
      if( is_single() || is_page() ){
        global $post;
        $title = $post->post_title;
        $site_title =  $title . ' | ' . get_option( 'blogname' ) ;
      }

      // title for archive pages
      if ( is_archive() && $redux_builder_amp['ampforwp-archive-support'] )  {
        $site_title = strip_tags(get_the_archive_title( '' )) . ' | ' . strip_tags(get_the_archive_description( '' ));
      }

      if ( is_home() ) {
        $site_title = get_bloginfo('name') . ' | ' . get_option( 'blogdescription' ) ;
        if  ( $redux_builder_amp['amp-frontpage-select-option']== 1) {
          $ID = $redux_builder_amp['amp-frontpage-select-option-pages'];
          $site_title =  get_the_title( $ID ) . ' | ' . get_option('blogname');
        } else {
          global $wp;
          $current_archive_url = home_url( $wp->request );
          $current_url_in_pieces = explode('/',$current_archive_url);
          $cnt = count($current_url_in_pieces);
          if( is_numeric( $current_url_in_pieces[  $cnt-1 ] ) ) {
            $site_title .= ' | Page '.$current_url_in_pieces[$cnt-1];
          }
        }
      }

      if( is_search() ) {
        $site_title =  $redux_builder_amp['amp-translator-search-text'] . '  ' . get_search_query();
      }

      if ( class_exists('WPSEO_Frontend') ) {
        $front = WPSEO_Frontend::get_instance();
        $title = $front->title( $site_title );

        // Code for Custom Frontpage Yoast SEO Title
        if ( class_exists('WPSEO_Meta') ) {

          // Yoast SEO Title
          $yaost_title = WPSEO_Options::get_option( 'wpseo' );
          if ( $yaost_title['website_name']) {
            $site_title  = $yaost_title['website_name'];
          } else {
            $site_title  =  get_bloginfo('name');
          }

          // Yoast SEO Title Seperator
          $wpseo_titles = WPSEO_Options::get_option( 'wpseo_titles' );
          $seperator_options = WPSEO_Option_Titles::get_instance()->get_separator_options();
          if ( $wpseo_titles['separator'] ) {
            $seperator = $seperator_options[ $wpseo_titles['separator'] ];
          } else {
            $seperator = ' - ';
          }

          $post_id = $redux_builder_amp['amp-frontpage-select-option-pages'];
          $custom_fp_title = WPSEO_Meta::get_value('title', $post_id );
          if ( is_home() && $redux_builder_amp['amp-frontpage-select-option'] ) {
            if ( $custom_fp_title ) {
              $title = $custom_fp_title;
            } else {
              $title = get_the_title($post_id) .' '. $seperator .' '. $site_title ;
            }
          }
        }

        echo $title;
      } else {
        echo $site_title;
      } ?>
    </title> <?php
  }
}

//38. #529 editable archives
add_filter( 'get_the_archive_title', 'ampforwp_editable_archvies_title' );
function ampforwp_editable_archvies_title($title) {
  global $redux_builder_amp;
  if ( is_category() ) {
    $title = single_cat_title( $redux_builder_amp['amp-translator-archive-cat-text'].' ', false );
  } elseif ( is_tag() ) {
    $title = single_tag_title( $redux_builder_amp['amp-translator-archive-tag-text'].' ', false );
  }
  return $title;
}