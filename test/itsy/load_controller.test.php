<?php

class test_itsy_load_controller extends PHPUnit_Framework_TestCase
{
  public function test_invalid_controller()
  {
    $this->setExpectedException('itsy_exception');
    itsy::load_controller('this_will_fail_to_load');
  }
  
  public function test_valid_controller()
  {
    itsy::load_controller('default_controller');
  }
}

?>