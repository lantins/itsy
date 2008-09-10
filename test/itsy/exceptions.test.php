<?php
require_once 'PHPUnit/Framework.php';
 
class test_itsy_exceptions extends PHPUnit_Framework_TestCase
{
  public function test_exception_basic()
  {
    $this->setExpectedException('itsy_exception');
    itsy::load_controller('this_will_fail_to_load');
  }
  
  public function test_exception_real()
  {
    $this->setExpectedException('itsy_exception');
    throw new itsy_exception('Test exception.');
  }
  
  
}

?>