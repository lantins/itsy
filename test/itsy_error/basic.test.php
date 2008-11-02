<?php

class test_itsy_error_basic extends PHPUnit_Framework_TestCase
{
  public function test_basic()
  {
    $expected_array['foo'][] = 'foo 1';
    $expected_array['foo'][] = 'foo 2';
    $expected_array['bar'][] = 'bar 1';
    $expected_array['meow'][] = 'meow 1';
    $expected = (object) $expected_array;
    
    $e = new itsy_error();
    
    $e->add('foo', 'foo 1');
    $e->add('foo', 'foo 2');
    $e->add('bar', 'bar 1');
    $e->add('meow', 'meow 1');
    
    $this->assertEquals(2, $e->count('foo'));
    $this->assertEquals(1, $e->count('bar'));
    $this->assertEquals(1, $e->count('meow'));
    $this->assertEquals(4, $e->count());
    
    $this->assertEquals((array) $expected->foo, (array) $e->on('foo'));
    $this->assertEquals((array) $expected->bar, (array) $e->on('bar'));
    $this->assertEquals((array) $expected->meow, (array) $e->on('meow'));
    $this->assertEquals((array) $expected, (array) $e->on_all());
    
    $e->clear('foo');
    $this->assertEquals(array(), (array) $e->on('foo'));
    $this->assertEquals(2, $e->count());
    $e->clear();
    $this->assertEquals(0, $e->count());
    $this->assertEquals(array(), (array) $e->on_all());
  }
}

?>