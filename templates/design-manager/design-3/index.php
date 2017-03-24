<!doctype html>
<html amp <?php ampforwp_the_lang_code( $this )?> >
	<head>
		<?php ampforwp_the_head( $this ); ?>
	</head>
	<body class="amp_home_body design_3_wrapper">
		<?php $this->load_parts( array( 'header-bar' ) ); ?>
		<div class="amp-wp-content">
			<?php do_action('ampforwp_area_above_loop'); ?>
		</div>
		<?php do_action( 'ampforwp_after_header', $this );
		 do_action('ampforwp_home_above_loop') ?>
		 <main> <?php
		 	do_action('ampforwp_post_before_loop') ;
		 	do_action('ampforwp_loop') ;
		 	do_action('ampforwp_post_after_loop') ; ?>
		 </main>
		<?php do_action('ampforwp_home_below_loop') ?>
		<?php do_action( 'amp_post_template_above_footer', $this ); ?>
		<?php $this->load_parts( array( 'footer' ) ); ?>
		<?php do_action( 'amp_post_template_footer', $this ); ?>
	</body>
</html>