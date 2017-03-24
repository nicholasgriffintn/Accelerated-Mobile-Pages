<?php // Standardized DRY Way

// # Util Function
// Outputs Ampforwp CSS
if ( !function_exists( 'ampforwp_the_css' ) ) {
  function ampforwp_the_css( $amp_post_template_object ) { ?>
    <style amp-custom> <?php
       $amp_post_template_object = ampforwp_get_template_data_object();
       $amp_post_template_object->load_parts( array( 'style' ) );
       do_action( 'amp_post_template_css', $amp_post_template_object ); ?>
    </style> <?php
  }
}


// # Util Function
// Outputs Ampforwp 'amp_post_template_head' Hooks
if ( !function_exists( 'ampforwp_the_template_head' ) ) {
  function ampforwp_the_template_head( $amp_post_template_object ) {
	 do_action( 'amp_post_template_head', $amp_post_template_object );
  }
}


// # Util Function
// reutrns loop args for Archive Index Search pages
if( !function_exists( 'ampforwp_get_loop_query_args' ) ) {
  function ampforwp_get_loop_query_args(){
    $args = null;
    if( is_archive() ) {
      $args = array(
        'post_type'           => 'post',
        'orderby'             => 'date',
        'ignore_sticky_posts' => 1,
        'paged'               => esc_attr($paged),
        'post__not_in' 		  => $exclude_ids,
        'has_password' => false ,
        'post_status'=> 'publish'
      );
    } elseif( is_home() ) {
      $args =  array(
  			'post_type'           => 'post',
  			'orderby'             => 'date',
  			'paged'               => esc_attr($paged),
  			'post__not_in' 		  => $exclude_ids,
  			'has_password' => false ,
  			'post_status'=> 'publish'
  		);
    } elseif( is_search() ) {
      $args = array(
  			's' 				  => get_search_query() ,
  			'ignore_sticky_posts' => 1,
  			'paged'               => esc_attr($paged),
  			'post__not_in' 		  => $exclude_ids,
  			'has_password' 		  => false ,
  			'post_status'		  => 'publish'
  		);
    }
    return $args;
  }
}


// # Util Function
// Outputs AMP sanitized archive Description
if ( !function_exists( 'ampforwp_get_the_description' ) ) {
  function ampforwp_get_the_description() {
  	$nonamp_description_content_input = get_the_archive_description();
  	$arch_desc = ampforwp_sanitize_html_to_amphtml( $nonamp_description_content_input );
    return $arch_desc;
  }
}


// # Util Function
// Outputs AMP Carousel
if ( !function_exists( 'ampforwp_the_carousel' ) ) {
  function ampforwp_the_carousel() { global $redux_builder_amp; ?>

  	<div class="amp-featured-wrapper">
      <div class="amp-featured-area">
      	<amp-carousel width="450" height="270" layout="responsive" type="slides" autoplay delay="4000"> <?php

      	  if( $redux_builder_amp['amp-design-3-category-selector'] ) {
      	    $args = array(
      	                   'cat' => $redux_builder_amp['amp-design-3-category-selector'],
      	                   'posts_per_page' => 4,
      	                   'has_password' => false ,
      	                   'post_status'=> 'publish'
      	                 );
      	  } else {
      	    //if user does not give a category
      	    $args = array(
      	                   'posts_per_page' => 4,
      	                   'has_password' => false ,
      	                   'post_status'=> 'publish'
      	                 );
      	  }

          // Loop Begins Here
      	   $category_posts = new WP_Query($args);
      	   if( $category_posts->have_posts() ) :
      	      while( $category_posts->have_posts() ) : $category_posts->the_post(); ?>
      	      <div> <?php
                if ( has_post_thumbnail() ) {
      					$thumb_id = get_post_thumbnail_id();
      					$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'medium_large', true);
      					$thumb_url = $thumb_url_array[0]; ?>

      					 <amp-img src=<?php echo $thumb_url ?> width=450 height=270></amp-img> <?php

                } ?>

                  <a href="<?php trailingslashit( the_permalink() ) . 'amp' ; ?>">
                  <div class="featured_title">
    		            <div class="featured_time">
                      <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) .' '.   $redux_builder_amp['amp-translator-ago-date-text']; ?>
                    </div>
    		            <h1><?php the_title() ?></h1>
  	              </div>
                  </a>

          		 </div>
          	 <?php endwhile; ?>
           <?php endif; ?>
        </amp-carousel>
      </div>
    </div> <?php
  }
}


