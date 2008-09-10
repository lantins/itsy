<?php

error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', TRUE);

define('ROOT_PATH', dirname(getcwd()) . '/');
define('ITSY_PATH', ROOT_PATH . 'lib/itsy/');

date_default_timezone_set('Europe/London');

// phpunit
require_once 'PHPUnit/Framework.php';

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
      'itsy_flash', 'itsy_registry', 'itsy_request', 'itsy_validate'
      );
    
    $suite = new itsy_framework_suite('Itsy Framework');
    
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