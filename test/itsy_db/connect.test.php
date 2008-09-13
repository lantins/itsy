<?php

class test_itsy_db_connect extends PHPUnit_Framework_TestCase
{
  public function test_missing_config()
  {
    $settings = array();
    $db = new itsy_db($settings);
  }
  
  public function test_invalid_config()
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
  
  public function test_config_via_constructor()
  {
    $settings = array(
      'engine' => 'sqlite',
      'database' => ':memory:'
      );
      
    $db = new itsy_db($settings);
  }
  
  public function test_sqlite_basic()
  {
    $this->markTestIncomplete('This test has not been implemented yet.');
  }
  
  public function test_mysql_memory()
  {
    $this->markTestIncomplete('This test has not been implemented yet.');
  }
  
  public function test_mysql_basic()
  {
    $this->markTestIncomplete('This test has not been implemented yet.');
  }
  
}

?>