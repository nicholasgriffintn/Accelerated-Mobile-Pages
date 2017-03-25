<?php
if ( is_customize_preview() ) {
	// Load all the elements in the customizer as we want all the elements in design-manager
	add_filter( 'ampforwp_design_elements', 'ampforwp_add_element_the_title' );
	add_filter( 'ampforwp_design_elements', 'ampforwp_add_element_meta_info' );
	add_filter( 'ampforwp_design_elements', 'ampforwp_add_element_featured_image' );
	add_filter( 'ampforwp_design_elements', 'ampforwp_add_element_the_content' );
	add_filter( 'ampforwp_design_elements', 'ampforwp_add_element_meta_taxonomy' );
	add_filter( 'ampforwp_design_elements', 'ampforwp_add_element_social_icons' );
	add_filter( 'ampforwp_design_elements', 'ampforwp_add_element_comments' );
	add_filter( 'ampforwp_design_elements', 'ampforwp_add_element_related_posts' );
}

	$data = get_option( 'ampforwp_design' );

	// Adding default Value
	if ($data['elements'] == '') {
	 	$data['elements'] = "meta_info:1,title:1,featured_image:1,content:1,meta_taxonomy:1,social_icons:1,comments:1,related_posts:1";
	}

	if( isset( $data['elements'] ) || ! empty( $data['elements'] ) ){
		$options = explode( ',', $data['elements'] );
	};

	if ($options): foreach ($options as $key=>$value) {
		if ( ! is_customize_preview() ) {
			switch ($value) {
				case 'title:1':
						add_filter( 'ampforwp_design_elements', 'ampforwp_add_element_the_title' );
						break;
				case 'meta_info:1':
						add_filter( 'ampforwp_design_elements', 'ampforwp_add_element_meta_info' );
						break;
				case 'featured_image:1':
						add_filter( 'ampforwp_design_elements', 'ampforwp_add_element_featured_image' );
						break;
				case 'content:1':
						add_filter( 'ampforwp_design_elements', 'ampforwp_add_element_the_content' );
						break;
				case 'meta_taxonomy:1':
						add_filter( 'ampforwp_design_elements', 'ampforwp_add_element_meta_taxonomy' );
						break;
				case 'social_icons:1':
						add_filter( 'ampforwp_design_elements', 'ampforwp_add_element_social_icons' );
						define('AMPFORWP_DM_SOCIAL_CHECK','true');
						break;
				case 'comments:1':
						add_filter( 'ampforwp_design_elements', 'ampforwp_add_element_comments' );
						break;
				case 'related_posts:1':
						add_filter( 'ampforwp_design_elements', 'ampforwp_add_element_related_posts' );
						break;
				case 'comments:0':
						add_filter( 'ampforwp_design_elements', 'ampforwp_add_element_simple_comment_button' );
						break;
			}
		}
	}
	endif;


add_action('pre_amp_render_post','ampforwp_stylesheet_file_insertion', 12 );
function ampforwp_stylesheet_file_insertion() {
        require AMPFORWP_DESIGN_SPECIFIC_STYLE_FILE;
}


// Post Title
function ampforwp_add_element_the_title( $meta_parts ) {
	$meta_parts[] = 'ampforwp-the-title';
	return $meta_parts;
}
add_filter( 'amp_post_template_file', 'ampforwp_design_element_the_title', 10, 3 );
function ampforwp_design_element_the_title( $file, $type, $post ) {
	if ( 'ampforwp-the-title' === $type ) {
		$file = AMPFORWP_DESIGN_SPECIFIC_TITLE_FILE ;
	}
	return $file;
}


// Meta Info
function ampforwp_add_element_meta_info( $meta_parts ) {
	$meta_parts[] = 'ampforwp-meta-info';
	return $meta_parts;
}
add_filter( 'amp_post_template_file', 'ampforwp_design_element_meta_info', 10, 3 );
function ampforwp_design_element_meta_info( $file, $type, $post ) {
	if ( 'ampforwp-meta-info' === $type ) {
		$file = AMPFORWP_DESIGN_SPECIFIC_META_INFO_FILE ;
	}
	return $file;
}


