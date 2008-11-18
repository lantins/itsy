<?php defined('ITSY_PATH') or die('No direct script access.');

/**
 * APPLICATION CONFIGURATION
 *   Configuration used by itsy.
 */
$app_path = dirname(dirname(__FILE__)) . '/';
$config = array(
  '/itsy/app_path' => $app_path,
  // sqlite3 database
  '/itsy/db/test/dsn' => 'sqlite:' . $app_path . 'db/test.sqlite3',
  '/itsy/db/test/database' => '',
  // mysql database
  '/itsy/db/showcase/dsn' => 'mysql:dbname=itsy_showcase;host=localhost',
  '/itsy/db/showcase/user' => 'itsy_showcase',
  '/itsy/db/showcase/pass' => 'pAsSwOrD'
  );
itsy_registry::load($config);

?>