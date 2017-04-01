<?php // Standardized DRY Way

// Code betweeen this comments dont Modify or Touch
/********************************************/
if( ampforwp_is_plugin_active( 'amp/amp.php' ) ) {
  require_once( WP_PLUGIN_DIR . '/amp/amp.php' );
  if( function_exists( 'amp_load_classes' ) ) {
    amp_load_classes();
  }
}
global $redux_builder_amp;

// # Util Function
if ( !function_exists( 'ampforwp_get_template_data_object' ) ) {
  add_action('pre_amp_render_post','ampforwp_get_template_data_object');
  function ampforwp_get_template_data_object() {
    global $redux_builder_amp;
    $post_id = $redux_builder_amp['amp-frontpage-select-option-pages'];
    $amp_post_template_object = new AMP_Post_Template( $post_id );
    return  $amp_post_template_object ;
  }
}

// Global Number of Comments Constant
define( 'AMPFORWP_COMMENTS_PER_PAGE', $redux_builder_amp['ampforwp-number-of-comments'] );
/********************************************/


// # Util Function
// Function to Check Comments Enabled or Not
if ( !function_exists( 'ampforwp_is_comments_enabled' ) ) {
  function ampforwp_is_comments_enabled() {
    if ( !comments_open() ) {
      return;
    }
  }
}


// # Util Function
// Function to Output Button Code
if ( !function_exists( 'ampforwp_button_code' ) ) {
  function ampforwp_button_code() { global $redux_builder_amp ;
    if( ! ampforwp_is_plugin_active( AMPFORWP_COMMENTS_PLUGIN ) ) { ?>
    <div class="comment-button-wrapper">
         <a href="<?php echo get_permalink().'?nonamp=1'.'#commentform'  ?>" rel="nofollow"><?php esc_html_e( $redux_builder_amp['amp-translator-leave-a-comment-text']  ); ?></a>
     </div>
  <?php }
  }
}


// TODO redeclaration error in design 1 and 2 because of below function
// # Core Function
// Function to be used for Every Comment output
if ( !function_exists( 'ampforwp_custom_translated_comment' ) ) {
  function ampforwp_custom_translated_comment($comment, $args, $depth){
    $GLOBALS['comment'] = $comment;
    global $redux_builder_amp; ?>
    <!-- comment Start -->
      <li id="li-comment-<?php comment_ID() ?>" <?php comment_class(); ?> >
        <article id="comment-<?php comment_ID(); ?>" class="comment-body">
          <footer class="comment-meta">

            <div class="comment-author vcard"> <?php
               printf(__('<b class="fn">%s</b> <span class="says">'.$redux_builder_amp['amp-translator-says-text'].':</span>'), get_comment_author_link()) ?>
            </div>

            <div class="comment-metadata">
              <a href="<?php echo htmlspecialchars( trailingslashit( get_comment_link( $comment->comment_ID ) ) ) ?>">
                <?php
                printf(__('%1$s '.$redux_builder_amp['amp-translator-at-text'].' %2$s'), get_comment_date(),  get_comment_time())
                ?>
              </a>
              <?php edit_comment_link(__('('.$redux_builder_amp['amp-translator-Edit-text'].')'),'  ','') ?>
            </div>

         </footer>

          <div class="comment-content">
            <p><?php
              $comment_content = get_comment_text();
              $comment_content = ampforwp_sanitize_html_to_amphtml( $comment_content );
              echo $comment_content; ?>
            </p>
          </div>
        </article>
      </li>
    <!-- comment End -->
  <?php
  }
}


//TODO properly organize comments code
// # Core Function
// Function of Element Comment
if ( !function_exists( 'ampforwp_content_element_comment' ) ) {
  add_action( 'ampforwp_content_elements_comment' , 'ampforwp_content_element_comment' );
  function ampforwp_content_element_comment() {
    global $redux_builder_amp;
    ampforwp_is_comments_enabled(); ?>

    <div class="ampforwp-comment-wrapper"> <?php
    	$postID = get_the_ID();
    	$comments = get_comments(array(
    			'post_id' => $postID,
    			'status' => 'approve'
    	));
    	if ( $comments ) { ?>
    		<div class="amp-wp-content comments_list">
          <h3><?php echo $redux_builder_amp['amp-translator-view-comments-text'] ?></h3>
            <ul> <?php
      				wp_list_comments( array(
      				  'per_page' 			=> AMPFORWP_COMMENTS_PER_PAGE ,
      				  'style' 				=> 'li',
      				  'type'				=> 'comment',
      				  'max_depth'   		=> 5,
      				  'avatar_size'			=> 0,
      					'callback'				=> 'ampforwp_custom_translated_comment',
      				  'reverse_top_level' 	=> true
      				), $comments ); ?>
    		    </ul>
    		</div> <?php
      		ampforwp_button_code();
    	} else {
        if ( !comments_open() ) {
           // Dont do Anything
         } else {
          ampforwp_button_code();
         }
      } ?>
   </div> <?php
  }
}


