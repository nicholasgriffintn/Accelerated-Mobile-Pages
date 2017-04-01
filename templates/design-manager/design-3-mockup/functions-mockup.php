<?php 
// Include Scripts
add_action('ampforwp_regiester_scripts','ampforwp_enqueue_scritps');
function ampforwp_enqueue_scritps() {
    add_amp_script('amp-sidebar');
    add_amp_script('amp-carousel');   
}


// Header Code
add_action('ampforwp_the_header_bar', 'ampforwp_design_3_the_header');
if(!function_exists('ampforwp_design_3_the_header')) {
  function ampforwp_design_3_the_header() { 
      global $redux_builder_amp; ?>


<amp-sidebar id='sidebar' layout="nodisplay" side="left">
    <div class="toggle-navigationv2">
    <div class="ampforwp-design3-nav"><?php ampforwp_nav(); ?></div>
    <div class="ampforwp-design3-social-profiles"><?php ampforwp_social_profiles(); ?></div>    
    </div>
</amp-sidebar>






<div id="designthree" class="designthree main_container">
    <header class="container">
      <div id="headerwrap">
          <div id="header">
            <div class="hamburgermenu">
                <button class="toast pull-left" on='tap:sidebar.toggle'><span></span></button>
            </div>
            <div class="headerlogo">
            <?php ampforwp_logo(); ?>
            </div>

            <div class="header-search">
                <?php ampforwp_search(); ?>
            </div>
      </div>
    </header>
    </div>
    <?php
  }
}



// Footer

// Loop
