<?php
require_once 'PHPUnit/Framework.php';
require_once 'get.php';
require_once 'set.php';
require_once 'set_base_path.php';

require_once ROOT_PATH . 'lib/itsy/itsy_registry.class.php';

class itsy_registry_suite extends PHPUnit_Framework_TestSuite
{
  public static function suite()
  {
    $suite = new itsy_controller_suite('Itsy Registry');
    $suite->addTestSuite('test_itsy_registry_get');
    $suite->addTestSuite('test_itsy_registry_set');
    $suite->addTestSuite('test_itsy_registry_set_base_path');
    

    return $suite;
  }
}

?>