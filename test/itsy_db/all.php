<?php

require_once TEST_ROOT_PATH . 'lib/itsy/itsy_db.class.php';

class itsy_db_suite extends itsy_framework_testsuite
{
  public static function suite()
  {
    $suite = new itsy_db_suite('Itsy DB');
    $suite->addAllTestFromDir();
    
    return $suite;
  }
}

?>