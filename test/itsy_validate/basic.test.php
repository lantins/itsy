<?php

class test_itsy_validate_basic extends PHPUnit_Framework_TestCase
{
  public function test_alpha()
  {
    itsy_validate::alpha('abc');
    $this->markTestIncomplete('This test has not been implemented yet.');
  }
  
  public function test_numeric()
  {
    itsy_validate::numeric(123);
    $this->markTestIncomplete('This test has not been implemented yet.');
  }
  
  public function test_alpha_numeric()
  {
    itsy_validate::alpha_numeric('abc123');
    $this->markTestIncomplete('This test has not been implemented yet.');
  }
  
  public function test_digit()
  {
    itsy_validate::digit(0.0);
    $this->markTestIncomplete('This test has not been implemented yet.');
  }
  
  public function test_standard_text()
  {
    itsy_validate::standard_text('foobar');
    $this->markTestIncomplete('This test has not been implemented yet.');
  }

}

?>