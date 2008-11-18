<?php

class itsy_db_suite extends itsy_framework_testsuite
{
  public static function suite()
  {
    $suite = new itsy_db_suite('itsy db');
    $suite->addAllTestFromDir();
    
    return $suite;
  }
}

?>