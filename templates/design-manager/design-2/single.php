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
<body class="single-post <?php ampforwp_the_body_class(); ?> <?php if(is_page()){ echo'amp-single-page'; };?> design_2_wrapper">
<?php $this->load_parts( array( 'header-bar' ) ); ?>

<?php do_action( 'ampforwp_after_header', $this ); ?>
	<main>
		<article class="amp-wp-article">
			<?php do_action('ampforwp_post_before_design_elements') ?>

			<?php $this->load_parts( apply_filters( 'ampforwp_design_elements', array( 'empty-filter' ) ) ); ?>
			<?php if( get_field('source_link') ): ?>
	<div class="source-link">
	Source:
	<a href="<?php the_field('source_link'); ?>" rel"nofollow noopener">
	<?php the_field('source_name'); ?>
	</a>
	</div>
<?php endif; ?>
<?php if( get_field('source_link_2') ): ?>
	<div class="source-link">
	Source:
	<a href="<?php the_field('source_link_2'); ?>" rel"nofollow noopener">
	<?php the_field('source_name_2'); ?>
	</a>
	</div>
<?php endif; ?>
<?php if( get_field('via_link') ): ?>
	<div class="via-link">
	Via:
	<a href="<?php the_field('via_link'); ?>" rel"nofollow noopener">
	<?php the_field('via_name'); ?>
	</a>
	</div>
<?php endif; ?>
			<?php do_action('ampforwp_post_after_design_elements') ?>
		</article>
	</main>

<?php do_action( 'amp_post_template_above_footer', $this ); ?>	
<?php $this->load_parts( array( 'footer' ) ); ?>
<?php do_action( 'amp_post_template_footer', $this ); ?>
</body>
</html>