<?php
require_once 'PHPUnit/Framework.php';

require_once ROOT_PATH . 'lib/itsy/itsy_controller.class.php';

class itsy_controller_suite extends PHPUnit_Framework_TestSuite
{
  public static function suite()
  {
    $suite = new itsy_controller_suite('Itsy Controller');

    return $suite;
  }
}

?>