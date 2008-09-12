<?php

// this is used by the unit tests; please do not edit if you want the tests to pass.
class test_controller extends itsy_controller
{
  public function no_layout()
  {
    $this->_layout = null;
  }
  
  public function forward()
  {
    return $this->_forward('test', 'forward_foo');
  }
  
  public function forward_foo()
  {
    $this->_layout = null;
  }
}

?>