<?php

require_once TEST_ROOT_PATH . 'lib/itsy/itsy_flash.class.php';

class itsy_flash_suite extends itsy_framework_testsuite
{
  public static function suite()
  {
    $suite = new itsy_flash_suite('Itsy Flash');
    $suite->addAllTestFromDir();
    
    return $suite;
  }
}

?>