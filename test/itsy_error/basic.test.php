<?php

class test_itsy_error_basic extends PHPUnit_Framework_TestCase
{
  public function test_basic()
  {
    $expected['foo'][] = 'foo 1';
    $expected['foo'][] = 'foo 2';
    $expected['bar'][] = 'bar 1';
    $expected['meow'][] = 'meow 1';
    
    $e = new itsy_error();
    
    $e->add('foo', 'foo 1');
    $e->add('foo', 'foo 2');
    $e->add('bar', 'bar 1');
    $e->add('meow', 'meow 1');
    
    $this->assertEquals(2, $e->count('foo'));
    $this->assertEquals(1, $e->count('bar'));
    $this->assertEquals(1, $e->count('meow'));
    $this->assertEquals(4, $e->count());
    
    $this->assertEquals($expected['foo'], $e->on('foo'));
    $this->assertEquals($expected['bar'], $e->on('bar'));
    $this->assertEquals($expected['meow'], $e->on('meow'));
    $this->assertEquals($expected, $e->on_all());
    
    $e->clear('foo');
    $this->assertEquals(array(), $e->on('foo'));
    $this->assertEquals(2, $e->count());
    $e->clear();
    $this->assertEquals(0, $e->count());
    $this->assertEquals(array(), $e->on_all());
  }
}

?>