<?php global $redux_builder_amp;
  wp_reset_postdata();
  global $post;
  $ampforwp_backto_nonamp = '';
  if ( is_home() ) {
    if($redux_builder_amp['amp-mobile-redirection']==1)
       $ampforwp_backto_nonamp = trailingslashit(home_url()).'?nonamp=1' ;
    else
       $ampforwp_backto_nonamp = trailingslashit(home_url()) ;
  }
  if ( is_single() ){
    if($redux_builder_amp['amp-mobile-redirection']==1)
      $ampforwp_backto_nonamp = trailingslashit(get_permalink( $post->ID )).'?nonamp=1' ;
    else
      $ampforwp_backto_nonamp = trailingslashit(get_permalink( $post->ID )) ;
  }
  if ( is_page() ){
    if($redux_builder_amp['amp-mobile-redirection']==1)
        $ampforwp_backto_nonamp = trailingslashit(get_permalink( $post->ID )).'?nonamp=1';
    else
      $ampforwp_backto_nonamp = trailingslashit(get_permalink( $post->ID ));
  }
  if( is_archive() ) {
    global $wp;
    if($redux_builder_amp['amp-mobile-redirection']==1){
        $ampforwp_backto_nonamp = esc_url( untrailingslashit(home_url( $wp->request )).'?nonamp=1'  );
        $ampforwp_backto_nonamp = preg_replace('/\/amp\?nonamp=1/','/?nonamp=1',$ampforwp_backto_nonamp);
      }
    else{
        $ampforwp_backto_nonamp = esc_url( untrailingslashit(home_url( $wp->request )) );
        $ampforwp_backto_nonamp = preg_replace('/amp/','',$ampforwp_backto_nonamp);
      }
  }
  ?>
 <footer class="container">
        <div id="footer">
        <a class="non-amp-link full-footer-link" href="<?php echo $ampforwp_backto_nonamp; ?>" rel="nofollow">View the original article</a>
        <a class="to-top-link full-footer-link" href="#header" rel="nofollow">Go back to the top</a>
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
