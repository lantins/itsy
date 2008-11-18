<?php

class itsy_filter_suite extends itsy_framework_testsuite
{
  public static function suite()
  {
    $suite = new itsy_filter_suite('itsy filter');
    $suite->addAllTestFromDir();
    
    return $suite;
  }
}

?>