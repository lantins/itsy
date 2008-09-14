<?php

class test_itsy_helper_basic extends PHPUnit_Framework_TestCase
{
  public function test_default_to()
  {
    $foo = '';
    $cats = 'purrrrr';
    $this->assertEquals('bar1', itsy_helper::default_to('bar1', $foo));
    $this->assertEquals('purrrrr', itsy_helper::default_to('meow', $cats));
  }
  
  public function test_cycle()
  {
    for ($i=0; $i<10; $i++) {
      $test = itsy_helper::cycle('foo', 'bar');
      if (($i % 2) == 0) {
        $this->assertEquals('foo', $test);
      } else {
        $this->assertEquals('bar', $test);
      }
    }
  }
}

?>