// # Core Function
// Function of Element Title
if ( !function_exists( 'ampforwp_content_element_title' ) ) {
  add_action( 'ampforwp_content_elements_title' , 'ampforwp_content_element_title' );
  function ampforwp_content_element_title( $amp_post_template_object ) {
    global $redux_builder_amp;
    if( $redux_builder_amp['amp-frontpage-select-option'] && !is_single() ){
      $amp_post_template_object = ampforwp_get_template_data_object();
    }?>
    <header class="amp-wp-content amp-wp-article-header ampforwp-title">
    	<h1 class="amp-wp-title"><?php echo wp_kses_data( $amp_post_template_object->get( 'post_title' ) ); ?></h1>
    </header> <?php
  }
}


// # Core Function
// Function of Element Social Share
if ( !function_exists( 'ampforwp_content_element_social_share' ) ) {
  add_action( 'ampforwp_content_elements_social_share' , 'ampforwp_content_element_social_share' );
  function ampforwp_content_element_social_share() {
   global $redux_builder_amp;
    if( is_socialshare_or_socialsticky_enabled_in_ampforwp() ) { ?>
    <div class="amp-wp-content ampforwp-social-icons-wrapper ampforwp-social-icons">
        <i class="icono-share"></i> <?php

        if($redux_builder_amp['enable-single-facebook-share'] == true)  { ?>
    			<amp-social-share type="facebook"    data-param-app_id="<?php echo $redux_builder_amp['amp-facebook-app-id']; ?>" width="40" height="40"></amp-social-share> <?php
        }

    		if($redux_builder_amp['enable-single-twitter-share'] == true)  { ?>
    			<amp-social-share type="twitter" width="40" height="40" data-param-url="CANONICAL_URL"
    				></amp-social-share> <?php
        }

        if($redux_builder_amp['enable-single-gplus-share'] == true)  { ?>
    			<amp-social-share type="gplus" width="40" height="40"></amp-social-share> <?php
    		}

    		if($redux_builder_amp['enable-single-email-share'] == true)  { ?>
    			<amp-social-share type="email" width="40" height="40"></amp-social-share> <?php
    		}

        if($redux_builder_amp['enable-single-pinterest-share'] == true)  { ?>
    			<amp-social-share type="pinterest"  width="40" height="40"></amp-social-share> <?php
    		}

        if($redux_builder_amp['enable-single-linkedin-share'] == true)  { ?>
    			<amp-social-share type="linkedin" width="40" height="40"></amp-social-share> <?php
        }

        if($redux_builder_amp['enable-single-whatsapp-share'] == true)  { ?>
    			<a href="whatsapp://send?text=<?php echo get_the_permalink(); ?>">
    				<div class="whatsapp-share-icon">
    				    <amp-img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4IiB2aWV3Qm94PSIwIDAgOTAgOTAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDkwIDkwOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPHBhdGggaWQ9IldoYXRzQXBwIiBkPSJNOTAsNDMuODQxYzAsMjQuMjEzLTE5Ljc3OSw0My44NDEtNDQuMTgyLDQzLjg0MWMtNy43NDcsMC0xNS4wMjUtMS45OC0yMS4zNTctNS40NTVMMCw5MGw3Ljk3NS0yMy41MjIgICBjLTQuMDIzLTYuNjA2LTYuMzQtMTQuMzU0LTYuMzQtMjIuNjM3QzEuNjM1LDE5LjYyOCwyMS40MTYsMCw0NS44MTgsMEM3MC4yMjMsMCw5MCwxOS42MjgsOTAsNDMuODQxeiBNNDUuODE4LDYuOTgyICAgYy0yMC40ODQsMC0zNy4xNDYsMTYuNTM1LTM3LjE0NiwzNi44NTljMCw4LjA2NSwyLjYyOSwxNS41MzQsNy4wNzYsMjEuNjFMMTEuMTA3LDc5LjE0bDE0LjI3NS00LjUzNyAgIGM1Ljg2NSwzLjg1MSwxMi44OTEsNi4wOTcsMjAuNDM3LDYuMDk3YzIwLjQ4MSwwLDM3LjE0Ni0xNi41MzMsMzcuMTQ2LTM2Ljg1N1M2Ni4zMDEsNi45ODIsNDUuODE4LDYuOTgyeiBNNjguMTI5LDUzLjkzOCAgIGMtMC4yNzMtMC40NDctMC45OTQtMC43MTctMi4wNzYtMS4yNTRjLTEuMDg0LTAuNTM3LTYuNDEtMy4xMzgtNy40LTMuNDk1Yy0wLjk5My0wLjM1OC0xLjcxNy0wLjUzOC0yLjQzOCwwLjUzNyAgIGMtMC43MjEsMS4wNzYtMi43OTcsMy40OTUtMy40Myw0LjIxMmMtMC42MzIsMC43MTktMS4yNjMsMC44MDktMi4zNDcsMC4yNzFjLTEuMDgyLTAuNTM3LTQuNTcxLTEuNjczLTguNzA4LTUuMzMzICAgYy0zLjIxOS0yLjg0OC01LjM5My02LjM2NC02LjAyNS03LjQ0MWMtMC42MzEtMS4wNzUtMC4wNjYtMS42NTYsMC40NzUtMi4xOTFjMC40ODgtMC40ODIsMS4wODQtMS4yNTUsMS42MjUtMS44ODIgICBjMC41NDMtMC42MjgsMC43MjMtMS4wNzUsMS4wODItMS43OTNjMC4zNjMtMC43MTcsMC4xODItMS4zNDQtMC4wOS0xLjg4M2MtMC4yNy0wLjUzNy0yLjQzOC01LjgyNS0zLjM0LTcuOTc3ICAgYy0wLjkwMi0yLjE1LTEuODAzLTEuNzkyLTIuNDM2LTEuNzkyYy0wLjYzMSwwLTEuMzU0LTAuMDktMi4wNzYtMC4wOWMtMC43MjIsMC0xLjg5NiwwLjI2OS0yLjg4OSwxLjM0NCAgIGMtMC45OTIsMS4wNzYtMy43ODksMy42NzYtMy43ODksOC45NjNjMCw1LjI4OCwzLjg3OSwxMC4zOTcsNC40MjIsMTEuMTEzYzAuNTQxLDAuNzE2LDcuNDksMTEuOTIsMTguNSwxNi4yMjMgICBDNTguMiw2NS43NzEsNTguMiw2NC4zMzYsNjAuMTg2LDY0LjE1NmMxLjk4NC0wLjE3OSw2LjQwNi0yLjU5OSw3LjMxMi01LjEwN0M2OC4zOTgsNTYuNTM3LDY4LjM5OCw1NC4zODYsNjguMTI5LDUzLjkzOHoiIGZpbGw9IiNGRkZGRkYiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" width="16" height="16" />
    			    </div>
    			</a> <?php
        } ?>
    </div> <?php
    }
  }
}


