<?php global $redux_builder_amp;?>
<!doctype html>
<html amp <?php echo AMP_HTML_Utils::build_attributes_string( $this->get( 'html_tag_attributes' ) ); ?>>
<head>
	<meta charset="utf-8">
    <link rel="dns-prefetch" href="https://cdn.ampproject.org">
	<?php do_action( 'amp_post_template_head', $this ); ?>
	<style amp-custom>
	<?php $this->load_parts( array( 'style' ) ); ?>
	<?php do_action( 'amp_post_template_css', $this ); ?>
	</style>
</head>
<body class="single-post <?php if(is_page()){ echo'amp-single-page'; };?> design_2_wrapper">
<?php $this->load_parts( array( 'header-bar' ) ); ?>

<?php do_action( 'ampforwp_after_header', $this ); ?>
	<main>
		<article class="amp-wp-article">
			<?php do_action('ampforwp_post_before_design_elements') ?>

			<?php $this->load_parts( apply_filters( 'ampforwp_design_elements', array( 'empty-filter' ) ) ); ?>
			<?php do_action('ampforwp_post_after_design_elements') ?>
		</article>

		<footer class="container">
        <div id="footer">
        <a class="non-amp-link full-footer-link" href="<?php echo $ampforwp_backto_nonamp; ?>" rel="nofollow">View the original article</a>
        <a class="to-top-link full-footer-link" href="#header" rel="nofollow"><?php echo ampforwp_translation( $redux_builder_amp['amp-translator-top-text'], 'Top'); ?> </a> <?php
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
	
	</main>

<?php do_action( 'amp_post_template_above_footer', $this ); ?>	
<?php $this->load_parts( array( 'footer' ) ); ?>
<?php do_action( 'amp_post_template_footer', $this ); ?>
</body>
</html>