<?php

class itsy_controller_suite extends itsy_framework_testsuite
{
  public static function suite()
  {
    $suite = new itsy_controller_suite('itsy controller');
    $suite->addAllTestFromDir();
    
    return $suite;
  }
}

?>