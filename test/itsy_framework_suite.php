<?php

error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', TRUE);

define('ROOT_PATH', dirname(getcwd()) . '/');
define('ITSY_PATH', ROOT_PATH . 'lib/itsy/');

date_default_timezone_set('Europe/London');

// setup itsy
require_once ITSY_PATH . 'bootstrap.php';

// phpunit
require_once 'PHPUnit/Framework.php';

// itsy files
require_once ITSY_PATH . 'itsy.class.php';
require_once ITSY_PATH . 'itsy_controller.class.php';
require_once ITSY_PATH . 'itsy_db.class.php';
require_once ITSY_PATH . 'itsy_error.class.php';
require_once ITSY_PATH . 'itsy_filter.class.php';
require_once ITSY_PATH . 'itsy_flash.class.php';
require_once ITSY_PATH . 'itsy_helper.class.php';
require_once ITSY_PATH . 'itsy_registry.class.php';
require_once ITSY_PATH . 'itsy_request.class.php';
//require_once ITSY_PATH . 'itsy_validate.class.php'; // we will load this to test the autoloader.

class itsy_framework_testsuite extends PHPUnit_Framework_TestSuite
{
  public function addAllTestFromDir()
  {
    $name = get_class($this);
    $name = str_replace('_suite', '', $name);
    $dir = getcwd() . '/' . $name;
    
    // find all the .test.php files in the cwd.
    $files = scandir($dir);
    
    foreach ($files as $file) {
      if (ereg('\.test\.php$', $file)) {
        require_once $dir . '/' . $file;
        
        $suite_name = str_replace('.test.php', '', $file);
        $suite_name = 'test_' . $name . '_' . $suite_name;
        $this->addTestSuite($suite_name);
      }
    }
  }
}

class itsy_framework_suite extends PHPUnit_Framework_TestSuite
{
  public static function suite()
  {
    $test_suites = array(
      'itsy', 'itsy_controller', 'itsy_db', 'itsy_error', 'itsy_filter',
      'itsy_flash', 'itsy_helper', 'itsy_registry', 'itsy_request',
      'itsy_validate'
      );
    
    $suite = new itsy_framework_suite('itsy framework');
    
    foreach ($test_suites as $test_suite) {
      $suite_file = ROOT_PATH . "test/$test_suite/all.php";
      if (is_readable($suite_file)) {
        require_once $suite_file;
        $call = call_user_func(array($test_suite . '_suite', 'suite'));
        $suite->addTestSuite($call);
      }
    }
    
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