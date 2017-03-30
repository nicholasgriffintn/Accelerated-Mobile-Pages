<!doctype html>
<html amp <?php ampforwp_the_lang_code( $this )?> >
	<head>
		<?php ampforwp_the_head( $this ); ?>
	</head>
	<body class="amp_home_body <?php if( is_archive() ){ echo 'archives_body'; } ?> design_3_wrapper">
		<?php do_action('ampforwp_the_header_bar'); ?>

		if( is_home() ) { ?>
			<div class="amp-wp-content">
				<?php do_action('ampforwp_area_above_loop'); ?>
			</div> <?php
	  }

		 do_action( 'ampforwp_after_header', $this );

		 if( is_home() ) {
		 	do_action('ampforwp_home_above_loop');
		 } ?>

		 <main> <?php
		 	do_action('ampforwp_post_before_loop') ;
		 	do_action('ampforwp_loop') ;
		 	do_action('ampforwp_post_after_loop') ; ?>
		 </main> <?php

		 if( is_home() ) {
		  do_action('ampforwp_home_below_loop');
	   }

		 do_action( 'amp_post_template_above_footer', $this );
		 do_action('ampforwp_the_footer');
		 do_action('ampforwp_global_after_footer');
		 do_action( 'amp_post_template_footer', $this ); ?>
	</body>
</html>

