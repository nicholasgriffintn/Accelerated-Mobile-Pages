<?php
// 9. Advertisement code
  // Below Header Global
  add_action('ampforwp_after_header','ampforwp_header_advert');
  add_action('ampforwp_design_1_after_header','ampforwp_header_advert');
  function ampforwp_header_advert() {
    global $redux_builder_amp;

    if($redux_builder_amp['enable-amp-ads-1'] == true) {
      if($redux_builder_amp['enable-amp-ads-select-1'] == 1)  {
        $advert_width  = '300';
        $advert_height = '250';
            } elseif ($redux_builder_amp['enable-amp-ads-select-1'] == 2) {
              $advert_width  = '336';
        $advert_height = '280';
      } elseif ($redux_builder_amp['enable-amp-ads-select-1'] == 3)  {
              $advert_width  = '728';
        $advert_height = '90';
            } elseif ($redux_builder_amp['enable-amp-ads-select-1'] == 4)  {
              $advert_width  = '300';
        $advert_height = '600';
            } elseif ($redux_builder_amp['enable-amp-ads-select-1'] == 5)  {
              $advert_width  = '320';
        $advert_height = '100';
          } elseif ($redux_builder_amp['enable-amp-ads-select-1'] == 6)  {
              $advert_width  = '200';
        $advert_height = '50';
          } elseif ($redux_builder_amp['enable-amp-ads-select-1'] == 7)  {
              $advert_width  = '320';
        $advert_height = '50';
          }
      $output = '<div class="amp-ad-wrapper amp_ad_1">';
      $output	.=	'<amp-ad class="amp-ad-1"
                    type="adsense"
                    width='. $advert_width .' height='. $advert_height . '
                    data-ad-client="'. $redux_builder_amp['enable-amp-ads-text-feild-client-1'].'"
                    data-ad-slot="'.  $redux_builder_amp['enable-amp-ads-text-feild-slot-1'] .'">';
      $output	.=	'</amp-ad>';
      $output	.= '</div>';
      echo $output;
    }
  }

  // Above Footer Global
  add_action('amp_post_template_footer','ampforwp_footer_advert',8);
  add_action('amp_post_template_above_footer','ampforwp_footer_advert',10);
      if ( $redux_builder_amp['amp-design-selector'] == 3) {
        remove_action('amp_post_template_footer','ampforwp_footer_advert',8);
      }

  function ampforwp_footer_advert() {
    global $redux_builder_amp;

    if($redux_builder_amp['enable-amp-ads-2'] == true) {
      if($redux_builder_amp['enable-amp-ads-select-2'] == 1)  {
        $advert_width  = '300';
        $advert_height = '250';
            } elseif ($redux_builder_amp['enable-amp-ads-select-2'] == 2) {
              $advert_width  = '336';
        $advert_height = '280';
      } elseif ($redux_builder_amp['enable-amp-ads-select-2'] == 3)  {
              $advert_width  = '728';
        $advert_height = '90';
            } elseif ($redux_builder_amp['enable-amp-ads-select-2'] == 4)  {
              $advert_width  = '300';
        $advert_height = '600';
            } elseif ($redux_builder_amp['enable-amp-ads-select-2'] == 5)  {
              $advert_width  = '320';
        $advert_height = '100';
          } elseif ($redux_builder_amp['enable-amp-ads-select-2'] == 6)  {
              $advert_width  = '200';
        $advert_height = '50';
          } elseif ($redux_builder_amp['enable-amp-ads-select-2'] == 7)  {
              $advert_width  = '320';
        $advert_height = '50';
          }
      $output = '<div class="amp-ad-wrapper">';
      $output	.=	'<amp-ad class="amp-ad-2"
                    type="adsense"
                    width='. $advert_width .' height='. $advert_height . '
                    data-ad-client="'. $redux_builder_amp['enable-amp-ads-text-feild-client-2'].'"
                    data-ad-slot="'.  $redux_builder_amp['enable-amp-ads-text-feild-slot-2'] .'">';
      $output	.=	'</amp-ad>';
      $output	.= '</div>';
      echo $output;
    }
  }

  // Below Title Single
  add_action('ampforwp_before_post_content','ampforwp_before_post_content_advert');
  add_action('ampforwp_inside_post_content_before','ampforwp_before_post_content_advert');

  function ampforwp_before_post_content_advert() {
    global $redux_builder_amp;

    if($redux_builder_amp['enable-amp-ads-3'] == true) {
      if($redux_builder_amp['enable-amp-ads-select-3'] == 1)  {
        $advert_width  = '300';
        $advert_height = '250';
            } elseif ($redux_builder_amp['enable-amp-ads-select-3'] == 2) {
              $advert_width  = '336';
        $advert_height = '280';
      } elseif ($redux_builder_amp['enable-amp-ads-select-3'] == 3)  {
              $advert_width  = '728';
        $advert_height = '90';
            } elseif ($redux_builder_amp['enable-amp-ads-select-3'] == 4)  {
              $advert_width  = '300';
        $advert_height = '600';
            } elseif ($redux_builder_amp['enable-amp-ads-select-3'] == 5)  {
              $advert_width  = '320';
        $advert_height = '100';
          } elseif ($redux_builder_amp['enable-amp-ads-select-3'] == 6)  {
              $advert_width  = '200';
        $advert_height = '50';
          } elseif ($redux_builder_amp['enable-amp-ads-select-3'] == 7)  {
              $advert_width  = '320';
        $advert_height = '50';
          }
      $output = '<div class="amp-ad-wrapper">';
      $output	.=	'<amp-ad class="amp-ad-3"
                    type="adsense"
                    width='. $advert_width .' height='. $advert_height . '
                    data-ad-client="'. $redux_builder_amp['enable-amp-ads-text-feild-client-3'].'"
                    data-ad-slot="'.  $redux_builder_amp['enable-amp-ads-text-feild-slot-3'] .'">';
      $output	.=	'</amp-ad>';
      $output	.= '</div>';
      echo $output;
    }
  }

  // Below Content Single
    add_action('ampforwp_after_post_content','ampforwp_after_post_content_advert');
    add_action('ampforwp_inside_post_content_after','ampforwp_after_post_content_advert');
  function ampforwp_after_post_content_advert() {
    global $redux_builder_amp;

    if($redux_builder_amp['enable-amp-ads-4'] == true) {
      if($redux_builder_amp['enable-amp-ads-select-4'] == 1)  {
        $advert_width  = '300';
        $advert_height = '250';
            } elseif ($redux_builder_amp['enable-amp-ads-select-4'] == 2) {
              $advert_width  = '336';
        $advert_height = '280';
      } elseif ($redux_builder_amp['enable-amp-ads-select-4'] == 3)  {
              $advert_width  = '728';
        $advert_height = '90';
            } elseif ($redux_builder_amp['enable-amp-ads-select-4'] == 4)  {
              $advert_width  = '300';
        $advert_height = '600';
            } elseif ($redux_builder_amp['enable-amp-ads-select-4'] == 5)  {
              $advert_width  = '320';
        $advert_height = '100';
          } elseif ($redux_builder_amp['enable-amp-ads-select-4'] == 6)  {
              $advert_width  = '200';
        $advert_height = '50';
          } elseif ($redux_builder_amp['enable-amp-ads-select-4'] == 7)  {
              $advert_width  = '320';
        $advert_height = '50';
          }
      $output = '<div class="amp-ad-wrapper">';
      $output	.=	'<amp-ad class="amp-ad-4"
                    type="adsense"
                    width='. $advert_width .' height='. $advert_height . '
                    data-ad-client="'. $redux_builder_amp['enable-amp-ads-text-feild-client-4'].'"
                    data-ad-slot="'.  $redux_builder_amp['enable-amp-ads-text-feild-slot-4'] .'">';
      $output	.=	'</amp-ad>';
      $output	.= '</div>';
      echo $output;
    }
  }