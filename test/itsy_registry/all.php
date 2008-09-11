<?php

require_once TEST_ROOT_PATH . 'lib/itsy/itsy_registry.class.php';

class itsy_registry_suite extends itsy_framework_testsuite
{
  public static function suite()
  {
    $suite = new itsy_registry_suite('Itsy Registry');
    $suite->addAllTestFromDir();
    
    return $suite;
  }
}

?>