<?php

class test_itsy_log extends PHPUnit_Framework_TestCase
{
  
  protected function setUp()
  {
    itsy::$log_dev = array();
  }
  
  public function test_dev_log()
  {
    $this->assertEquals(0, count(itsy::$log_dev), 'count should be 0 before we start.');
  }
  
  public function test_dev_log_no_enviroment()
  {
    itsy_registry::delete('/itsy/environment');
    itsy::log('no environment', 'dev');
    $this->assertEquals(0, count(itsy::$log_dev), 'count should be 0 if no enviroment is set.');
  }
  
  public function test_dev_log_production_enviroment()
  {
    itsy_registry::set('/itsy/environment', 'production');
    itsy::log('production environment', 'dev');
    $this->assertEquals(0, count(itsy::$log_dev), 'count should be 0 in production environment.');
  }
  
  public function test_dev_log_development_enviroment()
  {
    itsy_registry::set('/itsy/environment', 'development');
    itsy::log('development environment 1', 'dev');
    itsy::log('development environment 2', 'dev');
    $this->assertEquals(2, count(itsy::$log_dev), 'count should be 2 in development environment.');
  }
}

?>