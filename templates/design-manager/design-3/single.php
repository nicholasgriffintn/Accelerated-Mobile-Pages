<?php
global $redux_builder_amp;
if( is_front_page() ) {
	$front_page_id = $redux_builder_amp['amp-frontpage-select-option-pages'];
	$amp_post_template_object = new AMP_Post_Template( $post_id ); ?>
	<!doctype html>
	<html amp <?php echo AMP_HTML_Utils::build_attributes_string( $amp_post_template_object->get( 'html_tag_attributes' ) ); ?> > <?php
} else { ?>
<!doctype html>
<html amp <?php echo AMP_HTML_Utils::build_attributes_string( $this->get( 'html_tag_attributes' ) ); ?> >
<?php } ?>
	<head>
		<meta charset="utf-8">
	  <link rel="dns-prefetch" href="https://cdn.ampproject.org">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">

		<?php if( is_front_page() ) { ?>
			<link rel="canonical" href="<?php echo get_permalink( $front_page_id ) ?>"> <?php
			ampforwp_the_template_head( $amp_post_template_object );
			ampforwp_the_css( $amp_post_template_object );
		} else {
		 ampforwp_the_template_head( $this );
		 ampforwp_the_css( $this );
		} ?>
	</head>
	<body class="<?php echo is_front_page() ? "single-post design_3_wrapper" : is_single() ? "design_3_wrapper single-post" : "design_3_wrapper single-post amp-single-page" ?> "> <?php

	if( is_front_page() ) {
		$amp_post_template_object->load_parts( array( 'header-bar' ) ); ?>
		<header class="amp-wp-article-header ampforwp-title amp-wp-content">
			<h1 class="amp-wp-title"> <?php
				if( $front_page_id ) {
					echo get_the_title( $front_page_id );
				} ?>
			</h1>
		</header> <?php
		do_action( 'ampforwp_after_header', $amp_post_template_object );
		do_action('ampforwp_frontpage_above_loop');
	} else {
		$this->load_parts( array( 'header-bar' ) );
		do_action( 'ampforwp_after_header', $this );
	} ?>

	<main>
		<?php if( is_front_page() ) { ?>
			<div class="amp-wp-content the_content"> <?php
				$amp_custom_content_enable = get_post_meta($amp_post_template_object->data['post_id'], 'ampforwp_custom_content_editor_checkbox', true);
				if ( ! $amp_custom_content_enable ) {
					echo $amp_post_template_object->data['post_amp_content'];
				} else {
					echo $amp_post_template_object->data['ampforwp_amp_content'];
				}
				do_action( 'ampforwp_after_post_content', $amp_post_template_object ); ?>
			</div>
				<?php ampforwp_comments_pagination( $amp_post_template_object->data['post_id'] ); ?>
			<div class="amp-wp-content post-pagination-meta">
				<?php $amp_post_template_object->load_parts( apply_filters( 'amp_post_template_meta_parts', array( 'meta-taxonomy' ) ) ); ?>
			</div>
			<?php ampforwp_the_social_share();
		} else { ?>
			<article class="amp-wp-article">
				<?php do_action('ampforwp_post_before_design_elements') ?>
				<?php $this->load_parts( apply_filters( 'ampforwp_design_elements', array( 'empty-filter' ) ) ); ?>
				<?php do_action('ampforwp_post_after_design_elements') ?>
			</article> <?php
		} ?>
		</main> <?php

	if( is_front_page() ) {
		do_action('ampforwp_frontpage_below_loop');
		do_action( 'amp_post_template_above_footer', $amp_post_template_object );
		$amp_post_template_object->load_parts( array( 'footer' ) );
		do_action( 'amp_post_template_footer', $amp_post_template_object );
	} else {
		do_action( 'amp_post_template_above_footer', $this );
		$this->load_parts( array( 'footer' ) );
		do_action( 'amp_post_template_footer', $this );
	} ?>
	</body>
</html>