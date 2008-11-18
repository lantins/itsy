<?php

class itsy_validate_suite extends itsy_framework_testsuite
{
  public static function suite()
  {
    $suite = new itsy_validate_suite('itsy validate');
    $suite->addAllTestFromDir();
    
    return $suite;
  }
}

?>