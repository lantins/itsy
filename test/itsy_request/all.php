<?php

class itsy_request_suite extends itsy_framework_testsuite
{
  public static function suite()
  {
    $suite = new itsy_request_suite('itsy request');
    $suite->addAllTestFromDir();
    
    return $suite;
  }
}

?>