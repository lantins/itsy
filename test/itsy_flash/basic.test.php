<?php

class test_itsy_flash_basic extends PHPUnit_Framework_TestCase
{
  public function test_basic()
  {
    $expected['message'][] = 'this is a message';
    $expected['notice'][] = 'this is a notice';
    $expected['warning'][] = 'this is a warning';
    
    // add a flash message to the default namespace 'message'.
    //itsy_flash::add('this is another message');
    
    $this->add_flash_messages();
    
    $this->assertEquals($expected['message'], itsy_flash::get('message'));
    $this->assertEquals($expected['notice'], itsy_flash::get('notice'));
    $this->assertEquals($expected['warning'], itsy_flash::get('warning'));
    
    $this->add_flash_messages();
    $this->assertEquals($expected, itsy_flash::get());
    
    itsy_flash::discard();
    $this->assertEquals(array(), itsy_flash::get('message'));
    $this->assertEquals(array(), itsy_flash::get('notice'));
    $this->assertEquals(array(), itsy_flash::get('warning'));
  }
  
  private function add_flash_messages()
  {
    itsy_flash::discard();
    $this->assertEquals(array(), itsy_flash::get());
    itsy_flash::add('message', 'this is a message');
    itsy_flash::add('notice', 'this is a notice');
    itsy_flash::add('warning', 'this is a warning');
  }
}

?>