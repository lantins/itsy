<?php defined('ITSY_PATH') or die('No direct script access.');

/**
 * APPLICATION CONFIGURATION
 *   Configuration used by Itsy.
 */
 
$config = array();
$config['environment'] = 'development'; // 'development' or 'production'.
$config['controller_dir'] = 'app/controllers/'; // where to find the controller files.
$config['view_dir'] = 'app/views/'; // where to find the view files.
$config['layout_dir'] = 'app/layouts/'; // where to find the layout files.

// database config
$config['db'] = array(
  'development' => array(
    'itsy' => array(
      'user' => 'root',
      'pass' => 'root',
      'host' => 'localhost'
    )
  ),
  'production' => array(
    'itsy' => array(
      'user' => 'itsy',
      'pass' => 'moo',
      'host' => 'localhost'
      )
    )
  );
?>