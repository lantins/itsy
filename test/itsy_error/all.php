<?php

class itsy_error_suite extends itsy_framework_testsuite
{
  public static function suite()
  {
    $suite = new itsy_error_suite('Itsy Error');
    $suite->addAllTestFromDir();
    
    return $suite;
  }
}

?>