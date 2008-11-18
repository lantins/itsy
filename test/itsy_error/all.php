<?php

class itsy_error_suite extends itsy_framework_testsuite
{
  public static function suite()
  {
    $suite = new itsy_error_suite('itsy error');
    $suite->addAllTestFromDir();
    
    return $suite;
  }
}

?>