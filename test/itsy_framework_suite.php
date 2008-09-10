<?php

error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', TRUE);

define('ROOT_PATH', dirname(getcwd()) . '/');
define('ITSY_PATH', ROOT_PATH . 'lib/itsy/');

date_default_timezone_set('Europe/London');

require_once 'PHPUnit/Framework.php';
require_once ROOT_PATH . 'test/itsy/all_tests.php';
#require_once ROOT_PATH . 'test/itsy_controller/all_tests.php';
#require_once ROOT_PATH . 'test/itsy_db/all_tests.php';
#require_once ROOT_PATH . 'test/itsy_error/all_tests.php';
#require_once ROOT_PATH . 'test/itsy_filter/all_tests.php';
#require_once ROOT_PATH . 'test/itsy_flash/all_tests.php';
#require_once ROOT_PATH . 'test/itsy_registry/all_tests.php';
#require_once ROOT_PATH . 'test/itsy_request/all_tests.php';
#require_once ROOT_PATH . 'test/itsy_validate/all_tests.php';

class itsy_framework_suite extends PHPUnit_Framework_TestSuite
{
  public static function suite()
  {
    $suite = new itsy_framework_suite('Itsy Framework');
    $suite->addTest(itsy_suite::suite());
    #$suite->addTest(itsy_controller_suite::suite());
    #$suite->addTest(itsy_db_suite::suite());
    #$suite->addTest(itsy_error_suite::suite());
    #$suite->addTest(itsy_filter_suite::suite());
    #$suite->addTest(itsy_flash_suite::suite());
    #$suite->addTest(itsy_registry_suite::suite());
    #$suite->addTest(itsy_request_suite::suite());
    #$suite->addTest(itsy_validate_suite::suite());

    return $suite;
  }
  
  protected function setUp()
  {
  }
  
  protected function tearDown()
  {
  }
}

?>