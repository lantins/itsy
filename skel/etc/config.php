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
    'database_lable' => array(
      'user' => 'development_username',
      'pass' => 'development_password',
      'host' => 'localhost'
    )
  ),
  'production' => array(
    'database_lable' => array(
      'user' => 'production_username',
      'pass' => 'production_password',
      'host' => 'localhost'
      )
    )
  );
?>