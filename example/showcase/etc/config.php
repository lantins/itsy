<?php defined('ITSY_PATH') or die('No direct script access.');

/**
 * APPLICATION CONFIGURATION
 *   Configuration used by Itsy.
 */

$app_path = dirname(dirname(__FILE__)) . '/';
$config = array(
  '/itsy/app_path' => $app_path,
  '/itsy/db/showcase/development/user' => 'root',
  '/itsy/db/showcase/development/pass' => 'root',
  '/itsy/db/showcase/development/host' => 'localhost',
  '/itsy/db/showcase/production/user' => 'showcase',
  '/itsy/db/showcase/production/pass' => 'pAsSwOrD',
  '/itsy/db/showcase/production/host' => 'localhost'
  );
itsy_registry::load($config);

?>