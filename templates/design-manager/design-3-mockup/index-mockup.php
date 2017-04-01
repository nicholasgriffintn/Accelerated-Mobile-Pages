<?php ampforwp_header(); ?>

<?php do_action('ampforwp_home_above_loop'); ?>

<main>
<?php
// TODO: Add carousel into hook instead of direct function call of the loop.
do_action('ampforwp_post_before_loop');
do_action('ampforwp_loop');
do_action('ampforwp_post_after_loop');
?>
</main>

<?php do_action('ampforwp_home_below_loop'); ?>

<?php ampforwp_footer(); ?>