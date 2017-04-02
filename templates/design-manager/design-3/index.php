<?php

	$this->load_parts( array( 'd3-header' ) ); ?>

	<body class="<?php echo apply_filters( 'ampforwp_body_class_filter' , ''); ?>">
		<?php do_action('ampforwp_the_header_bar'); ?>

		<div class="amp-wp-content"> <?php
			do_action('ampforwp_area_above_loop'); ?>
		</div> <?php

		do_action( 'ampforwp_after_header', $this );

		do_action('ampforwp_home_above_loop'); ?>

		<main> <?php
        // TODO: Add carousel into hook instead of direct function call of the loop.
		 do_action('ampforwp_post_before_loop') ;
		 do_action('ampforwp_loop') ;
		 do_action('ampforwp_post_after_loop') ; ?>
		</main> <?php

		do_action('ampforwp_home_below_loop');

    $this->load_parts( array( 'd3-footer' ) ); ?>