<?php

class itsy_filter_suite extends itsy_framework_testsuite
{
  public static function suite()
  {
    $suite = new itsy_filter_suite('Itsy Filter');
    $suite->addAllTestFromDir();
    
    return $suite;
  }
}

?>