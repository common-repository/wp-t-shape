<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function burps_wp_tshape_autoload($class_name)
{
  $file = __DIR__ . '/../includes/classes/' . str_replace("\\", '/', $class_name) . '.php';

  if (file_exists($file)) {
    require_once($file);
  }
}

spl_autoload_register('burps_wp_tshape_autoload');