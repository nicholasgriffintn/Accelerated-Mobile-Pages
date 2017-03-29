<?php
//51. Adding Digg Digg compatibility with AMP
function ampforwp_dd_exclude_from_amp() {
if(ampforwp_is_amp_endpoint()) {
    remove_filter('the_excerpt', 'dd_hook_wp_content');
    remove_filter('the_content', 'dd_hook_wp_content');
	}
}
add_action('template_redirect', 'ampforwp_dd_exclude_from_amp');


//37. compatibility with wp-html-compression
function ampforwp_copat_wp_html_compression() {
	remove_action('template_redirect', 'wp_html_compression_start', -1);
	remove_action('get_header', 'wp_html_compression_start');
}
add_action('amp_init','ampforwp_copat_wp_html_compression');


//34. social share boost compatibility Ticket #387
function social_sharing_removal_code() {
    remove_filter('the_content','ssb_in_content');
}
add_action('amp_init','social_sharing_removal_code', 9);