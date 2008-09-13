<?php
/**
 * Bootstrap file; lets get ready to rock!
 * This file should be required by your index.php file.
 * 
 * @author Luke Antins <luke@lividpenguin.com>
 * @copyright Copyright (c) 2008, Luke Antins
 * @package itsy
 */

/**
 * Defines the version number of the itsy framework.
 */
define('ITSY_VERSION',  '1.0');

if (!defined('ITSY_PATH')) {
  /**
   * The ITSY_PATH is where we can find the other itsy files/libraries.
   * We define this _before_ calling this bootstrap file if were running tests.
   */
  define('ITSY_PATH', dirname(__FILE__) . '/');
}

// Load core files
require_once ITSY_PATH . 'itsy.class.php';
require_once ITSY_PATH . 'itsy_request.class.php';
require_once ITSY_PATH . 'helpers.php'; // TODO: Change how we load helpers.

itsy::setup();

?>