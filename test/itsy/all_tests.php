<?php

require_once ROOT_PATH . 'test/bootstrap.php';

require_once 'autoloader.php';
require_once 'dispatch.php';
require_once 'exceptions.php';
require_once 'log.php';
require_once 'partial.php';
require_once 'setup.php';

require_once ROOT_PATH . 'lib/itsy/itsy.class.php';

class itsy_suite extends PHPUnit_Framework_TestSuite
{
  public static function suite()
  {
    $suite = new itsy_suite('Itsy Core');
    $suite->addTestSuite('test_itsy_autoloader');
    $suite->addTestSuite('test_itsy_dispatch');
    $suite->addTestSuite('test_itsy_exceptions');
    $suite->addTestSuite('test_itsy_log');
    $suite->addTestSuite('test_itsy_partial');
    $suite->addTestSuite('test_itsy_setup');
    
    return $suite;
  }
}

?>