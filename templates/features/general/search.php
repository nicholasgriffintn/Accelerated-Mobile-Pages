<?php
// 46. search search search everywhere #615
add_action('pre_amp_render_post','ampforwp_search_related_functions',12);
function ampforwp_search_related_functions(){
  global $redux_builder_amp;
  if ( ampforwp_is_search_enabled() ) {
        add_action('ampforwp_search_form','ampforwp_the_search_form');
  }
}

add_action('ampforwp_global_after_footer','ampforwp_lightbox_html_output');
function ampforwp_lightbox_html_output() {
  if ( ampforwp_is_search_enabled() ) {
    global $redux_builder_amp;
    if( ampforwp_is_search_enabled() ) { ?>
        <amp-lightbox id="search-icon" layout="nodisplay">
            <?php do_action('ampforwp_search_form'); ?>
            <button on="tap:search-icon.close" class="closebutton">X</button>
            <i class="icono-cross"></i>
        </amp-lightbox> <?php
    }
  }
}

add_action( 'ampforwp_header_search' , 'ampforwp_search_button_html_output' );
function ampforwp_search_button_html_output(){
  if ( ampforwp_is_search_enabled() ) {
   global $redux_builder_amp;
   if( ampforwp_is_search_enabled() ) { ?>
        <div class="searchmenu">
          <button on="tap:search-icon">
            <i class="icono-search"></i>
          </button>
        </div> <?php
    }
  }
}


function ampforwp_the_search_form() {
    echo ampforwp_get_search_form();
}
function ampforwp_get_search_form() {
  if ( ampforwp_is_search_enabled() ) {
    global $redux_builder_amp;
    $label = $redux_builder_amp['ampforwp-search-label'];
    $placeholder = $redux_builder_amp['ampforwp-search-placeholder'];
    $form = '<form role="search" method="get" id="searchform" class="searchform" target="_top" action="' . get_bloginfo('url')  .'">
              <div>
                <label class="screen-reader-text" for="s">' . $label . '</label>
                <input type="text" placeholder="AMP" value="1" name="amp" class="hide" id="ampsomething" />
                <input type="text" placeholder="'.$placeholder.'" value="' . get_search_query() . '" name="s" id="s" />
                <input type="submit" id="searchsubmit" value="'. esc_attr_x( 'Search', 'submit button' ) .'" />
              </div>
            </form>';
      return $form;
    }
}