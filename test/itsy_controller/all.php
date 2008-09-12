<?php

class itsy_controller_suite extends itsy_framework_testsuite
{
  public static function suite()
  {
    $suite = new itsy_controller_suite('Itsy Controller');
    $suite->addAllTestFromDir();
    
    return $suite;
  }
}

?>