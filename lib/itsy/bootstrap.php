<?php
/**
 * Copyright (c) 2008 Luke Antins <luke@lividpenguin.com>
 * All rights reserved.
 *
 * http://www.lividpenguin.com/
 */

/**
 * Bootstrap file. Lets get ready!
 * Loaded by the front controller.
 */

define('ITSY_VERSION',  '1.0');

// Define path to core ITSY files
if (!defined('ITSY_PATH')) {
  define('ITSY_PATH', dirname(__FILE__) . '/');
}

// Load core files
require_once ITSY_PATH . 'itsy.class.php';
require_once ITSY_PATH . 'itsy_request.class.php';
require_once ITSY_PATH . 'helpers.php'; // TODO: Change how we load helpers.

itsy::setup();

?>