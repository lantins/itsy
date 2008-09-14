<?php

class test_itsy_db_connect extends PHPUnit_Framework_TestCase
{
  public function test_empty_config_array()
  {
    $this->setExpectedException('itsy_db_exception');
    
    $settings = array();
    $db = new itsy_db($settings);
  }
  
  public function test_mysql_invalid_auth_details()
  {
    $settings = array(
      'engine' => 'mysql',
      'host' => 'localhost',
      'database' => 'test',
      'user' => 'invalid_username',
      'pass' => 'invalid_password'
      );
      
    try {
      $db = new itsy_db($settings);
    } catch (itsy_db_exception $e) {
      $find = 'SQLSTATE[28000]';
      if (strpos($e->getMessage(), $find) === false) {
        $this->fail("Was expecting to find '$find' in the exception message.");
      }
    }
  }
  
  public function test_invalid_config_name()
  {
    $this->setExpectedException('itsy_db_exception');
    $db = new itsy_db('invalid_config_name');
  }
  
  public function test_valid_config_name()
  {
    itsy_registry::set('/itsy/db/foo/engine', 'sqlite');
    itsy_registry::set('/itsy/db/foo/database', ':memory:');
    $db = new itsy_db('foo');
  }
  
  public function test_sqlite_basic()
  {
    $settings = array(
      'engine' => 'sqlite',
      'database' => 'db/test.sqlite3'
      );
      
    $db = new itsy_db($settings);
  }
  
  public function test_mysql_memory()
  {
    $settings = array(
      'engine' => 'sqlite',
      'database' => ':memory:'
      );
      
    $db = new itsy_db($settings);
  }
  
  public function test_mysql_basic()
  {
    $this->markTestIncomplete('This test has not been implemented yet.');
  }
  
}

?>