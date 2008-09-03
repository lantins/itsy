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
define('ITSY_PATH', ROOT_PATH . 'lib/itsy/');

// Load core files
require_once ITSY_PATH . 'itsy.class.php';
require_once ROOT_PATH . 'lib/itsy/helpers.php';

itsy::setup();

?>