<?php

class test_itsy_db_connect extends PHPUnit_Framework_TestCase
{
  public function test_empty_config_array()
  {
    $this->setExpectedException('itsy_db_exception');
    
    $settings = array();
    $db = new itsy_db($settings);
  }
  
  public function test_invalid_config_name()
  {
    $this->setExpectedException('itsy_db_exception');
    $db = new itsy_db('invalid_config_name');
  }
  
  public function test_valid_config_name()
  {
    itsy_registry::set('/itsy/db/foo/dsn', 'sqlite::memory:');
    $db = new itsy_db('foo');
  }
  
  public function test_sqlite_basic()
  {
    $settings = array(
      'dsn' => 'sqlite:' . TEST_ROOT_PATH . 'test/itsy_db/test.sqlite3'
      );
      
    $db = new itsy_db($settings);
  }
  
  public function test_sqlite_memory()
  {
    $settings = array(
      'dsn' => 'sqlite::memory:',
      );
      
    $db = new itsy_db($settings);
  }
  
  public function test_mysql_basic()
  {
    $this->markTestIncomplete('This test has not been implemented yet.');
  }
  
}

?>