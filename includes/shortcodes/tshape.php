<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use TShape\View\TShape;

/**
 * Display Shortcode
 *
 * @param $atts
 *
 * @return string
 */
function wpts_tshape_shortcode($atts)
{

  extract(shortcode_atts(
    [
      "id"     => '',
      "height" => '150',
      "type"   => 'standard',
      "size"   => '',
    ],
    $atts

  ));

  if (is_numeric($id)) {

    $tshape              = new TShape($id);
    $tshape_display_html = $tshape->render($atts);
    return $tshape_display_html;


  }
  else {

    $tshape_display_html = __('Please add an id to your shortcode!', "wp-tshape");
    return $tshape_display_html;

  }

}
