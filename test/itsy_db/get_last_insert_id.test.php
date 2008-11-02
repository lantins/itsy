<?php

class test_itsy_db_get_last_insert_id extends PHPUnit_Framework_TestCase
{
  public function test_basic()
  {
    $config = array(
      'dsn' => 'sqlite::memory:'
      );
    $db = new itsy_db($config);
    
    $result = $db->execute("CREATE TABLE names(id INTEGER PRIMARY KEY AUTOINCREMENT, first VARCHAR(32), last VARCHAR(32))");
    
    $db->execute("INSERT INTO names VALUES(null, 'luke', 'antins')");
    $this->assertEquals(1, $db->get_last_insert_id());
    
    $db->execute("INSERT INTO names VALUES(null, 'foo', 'bar')");
    $this->assertEquals(2, $db->get_last_insert_id());
    
    $db->execute("INSERT INTO names VALUES(null, 'foo', 'baaaar')");
    $this->assertEquals(3, $db->get_last_insert_id());
    
    $db->execute("INSERT INTO names VALUES(null, 'jo', 'bloggs')");
    $this->assertEquals(4, $db->get_last_insert_id());
    
    $db->execute("INSERT INTO names VALUES(null, 'meow', 'purrr')");
    $this->assertEquals(5, $db->get_last_insert_id());
  }
  
  public function test_invalid_call()
  {
    $config = array(
      'dsn' => 'sqlite::memory:'
      );
    $db = new itsy_db($config);
    
    $result = $db->execute("CREATE TABLE names(id INTEGER PRIMARY KEY AUTOINCREMENT, first VARCHAR(32), last VARCHAR(32))");
    
    $this->assertEquals(0, $db->get_last_insert_id());
  }
}

?>