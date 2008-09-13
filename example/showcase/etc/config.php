<?php defined('ITSY_PATH') or die('No direct script access.');

/**
 * APPLICATION CONFIGURATION
 *   Configuration used by Itsy.
 */

$app_path = dirname(dirname(__FILE__)) . '/';
$config = array(
  '/itsy/app_path' => $app_path,
  // sqlite3 database
  '/itsy/db/test/engine' => 'sqlite',
  '/itsy/db/test/database' => 'db/test.sqlite3',
  // mysql database
  '/itsy/db/showcase/engine' => 'mysql',
  '/itsy/db/showcase/host' => 'localhost',
  '/itsy/db/showcase/database' => 'itsy_showcase',
  '/itsy/db/showcase/user' => 'itsy_showcase',
  '/itsy/db/showcase/pass' => 'pAsSwOrD'
  );
itsy_registry::load($config);

?>