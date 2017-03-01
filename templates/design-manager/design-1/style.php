<?php add_action('amp_post_template_css', 'ampforwp_additional_style_input');
function ampforwp_additional_style_input( $amp_template ) {
	global $redux_builder_amp;
	$get_customizer = new AMP_Post_Template( $post_id );
	$content_max_width       = absint( $get_customizer->get( 'content_max_width' ) );
	$theme_color             = $get_customizer->get_customizer_setting( 'theme_color' );
	$text_color              = $get_customizer->get_customizer_setting( 'text_color' );
	$muted_text_color        = $get_customizer->get_customizer_setting( 'muted_text_color' );
	$border_color            = $get_customizer->get_customizer_setting( 'border_color' );
	$link_color              = $get_customizer->get_customizer_setting( 'link_color' );
	$header_background_color = $get_customizer->get_customizer_setting( 'header_background_color' );
	$header_color            = $get_customizer->get_customizer_setting( 'header_color' );
?>
.alignright {float: right;} .alignleft {float: left;} .aligncenter {display: block;margin-left: auto;margin-right: auto;}
.amp-wp-enforced-sizes {max-width: 100%;margin: 0 auto;}
.amp-wp-unknown-size img {object-fit: contain;} amp-iframe { max-width: 100%; margin-bottom : 20px; }
.amp-wp-content,.amp-wp-title-bar div {<?php if ( $content_max_width > 0 ) : ?> margin: 0 auto;max-width: <?php echo sprintf( '%dpx', $content_max_width ); ?>; <?php endif; ?> }
html{background: <?php echo sanitize_hex_color( $header_background_color ); ?>;} body{background: <?php echo sanitize_hex_color( $theme_color ); ?>;color: <?php echo sanitize_hex_color( $text_color ); ?>;font-family: 'Merriweather', 'Times New Roman', Times, Serif;font-weight: 300;line-height: 1.75em;}
p,ol,ul,figure {margin: 0 0 1em;padding: 0;} a,a:visited {color: <?php echo sanitize_hex_color( $link_color ); ?>;}a:hover,a:active,a:focus {color: <?php echo sanitize_hex_color( $text_color ); ?>;} .wp-caption amp-img{max-width: 100%}
blockquote {color: <?php echo sanitize_hex_color( $text_color ); ?>;background: rgba(127,127,127,.125);border-left: 2px solid <?php echo sanitize_hex_color( $link_color ); ?>;margin: 8px 0 24px 0;padding: 16px;} blockquote p:last-child {margin-bottom: 0;}
.amp-wp-meta,.amp-wp-header .ampforwp-logo-area,.amp-wp-title,.wp-caption-text,.amp-wp-tax-category,.amp-wp-tax-tag,.amp-wp-comments-link,.amp-wp-footer p,.back-to-top {font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", "Oxygen-Sans", "Ubuntu", "Cantarell", "Helvetica Neue", sans-serif;}
.amp-wp-header {background-color: <?php echo sanitize_hex_color( $header_background_color ); ?>;}
.amp-wp-header .ampforwp-logo-area {color: <?php echo sanitize_hex_color( $header_color ); ?>;font-size: 1em;font-weight: 400;margin: 0 auto;max-width: calc(840px - 32px);padding: .875em 16px;position: relative;}  .amp-wp-header .amp-wp-site-icon {background-color: <?php echo sanitize_hex_color( $header_color ); ?>;border: 1px solid <?php echo sanitize_hex_color(  $header_color ); ?>;border-radius: 50%;position: absolute;right: 18px;top: 10px;}
.amp-wp-article {color: <?php echo sanitize_hex_color( $text_color ); ?>;font-weight: 400;margin: 1.5em auto;max-width: 840px;overflow-wrap: break-word;word-wrap: break-word;} .amp-wp-article-header {align-items: center;align-content: stretch;display: flex;flex-wrap: wrap;justify-content: space-between;margin: 1.5em 16px 1.5em;}
.amp-wp-title {color: <?php echo sanitize_hex_color( $text_color ); ?>;display: block;flex: 1 0 100%;font-weight: 900;margin: 0;width: 100%;}.amp-wp-meta {color: <?php echo sanitize_hex_color( $muted_text_color ); ?>;display: inline-block;flex: 2 1 50%;font-size: .875em;line-height: 1.7em;margin: 0;padding: 0;}.ampforwp-meta-info{margin-top: 0px;}.amp-wp-article-header .amp-wp-meta:last-of-type {text-align: right;}.amp-wp-article-header .amp-wp-meta:first-of-type {text-align: left;}.amp-wp-byline amp-img,.amp-wp-byline .amp-wp-author {display: inline-block;vertical-align: middle;}.amp-wp-byline amp-img {border: 1px solid <?php echo sanitize_hex_color( $link_color ); ?>;border-radius: 50%;position: relative;margin-right: 6px;}.amp-wp-posted-on {text-align: right;}
.amp-wp-article-featured-image {margin: 1.5em 16px 1.5em;}.amp-wp-article-featured-image amp-img {margin: 0 auto;}.amp-wp-article-featured-image.wp-caption .wp-caption-text {margin: 0 18px;}.amp-wp-frontpage .the_content {padding: 10px;}.amp-wp-frontpage .ampforwp-title {margin-left:10px;}.amp-wp-article a{text-decoration:none}.amp-wp-article-content {margin: 0 16px;}.amp-wp-article-content ul,.amp-wp-article-content ol {margin-left: 1em;}.amp-wp-article-content amp-img {margin: 0 auto;}.amp-wp-article-content amp-img.alignright {margin: 0 0 1em 16px;}.amp-wp-article-content amp-img.alignleft {margin: 0 16px 1em 0;} .amp-disqus-comments {padding: 15px;}.amp-disqus-comments amp-iframe{background: none;}.wp-caption {padding: 0;}.wp-caption.alignleft {margin-right: 16px;}.wp-caption.alignright { margin-left: 16px;}.wp-caption-text {border-bottom: 1px solid <?php echo sanitize_hex_color( $border_color ); ?>;color: <?php echo sanitize_hex_color( $muted_text_color ); ?>;font-size: .875em;line-height: 1.5em;margin: 0;padding: .66em 10px .75em;text-align: center;} amp-carousel {background: <?php echo sanitize_hex_color( $border_color ); ?>;margin: 0 -16px 1.5em;} amp-iframe,amp-youtube,amp-instagram,amp-vine {background: <?php echo sanitize_hex_color( $border_color ); ?>;margin: 0 -16px 1.5em; } .amp-wp-article-content amp-carousel amp-img {border: none;} amp-carousel > amp-img > img {object-fit: contain; } .amp-wp-iframe-placeholder { background: <?php echo sanitize_hex_color( $border_color ); ?> url( <?php echo esc_url( $get_customizer->get( 'placeholder_image_url' ) ); ?> ) no-repeat center 40%;background-size: 48px 48px;min-height: 48px;} .amp-wp-article-footer .amp-wp-meta {display: block;} .amp-wp-tax-category span{margin-right:5px;} .amp-wp-tax-category, .amp-wp-tax-tag { color: <?php echo sanitize_hex_color( $muted_text_color ); ?>;font-size: .875em;line-height: 1.5em;margin: 1.5em 16px;}.ampforwp-comment-button {margin-bottom:20px;} .amp-wp-comments-link {color: <?php echo sanitize_hex_color( $muted_text_color ); ?>;font-size: .875em;line-height: 1.5em;text-align: center;margin: 2.25em 0 1.5em;} .amp-wp-comments-link a { border-style: solid;border-color: <?php echo sanitize_hex_color( $border_color ); ?>;border-width: 1px 1px 2px;border-radius: 4px;background-color: transparent;color: <?php echo sanitize_hex_color( $link_color ); ?>;cursor: pointer; display: block;font-size: 14px;font-weight: 600;line-height: 18px;margin: 0 auto;max-width: 200px;padding: 11px 16px;text-decoration: none;width: 50%;-webkit-transition: background-color 0.2s ease;transition: background-color 0.2s ease;} .page-title {margin: 0 15px;} .amp-wp-footer {border-top: 1px solid <?php echo sanitize_hex_color( $border_color ); ?>;margin: calc(1.5em - 1px) 0 0;padding-bottom:25px;}
.amp-wp-footer div{margin:0 auto;max-width:calc(840px - 32px);padding:1.25em 16px;position:relative}.amp-wp-footer h2{font-size:1em;line-height:1.375em;margin:0 0 .5em}
.amp-wp-footer p {color: <?php echo sanitize_hex_color( $muted_text_color ); ?>;font-size: .8em;line-height: 1.5em;margin: 0 15px 0 0;}
.amp-wp-footer a{text-decoration:none}.copyright_txt{float:left}.back-to-top{float:right}.amp-wp-header .nav_container{float: right;top: 16px;line-height: 1;   right: 65px; position: absolute}.toggle-text{position:absolute;right:0;height:22px;width:28px}.toggle-text span{display:block;position:absolute;height:2px;width:25px;background:#fff;border-radius:19px;opacity:1;left:0}.toggle-text span:nth-child(2){top:9px}.toggle-text span:nth-child(3){top:18px}.amp-wp-home .amp-wp-meta{margin:5px 0}.amp-wp-home .amp-wp-content p{display:inline-block;width:100%}.ampforwp-custom-index .amp-wp-title a {text-decoration: none;color: <?php echo sanitize_hex_color( $text_color ); ?>;}.comment-button-wrapper a,.related_posts ol{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif}.amp-wp-meta{display:flex}.amp-wp-posted-on{display:initial}#pagination .next,#pagination .prev{display:inline-block}.ampforwp-custom-index .amp-wp-content{margin-bottom:30px}.pagination-holder{margin:1.5em 16px}#pagination .next{float:right}.amp-wp-home .amp-wp-content p{display:inline}.home-post-image{float:right;margin:0 0 10px 20px}.amp-wp-article-content amp-img{max-width:100%}.amp-wp-meta.amp-wp-tax-category,.amp-wp-meta.amp-wp-tax-tag{margin:0}.amp-wp-meta.amp-wp-tax-tag{display:initial}.ampforwp-social-icons{margin:1.5em 16px}.whatsapp-share-icon{width:50px;height:20px;display:inline-block;background:#5cbe4a;padding:4px 0;position:relative;top:-4px;text-align:center}.comment-button-wrapper a{border-style:solid;border-color:#c2c2c2;border-width:1px 1px 2px;border-radius:4px;background-color:transparent;color:#0a89c0;cursor:pointer;display:block;font-size:14px;font-weight:600;text-align:center;line-height:18px;margin:0 auto;max-width:200px;padding:11px 16px;text-decoration:none;width:50%;-webkit-transition:background-color .2s ease;transition:background-color .2s ease}.close-nav,.comments_list div,.related_posts ol li,.toggle-navigation ul,.toggle-navigationv2 ul li a{display:inline-block}main .amp-wp-content.comments_list,main .amp-wp-content.relatedpost{background:0 0;box-shadow:none;max-width:1030px}.relatedpost{margin:2em 16px}.comments_list h3,.related_posts h3{font-size:14px;font-weight:700;letter-spacing:.4px;margin:25px 0 10px;color:#333}.related_posts ol{list-style-type:none;margin:0;padding:0}.related_posts ol li{width:100%;margin-bottom:12px;padding:0}.related_posts .related_link a{color:#000;font-size:18px}.related_posts ol li amp-img{width:100px;float:left;margin-right:15px}.related_posts ol li p{font-size:12px;color:#999;line-height:1.2;margin:12px 0 0}.no_related_thumbnail{padding:15px 18px}.comments_list{margin:2.5em 16px}.comments_list ul{margin:0;padding:0}.comments_list ul.children{padding-bottom:10px;margin-left:4%;width:96%}.comments_list ul li p{margin:0;font-size:14px;clear:both;padding-top:5px}.comments_list ul li{font-family:sans-serif;font-size:11px;list-style-type:none;margin-bottom:12px;background:#fefefe;-moz-border-radius:2px;-webkit-border-radius:2px;border-radius:2px;-moz-box-shadow:0 2px 3px rgba(0,0,0,.05);-webkit-box-shadow:0 2px 3px rgba(0,0,0,.05);box-shadow:0 2px 3px rgba(0,0,0,.05);padding:0;max-width:1000px;width:96%}.comments_list ul li .says{margin-right:4px}.comments_list li li,.comments_list li li li{margin:20px 20px 10px}.comments_list ul li p{font-family:Merriweather,'Times New Roman',Times,Serif}.comments_list ul li .comment-body{padding:10px 0 15px}.comment-author{float:left}.single-post footer.comment-meta{padding-bottom:0}.comments_list li li{background:#f7f7f7;box-shadow:none;border:1px solid #eee}amp-sidebar{width:250px}.amp-sidebar-image{line-height:100px;vertical-align:middle}.amp-close-image{top:15px;left:225px;cursor:pointer}.toggle-navigationv2 ul{list-style-type:none;margin:0;font-family:sans-serif;padding:0}.toggle-navigationv2 ul ul li a{padding-left:35px;background:#fff;display:inline-block}.toggle-navigationv2 ul li a{padding:10px 15px 10px 25px;width:88%;text-decoration:none;background:#fafafa;font-size:13px;border-bottom:1px solid #efefef}.close-nav{font-size:12px;font-family:sans-serif;background:rgba(0,0,0,.25);letter-spacing:1px;padding:10px;border-radius:100px;line-height:8px;margin:14px;left:191px;color:#fff}.close-nav:hover{background:rgba(0,0,0,.45)}.toggle-navigation ul{list-style-type:none;margin:0;padding:0;width:100%}.menu-all-pages-container:after{content:"";clear:both}.toggle-navigation ul li{font-size:13px;border-bottom:1px solid rgba(0,0,0,.11);padding:11px 0;width:25%;float:left;text-align:center;margin-top:6px}.toggle-navigation ul ul{display:none}.toggle-navigation ul li a{color:#eee;padding:15px}.toggle-navigation{display:none;background:#444}.nav_container:hover+.toggle-navigation,.toggle-navigation:active,.toggle-navigation:focus,.toggle-navigation:hover{display:inline-block;width:100%}#amp-user-notification1 p{display:inline-block}amp-user-notification{padding:5px;text-align:center;background:#fff;border-top:1px solid} amp-user-notification button {padding: 8px 10px;background:  <?php echo sanitize_hex_color( $header_background_color ); ?>;color: <?php echo sanitize_hex_color( $header_color ); ?>;margin-left: 5px;border: 0;}amp-user-notification button:hover {cursor: pointer} .amp-ad-wrapper {text-align: center} <?php if( $redux_builder_amp['enable-single-social-icons'] == true && is_single() )  { ?>body {padding-bottom: 43px;}<?php } ?> .sticky_social{width:100%;bottom:0;display:block;left:0;box-shadow:0 4px 7px #000;background:#fff;padding:7px 0 0;position:fixed;margin:0;z-index:10;text-align:center}.whatsapp-share-icon{width:50px;height:20px;display:inline-block;background:#5cbe4a;padding:4px 0;position:relative;top:-4px}.amp-wp-tax-category span:first-child:after{content:' '}.amp-wp-tax-category span:after,.amp-wp-tax-tag span:after{content:', '}.amp-wp-tax-category span:last-child:after,.amp-wp-tax-tag span:last-child:after{content:' '}pre{white-space:pre-wrap}.amp-ad-wrapper.amp_ad_1{padding-top:20px}

/* Category 1 */
.amp-category-block ul{
    list-style-type:none
}
.design_1_wrapper .amp-category-block{
    max-width: 840px;
    margin: 1.5em auto;
}
.amp-category-block ul{
    margin: 1.5em 16px 1.5em;
}
.amp-category-post{

}
.searchmenu{
    margin-right: 15px;
    margin-top: 10px;
    position: absolute;
    top: 0;
    right: 91px;
}
.searchmenu button{
    background:transparent;
    border:none
}    
 
.closebutton{
    background: transparent;
    border: 0;
    color: rgba(255, 255, 255, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.7);
    border-radius: 30px;
    width: 32px;
    height: 32px;
    font-size: 12px;
    text-align: center;
    position: absolute;
    top: 12px;
    right: 20px;
    outline:none
}
amp-lightbox{
    background: rgba(0, 0, 0,0.85);
}
/* CSS3 icon */

[class*=icono-]:after, [class*=icono-]:before {
    content: '';
    pointer-events: none;
}
.icono-search:before{
    position: absolute;
    left: 50%;
    -webkit-transform: rotate(270deg);
    -ms-transform: rotate(270deg);
     transform: rotate(270deg);
    width: 2px;
    height: 9px;
    box-shadow: inset 0 0 0 32px;
    top: 0px;
    border-radius: 0 0 1px 1px;
    left: 14px;
} 
[class*=icono-] {
    display: inline-block;
    vertical-align: middle;
    position: relative;
    font-style: normal;
    color: #f42;
    text-align: left;
    text-indent: -9999px;
    direction: ltr
}

.icono-search {
    -webkit-transform: translateX(-50%);
    -ms-transform: translateX(-50%);
    transform: translateX(-50%)
}

.icono-search {
    border: 1px solid;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
    margin: 4px 4px 8px 8px;
}
.searchform label{
    color: #f7f7f7;
    display: block;
    font-size: 10px;
    line-height: 0;
    opacity:0.6
}
.searchform{
    background: transparent;
    left: 20%;
    position: absolute;
    top: 35%;
    width: 60%;
    max-width: 100%;
    transition-delay: 0.5s;
}
.searchform input{
    background: transparent;
    border: 1px solid #666;
    color: #f7f7f7;
    font-size: 14px;
    font-weight: 400;
    line-height: 1;
    letter-spacing: 0.3px;
    text-transform: capitalize;
    padding: 20px 0px 20px 30px;
    margin-top: 15px;
    width: 100%;
}
#searchsubmit{display:none}
.hide{display:none}
.amp-wp-header .ampforwp-search-nav-wrapper {
    padding: 0;
}
 
.ampforwp-search-nav-wrapper .searchmenu {
    margin-top: 20px;
}
.headerlogo a, [class*=icono-]{
    top:0;
}
.amp-wp-header a, .headerlogo a, [class*=icono-] {color: <?php echo sanitize_hex_color( $header_color ); ?>;text-decoration: none;}
<?php if($redux_builder_amp['enable-single-social-icons']){ ?> .amp-wp-footer{padding-bottom: 60px;}<?php } ?>
<?php if( is_rtl() ) { ?>
.amp-wp-header .amp-wp-site-icon,.amp-wp-header .nav_container{float:left;right:initial;left:-11px}.amp-wp-header .amp-wp-site-icon{position:relative;top:-3px}
<?php } ?>
<?php
if ( class_exists('TablePress') ) { ?>
.tablepress-table-description{clear:both;display:block}.tablepress{border-collapse:collapse;border-spacing:0;width:100%;margin-bottom:1em;border:none}.tablepress td,.tablepress th{padding:8px;border:none;background:0 0;text-align:left}.tablepress tbody td{vertical-align:top}.tablepress tbody td,.tablepress tfoot th{border-top:1px solid #ddd}.tablepress tbody tr:first-child td{border-top:0}.tablepress thead th{border-bottom:1px solid #ddd}.tablepress tfoot th,.tablepress thead th{background-color:#d9edf7;font-weight:700;vertical-align:middle}.tablepress .odd td{background-color:#f9f9f9}.tablepress .even td{background-color:#fff}.tablepress .row-hover tr:hover td{background-color:#f3f3f3}@media (min-width:768px) and (max-width:1600px){.tablepress{overflow-x:none}}@media (min-width:320px) and (max-width:767px){.tablepress{display:inline-block;overflow-x:scroll}}
<?php }  ?>
<?php echo $redux_builder_amp['css_editor']; } ?>