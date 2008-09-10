<?php

require_once ROOT_PATH . 'lib/itsy/itsy_request.class.php';

class itsy_request_suite extends itsy_framework_testsuite
{
  public static function suite()
  {
    $suite = new itsy_request_suite('Itsy Request');
    $suite->addAllTestFromDir();
    
    return $suite;
  }
}

?>