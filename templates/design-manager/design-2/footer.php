<?php global $redux_builder_amp;
  wp_reset_postdata();?>
  <footer class="container">
      <div id="footer">
        <?php if ( has_nav_menu( 'amp-footer-menu' ) ) { ?>
          <div class="footer_menu"> 
           <?php // schema.org/SiteNavigationElement missing from menus #1229 ?>
      <nav id ="primary-amp-menu" itemscope="" itemtype="https://schema.org/SiteNavigationElement">
            <?php
              $menu = wp_nav_menu( array(
                  'theme_location' => 'amp-footer-menu',
                  'link_before'     => '<span itemprop="name">',
                  'link_after'     => '</span>',
                  'echo' => false
              ) );
              echo strip_tags( $menu , '<ul><li><a>'); ?>
          </div>
        </nav>
        <?php } ?>

        <p> <?php if($redux_builder_amp['ampforwp-footer-top']=='1') { ?>
            <a href="#header">
              <?php echo ampforwp_translation( $redux_builder_amp['amp-translator-top-text'], 'Top'); ?> </a> <?php }
                if($redux_builder_amp['amp-footer-link-non-amp-page']=='1') { ?> |  <?php ampforwp_view_nonamp(); 
                } ?>
                    <?php
              global $allowed_html;
              echo wp_kses( ampforwp_translation($redux_builder_amp['amp-translator-footer-text'], 'Footer'),$allowed_html);
              ?>
          <a href="https://technutty.co.uk/">
<span class="footer-logo-center">
              <amp-img src="https://technutty.co.uk/wp-content/assets/TechNuttyLogo.svg" width="300" height="68" alt="logo" class="amp-logo" layout=responsive id="AMP_1">
              </amp-img>
            </span>
</a>
  <br>
              <div class="meta-footer">
            <a href="https://technutty.co.uk/about/">About</a> / <a href="https://technutty.co.uk/contact/">Contact</a> / <a href="https://technutty.co.uk/terms-and-conditions/">Terms and Conditions</a> / <a href="https://technutty.co.uk/privacy-policy/">Privacy Policy</a> 
   																                       </div>
  <div class="col-sm-4 meta-social-footer">
                                  <div class="social-footer">
                                     <ul>
                                     <li class="social-link-footer"><a href="http://facebook.com/technutty"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                     <li class="social-link-footer"><a href="http://twitter.com/thetechnuttyuk"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                     <li class="social-link-footer"><a href="https://plus.google.com/u/0/b/113424605348271306286/113424605348271306286/posts"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                                      <li class="social-link-footer"><a href="https://technutty.tumblr.com"><i class="fa fa-tumblr" aria-hidden="true"></i></a></li>
                                      <li class="social-link-footer"><a href="https://in.pinterest.com/technuttyuk/technutty-pins/"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li>
                                      <li class="social-link-footer"><a href="https://technutty.co.uk/feed"><i class="fa fa-rss" aria-hidden="true"></i></a></li>
                                      </ul>
                                   </div>
                              </div>
                              <br>

        </div>
    </footer>
<?php do_action('ampforwp_global_after_footer'); ?>