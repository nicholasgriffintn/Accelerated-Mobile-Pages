<?php
// 10. Analytics Area
  add_action('amp_post_template_footer','ampforwp_analytics',11);
  function ampforwp_analytics() {
    // 10.1 Analytics Support added for Google Analytics
    global $redux_builder_amp;
    if ( $redux_builder_amp['amp-analytics-select-option']=='1' ){ ?>
        <amp-analytics type="googleanalytics" id="analytics1">
          <script type="application/json">
          {
            "vars": {
              "account": "<?php global $redux_builder_amp; echo $redux_builder_amp['ga-feild']; ?>"
            },
            "triggers": {
              "trackPageview": {
                "on": "visible",
                "request": "pageview"
              }
            }
          }
          </script>
        </amp-analytics> <?php
      }//code ends for supporting Google Analytics

    // 10.2 Analytics Support added for segment.com
    if ( $redux_builder_amp['amp-analytics-select-option']=='2' ) { ?>
        <amp-analytics type="segment">
          <script>
          {
            "vars": {
              "writeKey": "<?php global $redux_builder_amp; echo $redux_builder_amp['sa-feild']; ?>",
              "name": "<?php echo the_title(); ?>"
            }
          }
          </script>
        </amp-analytics> <?php
    }

    // 10.3 Analytics Support added for Piwik
    if( $redux_builder_amp['amp-analytics-select-option']=='3' ) { ?>
        <amp-pixel src="<?php global $redux_builder_amp; echo $redux_builder_amp['pa-feild']; ?>"></amp-pixel> <?php
    }

    // 10.4 Analytics Support added for quantcast
    if ( $redux_builder_amp['amp-analytics-select-option']=='4' ) { ?>
        <amp-analytics type="quantcast">
          <script type="application/json">
          {
            "vars": {
              "pcode": "<?php echo $redux_builder_amp['amp-quantcast-analytics-code']; ?>",
              "labels": [ "AMPProject" ]
            }
          }
          </script>
        </amp-analytics> <?php
      }

    // 10.5 Analytics Support added for comscore
    if ( $redux_builder_amp['amp-analytics-select-option']=='5' ) { ?>
        <amp-analytics type="comscore">
          <script type="application/json">
          {
            "vars": {
              "c1": "<?php echo $redux_builder_amp['amp-comscore-analytics-code-c1']; ?>",
              "c2": "<?php echo $redux_builder_amp['amp-comscore-analytics-code-c2']; ?>"
            }
          }
          </script>
        </amp-analytics> <?php
      }

  }//analytics function ends here
  // Create GTM support
  add_filter( 'amp_post_template_analytics', 'amp_gtm_add_gtm_support' );
  function amp_gtm_add_gtm_support( $analytics ) {
    global $redux_builder_amp;
    if ( ! is_array( $analytics ) ) {
      $analytics = array();
    }
    $analytics['amp-gtm-googleanalytics'] = array(
      'type' => $redux_builder_amp['amp-gtm-analytics-type'],
      'attributes' => array(
        'data-credentials' 	=> 'include',
        'config'			=> 'https://www.googletagmanager.com/amp.json?id='. $redux_builder_amp['amp-gtm-id'] .'&gtm.url=SOURCE_URL'
      ),
      'config_data' => array(
        'vars' => array(
          'account' =>  $redux_builder_amp['amp-gtm-analytics-code']
        ),
        'triggers' => array(
          'trackPageview' => array(
            'on' => 'visible',
            'request' => 'pageview',
          ),
        ),
      ),
    );
    return $analytics;
  }