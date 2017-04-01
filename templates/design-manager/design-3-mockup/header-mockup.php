<!doctype html>
<html amp <?php echo apply_filters( 'ampforwp_lang_filter', $this); ?> >

<head><?php do_action('ampforwp_head', $this); ?></head>

<body class="<?php echo apply_filters( 'ampforwp_body_class_filter' , ''); ?>">

<?php
    do_action('ampforwp_the_header_bar');
    do_action( 'ampforwp_after_header', $this );
?>