<?php
//53. Adding the Markup for AMP Woocommerce latest Products
/*******************************
Examples:

[amp-woocommerce num=5]
[amp-woocommerce num=5 link=noamp]
[amp-woocommerce num=5 link=amp]
*******************************/
 function get_amp_latest_prodcuts_markup( $atts ) {
   // initializing these to avoid debug errors
   global $post;
   $atts[] = shortcode_atts( array(
                                 'num' => get_permalink($atts['num']),
                                 'link' => get_permalink($atts['link'])
                                ), $atts );

   $exclude_ids = get_option('ampforwp_exclude_post');
   $number_of_latest_prcts = $atts['num'] ;

    $q = new WP_Query( array(
     'post_type'           => 'product',
     'orderby'             => 'date',
     'paged'               => esc_attr($paged),
     'post__not_in' 		  => $exclude_ids,
     'has_password' => false,
     'post_status'=> 'publish',
     'posts_per_page' => $number_of_latest_prcts
    ) );

    if ( $q->have_posts() ) :  $content .= '<ul class="ampforwp_wc_shortcode">';
          while ( $q->have_posts() ) : $q->the_post();
      if( $atts['link'] === 'amp' ) {
        $ampforwp_post_url = trailingslashit( get_permalink() ) . AMPFORWP_AMP_QUERY_VAR ;
      } else {
        $ampforwp_post_url = trailingslashit( get_permalink() ) ;
      }
          $content .= '<li class="ampforwp_wc_shortcode_child"><a href="'.$ampforwp_post_url.'">';
          global $redux_builder_amp;
          // $content .= '<div class="amp-wp-content ampforwp-wc-parent"><div class="amp-wp-content featured-image-content">';
          if ( has_post_thumbnail() ) {
            $thumb_id = get_post_thumbnail_id();
            $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail', true);
            $thumb_url = $thumb_url_array[0];

            $content .= '<amp-img src='.$thumb_url.' width="150" height="150" ></amp-img>' ;
          }
          // $content .= '</div>';
          $content .= '<div class="ampforwp-wc-title">'.get_the_title().'</div>';
          if (  class_exists( 'WooCommerce' )  ) {
            // $content .= '<div class="ampforwp-wc-price">';
            global $woocommerce;
            $amp_product_price 	=  $woocommerce->product_factory->get_product()->get_price_html();
            $context = '';
            $allowed_tags 		= wp_kses_allowed_html( $context );

            if ( $amp_product_price ) {
              $content .= '<div class="ampforwp-wc-price">' .  wp_kses( $amp_product_price,  $allowed_tags  ) .'</div>' ;
            } else {
              // $content .= "Sorry, this item is not for sale at the moment, please check out more products <a href=" . esc_url( home_url('/shop') ) . "> Here </a> " ;
            }
          }
        $content .= '</a></li>';  ?>
        <?php endwhile;  $content .= '</ul>'; ?>
    <?php endif; ?>
    <?php wp_reset_postdata();

     // Add AMP Woocommerce latest Products only on AMP Endpoint
     $endpoint_check = is_amp_endpoint();
     if ( $endpoint_check ) {
       return $content;
     }
 }

 // Generating Short code for AMP Woocommerce latest Products
 function ampforwp_latest_products_register_shortcodes() {
   add_shortcode('amp-woocommerce', 'get_amp_latest_prodcuts_markup');
 }
 add_action( 'amp_init', 'ampforwp_latest_products_register_shortcodes');

 // Adding the styling for AMP Woocommerce latest Products
 add_action('amp_post_template_css','amp_latest_products_styling',999);
 function amp_latest_products_styling() { ?>
  .ampforwp_wc_shortcode{padding:0}
  .ampforwp_wc_shortcode li{ font-size:12px; line-height: 1; float: left;max-width: 150px;list-style-type: none;margin: 10px;}
  .single-post .ampforwp_wc_shortcode li amp-img{margin:0}
  .ampforwp-wc-title{ margin: 10px 0px; }
  .ampforwp-wc-price{ color:#444 } <?php
 }