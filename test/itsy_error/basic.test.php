<?php
require_once 'PHPUnit/Framework.php';
 
class test_itsy_error_basic extends PHPUnit_Framework_TestCase
{
  public function test_example1()
  {
    $this->markTestIncomplete('This test has not been implemented yet.');
  }
}

?>