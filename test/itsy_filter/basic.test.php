<?php

class test_itsy_filter_basic extends PHPUnit_Framework_TestCase
{
  public function test_alpha()
  {
    itsy_filter::alpha('abc');
    $this->markTestIncomplete('This test has not been implemented yet.');
  }
  
  public function test_numeric()
  {
    itsy_filter::numeric(123);
    $this->markTestIncomplete('This test has not been implemented yet.');
  }
  
  public function test_alpha_numeric()
  {
    itsy_filter::alpha_numeric('abc123');
    $this->markTestIncomplete('This test has not been implemented yet.');
  }
  
  public function test_digit()
  {
    itsy_filter::digit(0.0);
    $this->markTestIncomplete('This test has not been implemented yet.');
  }
  
  public function test_text()
  {
    itsy_filter::text('foobar');
    $this->markTestIncomplete('This test has not been implemented yet.');
  }

}

?>