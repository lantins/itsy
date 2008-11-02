<?php

class test_itsy_db_get_single_value extends PHPUnit_Framework_TestCase
{
  private $db;
  
  public function setUp()
  {
    $config = array(
      'dsn' => 'sqlite::memory:'
      );
    $db = $this->db = new itsy_db($config);
    
    $db->execute("CREATE TABLE names(id INTEGER PRIMARY KEY AUTOINCREMENT, first VARCHAR(32), last VARCHAR(32))");
    $db->execute("INSERT INTO names VALUES(null, 'luke', 'antins')");
    $db->execute("INSERT INTO names VALUES(null, 'foo', 'bar')");
    $db->execute("INSERT INTO names VALUES(null, 'jo', 'bloggs')");
    $db->execute("INSERT INTO names VALUES(null, 'meow', 'purrr')");
  }
  
  public function test_basic()
  {
    $db = $this->db;
    
    $value = $db->get_single_value("SELECT id FROM names");
    $this->assertEquals(1, $value);
    
    $value = $db->get_single_value("SELECT first FROM names WHERE id = 1");
    $this->assertEquals('luke', $value);
  }
}

?>