<?php global $redux_builder_amp;
  wp_reset_postdata();

  $ampforwp_backto_nonamp = " ";
  if ( is_home() ) {
    $ampforwp_backto_nonamp = untrailingslashit(home_url()).'?nonamp=1';
  }
  if ( is_single() ){
    global $post;
    $ampforwp_backto_nonamp = untrailingslashit(get_permalink( $post->ID )).'?nonamp=1';
  }
  if ( is_page() ){
    global $post;
    $ampforwp_backto_nonamp = untrailingslashit(get_permalink( $post->ID )).'?nonamp=1';
  }
  if( is_archive() ) {
    global $wp;
    $ampforwp_backto_nonamp = esc_url( untrailingslashit(home_url( $wp->request )).'?nonamp=1' );
    $ampforwp_backto_nonamp = preg_replace('/\/amp\?nonamp=1/','?nonamp=1',$ampforwp_backto_nonamp);
  }
  ?>

  <footer class="footer_wrapper container">
      <div id="footer">
        <?php if ( has_nav_menu( 'amp-footer-menu' ) ) { ?>
          <div class="footer_menu">
              <?php
                    wp_nav_menu( array(
                        'theme_location' => 'amp-footer-menu',
                    ) );
              ?>
          </div>
        <?php } ?>

        <?php ampforwp_social_profiles() ?>

          <p class="rightslink">
            <?php
              global $allowed_html;
              echo wp_kses($redux_builder_amp['amp-translator-footer-text'],$allowed_html) ;
              ?>
              <?php
              //24. Added an options button for switching on/off link to non amp page
              if($redux_builder_amp['amp-footer-link-non-amp-page']=='1') {
                if ( $ampforwp_backto_nonamp ) { ?> | <a href="<?php echo $ampforwp_backto_nonamp; ?>" rel="nofollow"><?php echo esc_html( $redux_builder_amp['amp-translator-non-amp-page-text'] ) ;?> </a> <?php  }
              } ?>
          </p>
          <?php global $redux_builder_amp; if( $redux_builder_amp['amp-design-3-credit-link'] ) { ?>
          <p class="poweredby">
              <a href="https://ampforwp.com" rel="nofollow">Powered by AMPforWP</a>
          <p>
            <?php } ?>
      </div>
  </footer>
</div><!--Design3 Ends-->

 <?php
 do_action('ampforwp_global_after_footer');

 ?>