// # Core Function
// Function of Element Simple Comment Button
if ( !function_exists( 'ampforwp_content_element_simple_comment' ) ) {
  add_action( 'ampforwp_content_elements_simple_comments' , 'ampforwp_content_element_simple_comment' );
  function ampforwp_content_element_simple_comment() {
    ampforwp_is_comments_enabled();
    ampforwp_button_code();
  }
}


// # Core Function
// Function of Element Related Posts
if ( !function_exists( 'ampforwp_content_element_related_posts' ) ) {
  add_action( 'ampforwp_content_elements_related_posts' , 'ampforwp_content_element_related_posts' );
  function ampforwp_content_element_related_posts() {
    global $post , $redux_builder_amp;
    $string_number_of_related_posts = $redux_builder_amp['ampforwp-number-of-related-posts'];
    $int_number_of_related_posts = round( abs( floatval( $string_number_of_related_posts ) ) );
    $args = null;

    if($redux_builder_amp['ampforwp-single-select-type-of-related']==2){
        $categories = get_the_category($post->ID);
          if ($categories) {
              $category_ids = array();
              foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
              $args=array(
                  'category__in' => $category_ids,
                  'post__not_in' => array($post->ID),
                  'posts_per_page'=> $int_number_of_related_posts,
                  'ignore_sticky_posts'=>1,
                  'has_password' => false ,
                  'post_status'=> 'publish'
              );
          }
    } //end of block for categories

   if($redux_builder_amp['ampforwp-single-select-type-of-related']==1) {
        $ampforwp_tags = get_the_tags($post->ID);
          if ($ampforwp_tags) {
                  $tag_ids = array();
                  foreach($ampforwp_tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
                  $args=array(
                     'tag__in' => $tag_ids,
                      'post__not_in' => array($post->ID),
                      'posts_per_page'=> $int_number_of_related_posts,
                      'ignore_sticky_posts'=>1,
                      'has_password' => false ,
                      'post_status'=> 'publish'
                  );
        }
    } //end of block for tags

    $custom_query = new wp_query( $args );
      if( $custom_query->have_posts() ) { ?>
        <div class="amp-wp-content relatedpost">
          <div class="related_posts">
            <ol class="clearfix">
              <h3><?php echo esc_html( $redux_builder_amp['amp-translator-related-text'] ); ?></h3> <?php

              while( $custom_query->have_posts() ) {
                $custom_query->the_post();
                $related_post_permalink = get_permalink();
                $related_post_permalink = trailingslashit( $related_post_permalink );
                $related_post_permalink = trailingslashit( $related_post_permalink . AMPFORWP_AMP_QUERY_VAR ); ?>

                <li class="<?php if ( has_post_thumbnail() ) { echo'has_related_thumbnail'; } else { echo 'no_related_thumbnail'; } ?>">
                  <a href="<?php echo esc_url( $related_post_permalink ); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"> <?php

                    $thumb_id_2 = get_post_thumbnail_id();
                    $thumb_url_array_2 = wp_get_attachment_image_src($thumb_id_2, 'thumbnail', true);
                    $thumb_url_2 = $thumb_url_array_2[0];

                    if ( has_post_thumbnail() ) { ?>
                     <amp-img src="<?php echo esc_url( $thumb_url_2 ); ?>" width="150" height="150" layout="responsive"></amp-img> <?php
                    } ?>
                 </a>

                 <div class="related_link">
                  <a href="<?php echo esc_url( $related_post_permalink ); ?>"> <?php the_title(); ?> </a> <?php
                  if( has_excerpt() ) {
                    $content = get_the_excerpt();
                  } else {
                    $content = get_the_content();
                  } ?>
                  <p> <?php echo wp_trim_words( strip_shortcodes( $content ) , '15' ); ?> </p>
                 </div>
                </li> <?php
            } ?>
          </ol>
        </div>
      </div> <?php
    }
    wp_reset_postdata();
  }
}


// # Core Function
// Function of Element Meta Taxonomy
if ( !function_exists( 'ampforwp_content_element_meta_taxonomy' ) ) {
  add_action( 'ampforwp_content_elements_meta_taxonomy' , 'ampforwp_content_element_meta_taxonomy' );
  function ampforwp_content_element_meta_taxonomy( $amp_post_template_object ) { ?>
    <div class="amp-wp-content amp-wp-article-tags amp-wp-article-category ampforwp-meta-taxonomy "> <?php
      global $redux_builder_amp;
      if( $redux_builder_amp['amp-frontpage-select-option'] && !is_single() ){
        $amp_post_template_object = ampforwp_get_template_data_object();
      }
    	$ampforwp_tags=  get_the_terms( $amp_post_template_object->ID, 'post_tag' );
    	if ( $ampforwp_tags && ! is_wp_error( $ampforwp_tags ) ) { ?>
    		<div class="amp-wp-meta amp-wp-content ampforwp-tax-tag"> <?php
          foreach ($ampforwp_tags as $tag) {
            if($redux_builder_amp['ampforwp-archive-support']) {
                echo ('<span><a href="'.trailingslashit( trailingslashit( get_tag_link( $tag->term_taxonomy_id ) ) . 'amp' ) .'" >'. $tag->name  .'</a></span>');
            } else {
              echo '<span>'. $tag->name .'</span>';
            }
  				} ?>
    	 </div> <?php
     } ?>
   </div> <?php

    if( $redux_builder_amp['amp-design-3-author-description'] ) { ?>
      <div class="amp-wp-content amp_author_area ampforwp-meta-taxonomy">
        <div class="amp-wp-content amp_author_area_wrapper"> <?php
         $post_author = $amp_post_template_object->get( 'post_author' );
          if ( $post_author ) {
            $author_avatar_url = get_avatar_url( $post_author->user_email, array( 'size' => 70 ) );
            if ( $author_avatar_url ) { ?>
                <amp-img src="<?php echo $author_avatar_url; ?>" width="70" height="70" layout="fixed"></amp-img> <?php
            } ?>
            <strong><?php echo esc_html( $post_author->display_name ); ?></strong>: <?php echo  $post_author->description ;
          } ?>
        </div>
      </div> <?php
    }
  }
}


// # Core Function
// Function of Element Meta Info
if ( !function_exists( 'ampforwp_content_element_meta_info' ) ) {
  add_action( 'ampforwp_content_elements_meta_info' , 'ampforwp_content_element_meta_info' );
  function ampforwp_content_element_meta_info( $amp_post_template_object ) {
    global $redux_builder_amp;
    if( $redux_builder_amp['amp-frontpage-select-option'] && !is_single() ){
      $amp_post_template_object = ampforwp_get_template_data_object();
    } ?>
   <div class="amp-wp-content amp-wp-article-header ampforwp-meta-info">
     <div class="amp-wp-content post-title-meta">
        <ul class="amp-wp-meta amp-meta-wrapper"> <?php
           $post_author = $amp_post_template_object->get( 'post_author' );

           if ( $post_author ) {
             $author_avatar_url = get_avatar_url( $post_author->user_email, array( 'size' => 24 ) ); ?>
             <div class="amp-wp-meta amp-wp-byline">
             <span class="amp-wp-author author vcard"><?php echo esc_html( $post_author->display_name ); ?></span> <?php

             $ampforwp_categories = get_the_terms( $amp_post_template_object->ID, 'category' );
               if ( $ampforwp_categories ) { ?>
                 <span class="amp-wp-meta amp-wp-tax-category ampforwp-tax-category"> <?php

                   if(!$redux_builder_amp['amp-rtl-select-option']) {
                     printf( __($redux_builder_amp['amp-translator-in-designthree'] .' ', 'amp' ));
                   }
                   foreach ($ampforwp_categories as $cat ) {

                     if($redux_builder_amp['ampforwp-archive-support']){
                         echo ('<span><a href="'.trailingslashit( trailingslashit( get_category_link( $cat->term_taxonomy_id ) ) .'amp' ) .'" >'.$cat->name .'</a></span>');
                     } else {
                       echo ('<span>'.$cat->name .'</span>');
                     }

                   }
                   //if RTL is ON
                   if($redux_builder_amp['amp-rtl-select-option']) {
                         printf( __($redux_builder_amp['amp-translator-categories-text'] .' ', 'amp' ));
                   } ?>
                </span> <?php
            }

            if ( $redux_builder_amp['amp-design-3-date-feature'] ) { ?>
             <span class="ampforwp-design3-single-date"><?php  _e( $redux_builder_amp['amp-translator-on-text'] . " ",'ampforwp'); the_time( get_option( 'date_format' ) ) ?></span> <?php
            } ?>

          </div> <?php
         } ?>
       </ul>
     </div>
   </div> <?php
  }
}


// # Core Function
// Function of Element Featured Image
if ( !function_exists( 'ampforwp_content_element_featured_image' ) ) {
  add_action( 'ampforwp_content_elements_featured_image' , 'ampforwp_content_element_featured_image' );
  function ampforwp_content_element_featured_image( $amp_post_template_object ) {
    global $redux_builder_amp;
    if( $redux_builder_amp['amp-frontpage-select-option'] && !is_single() ){
      $amp_post_template_object = ampforwp_get_template_data_object();
    }?>
    <div class="amp-wp-article-featured-image amp-wp-content featured-image-content"> <?php
      $featured_image = $amp_post_template_object->get( 'featured_image' );
      if ( empty( $featured_image ) ) {
      	return;
      }

      $amp_html = $featured_image['amp_html'];
      $caption = $featured_image['caption'];
      ?>
    <div class="post-featured-img">
      <figure class="amp-wp-article-featured-image wp-caption">
      	<?php echo $amp_html; // amphtml content; no kses
      	if ( $caption ) { ?>
      		<p class="wp-caption-text"> <?php echo wp_kses_data( $caption ); ?> </p> <?php
        } ?>
      </figure>
    </div>
  </div> <?php
  }
}


// # Util Function
// Function to output Next Previous Links
if ( !function_exists( 'ampforwp_next_previous_links' ) ) {
  function ampforwp_next_previous_links() {
    global $redux_builder_amp;
    if($redux_builder_amp['enable-single-next-prev']) { ?>
      <div class="amp-wp-content post-pagination-meta">
        <div id="pagination">
          <?php $next_post = get_next_post();
            if (!empty( $next_post )) { ?>
              <span><?php echo $redux_builder_amp['amp-translator-next-read-text']; ?></span> <a href="<?php echo trailingslashit( trailingslashit( get_permalink( $next_post->ID ) ) . AMPFORWP_AMP_QUERY_VAR ); ?>"><?php echo $next_post->post_title; ?> &raquo;</a> <?php
            } ?>
        </div>
      </div> <?php
    }
  }
}


// # Core Function
// Function of Element Content
if ( !function_exists( 'ampforwp_content_element_content' ) ) {
  add_action( 'ampforwp_content_elements_content' , 'ampforwp_content_element_content' );
  function ampforwp_content_element_content( $amp_post_template_object ) {
    global $redux_builder_amp;
    if( $redux_builder_amp['amp-frontpage-select-option'] && !is_single() ){
      $amp_post_template_object = ampforwp_get_template_data_object();
    } ?>
    <div class="amp-wp-article-content"> <?php
      // Post Content here ?>
    	<div class="amp-wp-content the_content"> <?php
    		 do_action('ampforwp_before_post_content'); //Post before Content here
    		 $amp_custom_content_enable = get_post_meta( $amp_post_template_object->get( 'post_id' ) , 'ampforwp_custom_content_editor_checkbox', true);
    			// Normal Front Page Content
    			if ( ! $amp_custom_content_enable ) {
    				echo $amp_post_template_object->get( 'post_amp_content' ); // amphtml content; no kses
    			} else {
    				// Custom/Alternative AMP content added through post meta
    				echo $amp_post_template_object->get( 'ampforwp_amp_content' );
    			}
     	   do_action('ampforwp_after_post_content') ; //Post After Content here ?>
    	</div> <?php
    	// Post Content Ends here
     	ampforwp_next_previous_links() ; ?>
    </div>
    <?php
  }
}


if( !function_exists( 'ampforwp_singular_after_header_content' ) ) {
  add_action( 'ampforwp_after_header', 'ampforwp_singular_after_header_content');
  function ampforwp_singular_after_header_content( $post_data_object ){ ?>
   <main>
     <article class="amp-wp-article"> <?php
        do_action('ampforwp_post_before_design_elements', $post_data_single);
        do_action('ampforwp_post_after_design_elements', $post_data_object); ?>
     </article>
   </main> <?php
  }
}


if( !function_exists( 'ampforwp_frontpage_after_header_content' ) ) {
  if( is_amp_front_page() ) {
    add_action( 'ampforwp_after_header', 'ampforwp_frontpage_after_header_content');
  } else {
    add_action( 'ampforwp_after_header', 'ampforwp_single_after_header_content');
  }
  function ampforwp_frontpage_after_header_content( $post_data_object ){ ?>

      <div class="amp-wp-content the_content"> <?php
        $amp_custom_content_enable = get_post_meta($post_data_object->data['post_id'], 'ampforwp_custom_content_editor_checkbox', true);
        if ( ! $amp_custom_content_enable ) {
          echo $post_data_object->data['post_amp_content'];
        } else {
          echo $post_data_object->data['ampforwp_amp_content'];
        }
        do_action( 'ampforwp_after_post_content', $post_data_object ); ?>
      </div>
        <?php ampforwp_comments_pagination( $post_data_object->data['post_id'] ); ?>
      <div class="amp-wp-content post-pagination-meta">
        <?php $post_data_object->load_parts( apply_filters( 'amp_post_template_meta_parts', array( 'meta-taxonomy' ) ) ); ?>
      </div>
      <?php ampforwp_the_social_share();
  }
}


if( !function_exists( 'ampforwp_single_after_header_content' ) ) {
  function ampforwp_single_after_header_content( $post_data_object ){
    $post_data_object->load_parts( apply_filters( 'ampforwp_design_elements', array( 'empty-filter' ) ) );
  }
}