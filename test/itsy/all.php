<?php

class itsy_suite extends itsy_framework_testsuite
{
  public static function suite()
  {
    $suite = new itsy_suite('itsy core');
    $suite->addAllTestFromDir();
    
    return $suite;
  }
}

?>