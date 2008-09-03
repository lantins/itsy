<?php

/**
 * PHP version check.
 */
version_compare(PHP_VERSION, '5.2.5', '<') and exit('PHP 5.2.5 or newer is required.');

/**
 * ERROR REPORTING
 */
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', TRUE);

/**
 * BOOTSTRAP
 *   Gets things rolling.
 */
define('ROOT_PATH', dirname(getcwd()) . '/');
require_once ROOT_PATH . 'lib/itsy/bootstrap.php';

itsy::$db['itsy'] = new itsy_db();
itsy::$db['itsy']->connect('itsy');

$controller = empty($_GET['c']) ? 'default' : $_GET['c'];
$action = empty($_GET['a']) ? 'index' : $_GET['a'];
$partial = isset($_GET['partial']);

itsy::dispatch($controller, $action, null, $partial, true);

?>