// # Core Function
// Outputs Ampforwp loop for Search Archive Home
if ( !function_exists( 'ampforwp_the_loop' ) ) {
  add_action( 'ampforwp_loop' , 'ampforwp_the_loop' );
  function ampforwp_the_loop( ){
    global $redux_builder_amp;
    if ( get_query_var( 'paged' ) ) {
          $paged = get_query_var('paged');
      } elseif ( get_query_var( 'page' ) ) {
          $paged = get_query_var('page');
      } else {
          $paged = 1;
    }

    if( $redux_builder_amp['amp-design-3-featured-slider'] == 1 && $paged === 1 && !is_search() && !is_archive() ) {
      ampforwp_the_carousel();
    }

    $exclude_ids = get_option('ampforwp_exclude_post');
    $q = null;
    $args = ampforwp_get_loop_query_args();

    if( is_home() ){
      $filtered_args = apply_filters('ampforwp_query_args', $args);
      $q = new WP_Query( $filtered_args );
    } else {
      $q = new WP_Query( $args );
    }

    if ( is_archive() ) {
   			the_archive_title( '<h3 class="amp-wp-content page-title">', '</h3>' );
        $arch_desc = ampforwp_get_the_description();
  			if( $arch_desc ) {  ?>
  				<div class="amp-wp-content taxonomy-description">
  					<?php echo $arch_desc ; ?>
  			  </div> <?php
  			}
   		}

    if ( is_search() ) { ?>
       		<h3 class="amp-wp-content page-title"> <?php echo $redux_builder_amp['amp-translator-search-text'] . '  ' . get_search_query();?>  </h3> <?php
    }

          if ( have_posts() ) : while ( have_posts() ) : the_post();
        		$ampforwp_amp_post_url = trailingslashit( trailingslashit( get_permalink() ) . AMPFORWP_AMP_QUERY_VAR ); ?>

              <div class="amp-wp-content amp-loop-list <?php if ( has_post_thumbnail() ) { } else{?>amp-loop-list-noimg<?php } ?>">
          			<?php if ( has_post_thumbnail() ) { ?> <?php
          				$thumb_id = get_post_thumbnail_id();
          				$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'medium', true);
          				$thumb_url = $thumb_url_array[0]; ?>
          				<div class="home-post_image">
          					<a href="<?php echo esc_url( $ampforwp_amp_post_url ); ?>">
          						<amp-img layout="responsive" src=<?php echo $thumb_url ?> width=450 height=270></amp-img>
          					</a>
          				</div>
          			<?php } ?>

      			<div class="amp-wp-post-content">

              <ul class="amp-wp-tags"> <?php
               foreach((get_the_category()) as $category) { ?>
  			        <li><?php echo $category->cat_name ?></li> <?php
               } ?>
              </ul>

      				<h2 class="amp-wp-title"><a href="<?php echo esc_url( $ampforwp_amp_post_url ); ?>"> <?php the_title(); ?></a></h2> <?php

      					if( has_excerpt() ){
      						$content = get_the_excerpt();
      					} else {
      						$content = get_the_content();
      					} ?>

  		        <p><?php echo wp_trim_words( strip_shortcodes(  $content ) , '15' ); ?></p>
                <div class="featured_time"> <?php
                   printf( _x( '%1$s '. $redux_builder_amp['amp-translator-ago-date-text'], '%2$s = human-readable time difference', 'wpdocs_textdomain' ),
                         human_time_diff( get_the_time( 'U' ),
                         current_time( 'timestamp' ) ) ); ?>
                </div>

      	  </div>
          <div class="cb"></div>
      	</div>

      <?php endwhile;  ?>

      	<div class="amp-wp-content pagination-holder">
      		<div id="pagination">
      			<div class="next"><?php next_posts_link( $redux_builder_amp['amp-translator-show-more-posts-text'] , 0 ) ?></div>
      					<?php if ( $paged > 1 ) { ?>
      						<div class="prev"><?php previous_posts_link( $redux_builder_amp['amp-translator-show-previous-posts-text'] ); ?></div>
      					<?php } ?>
      			<div class="clearfix"></div>
      		</div>
      	</div>
    <?php else : ?>

    		<div class="amp-wp-content">
     			<?php echo $redux_builder_amp['amp-translator-search-no-found']; ?>
     		</div>

    <?php endif; ?> <?php
    wp_reset_postdata();
  }
}


// # Util Function
// Outputs Lang Code for <html> Tag
if ( !function_exists( 'ampforwp_the_lang_code' ) ) {
  function ampforwp_the_lang_code( $amp_post_template_object ) {
   echo AMP_HTML_Utils::build_attributes_string( $amp_post_template_object->get( 'html_tag_attributes' ) );
  }
}

// # Util Function
// Outputs Head Boilerplate
if ( !function_exists( 'ampforwp_head_boilerplate' ) ) {
  function ampforwp_head_boilerplate() { ?>
    <meta charset="utf-8">
    <link rel="dns-prefetch" href="https://cdn.ampproject.org">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"> <?php
  }
}


// # Util Function
// Outputs rel canonical
if ( !function_exists( 'ampforwp_rel_canonical' ) ) {
  function ampforwp_rel_canonical() {
    global $redux_builder_amp;

      if( is_search() ){
        $current_search_url =trailingslashit(get_home_url())."?s=".get_search_query();
        $amp_url = untrailingslashit($current_search_url);
      }
      if ( is_home()  || is_search() || is_archive() ){
        global $wp;
        $current_archive_url = home_url( $wp->request );
        $amp_url 	= trailingslashit($current_archive_url);
        $remove 	= '/'. AMPFORWP_AMP_QUERY_VAR;
        $amp_url 	= str_replace( $remove, '', $amp_url) ;
      }

      if( is_search() ){
        $amp_url = $amp_url ."?s=".get_search_query();
      } ?>

    <link rel="canonical" href="<?php echo $amp_url ?>"> <?php
  }
}


// # Util Function
// All Head Functions Combined into one
if ( !function_exists( 'ampforwp_the_head' ) ) {
  function ampforwp_the_head( $amp_post_template_object ) {
    ampforwp_head_boilerplate();
    ampforwp_rel_canonical();
    ampforwp_the_template_head( $amp_post_template_object );
    ampforwp_the_css( $amp_post_template_object );
  }
}
