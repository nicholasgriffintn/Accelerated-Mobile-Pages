<?php global $redux_builder_amp;  
if ( is_single() ) { ?>
<?php do_action('ampforwp_before_social_icons_hook',$this); ?>

<div class="amp-wp-content post-pagination-meta ampforwp-social-icons-wrapper ampforwp-social-icons">
<div class="amp-share">
 <a href="http://twitter.com/home/?status=<?php the_title(); ?> - <?php the_permalink(); ?> via @TheTechNuttyUK" target="_blank" class="as-twitter" title="Tweet"><i class="fa fa-twitter" aria-hidden="true"></i></a>
 <a target="_blank" href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&amp;t=<?php the_title(); ?>" class="as-facebook" title="Share on Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
</div>

<?php } ?>
<?php do_action('ampforwp_after_social_icons_hook',$this);