// Featured Image
function ampforwp_add_element_featured_image( $meta_parts ) {
	$meta_parts[] = 'ampforwp-featured-image';
	return $meta_parts;
}
add_filter( 'amp_post_template_file', 'ampforwp_design_element_featured_image', 10, 3 );
function ampforwp_design_element_featured_image( $file, $type, $post ) {
	if ( 'ampforwp-featured-image' === $type ) {
		$file = AMPFORWP_DESIGN_SPECIFIC_FEATURED_IMAGE_FILE;
	}
	return $file;
}


// The Content
function ampforwp_add_element_the_content( $meta_parts ) {
	$meta_parts[] = 'ampforwp-the-content';
	return $meta_parts;
}
add_filter( 'amp_post_template_file', 'ampforwp_design_element_the_content', 10, 3 );
function ampforwp_design_element_the_content( $file, $type, $post ) {
	if ( 'ampforwp-the-content' === $type ) {
		$file = AMPFORWP_DESIGN_SPECIFIC_CONTENT_FILE;
	}
	return $file;
}


// Meta Texonomy
function ampforwp_add_element_meta_taxonomy( $meta_parts ) {
	$meta_parts[] = 'ampforwp-meta-taxonomy';
	return $meta_parts;
}
add_filter( 'amp_post_template_file', 'ampforwp_design_element_meta_taxonomy', 10, 3 );
function ampforwp_design_element_meta_taxonomy( $file, $type, $post ) {
	if ( 'ampforwp-meta-taxonomy' === $type ) {
		$file = AMPFORWP_DESIGN_SPECIFIC_META_TAXONOMY_FILE;
	}
	return $file;
}


// Social Icons
function ampforwp_add_element_social_icons( $meta_parts ) {
	$meta_parts[] = 'ampforwp-social-icons';
	return $meta_parts;
}
add_filter( 'amp_post_template_file', 'ampforwp_design_element_social_icons', 10, 3 );
function ampforwp_design_element_social_icons( $file, $type, $post ) {
	if ( 'ampforwp-social-icons' === $type ) {
		$file = AMPFORWP_DESIGN_SPECIFIC_SOCIAL_ICONS_FILE;
	}
	return $file;
}


// Comments
function ampforwp_add_element_comments( $meta_parts ) {
	$meta_parts[] = 'ampforwp-comments';
	return $meta_parts;
}
add_filter( 'amp_post_template_file', 'ampforwp_design_element_comments', 10, 3 );
function ampforwp_design_element_comments( $file, $type, $post ) {
	if ( 'ampforwp-comments' === $type ) {
		$file = AMPFORWP_DESIGN_SPECIFIC_COMMENTS_FILE;
	}
	return $file;
}


// simple comment button
function ampforwp_add_element_simple_comment_button( $meta_parts ) {
	$meta_parts[] = 'ampforwp-simple-comment-button';
	return $meta_parts;
}
add_filter( 'amp_post_template_file', 'ampforwp_design_element_simple_comment_button', 10, 3 );
function ampforwp_design_element_simple_comment_button( $file, $type, $post ) {
	if ( 'ampforwp-simple-comment-button' === $type ) {
		$file = AMPFORWP_DESIGN_SPECIFIC_SIMPLE_COMMENTS_FILE;
	}
	return $file;
}


// Related Posts
function ampforwp_add_element_related_posts( $meta_parts ) {
	$meta_parts[] = 'ampforwp-related-posts';
	return $meta_parts;
}
add_filter( 'amp_post_template_file', 'ampforwp_design_element_related_posts', 10, 3 );
function ampforwp_design_element_related_posts( $file, $type, $post ) {
	if ( 'ampforwp-related-posts' === $type ) {
		$file = AMPFORWP_DESIGN_SPECIFIC_RELEATED_POST_FILE;
	}
	return $file;
}
?>