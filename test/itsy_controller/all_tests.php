<?php
require_once 'PHPUnit/Framework.php';

class itsy_controller_suite extends PHPUnit_Framework_TestSuite
{
  public static function suite()
  {
    $suite = new itsy_controller_suite('Itsy Core');

    return $suite;
  }
}

?>