<?php

class test_itsy_request_basic extends PHPUnit_Framework_TestCase
{
  public function test_get()
  {
    $get = array('fooget' => 'bar');
    $r = new itsy_request($get);
    $this->assertEquals('bar', $r->get('fooget'));
    $this->assertEquals('', $r->get('notset'));
    $this->assertEquals(false, $r->is_post());
  }
  
  public function test_post()
  {
    $post = array('foopost' => 'bar');
    $r = new itsy_request(array(), $post);
    $this->assertEquals('bar', $r->post('foopost'));
    $this->assertEquals('', $r->post('notset'));
  }
  
  public function test_is_post()
  {
    $post = array('fooispost' => 'bar');
    $r = new itsy_request(array(), $post);
    $this->assertEquals(true, $r->is_post());
  }
}

?>