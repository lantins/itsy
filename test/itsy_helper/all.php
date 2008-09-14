<?php

class itsy_helper_suite extends itsy_framework_testsuite
{
  public static function suite()
  {
    $suite = new itsy_helper_suite('Itsy Helper');
    $suite->addAllTestFromDir();
    
    return $suite;
  }
}

?>