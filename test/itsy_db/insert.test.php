<?php

class test_itsy_db_insert extends PHPUnit_Framework_TestCase
{
  private $db;
  
  public function setUp()
  {
    $config = array('dsn' => 'sqlite::memory:');
    $db = $this->db = new itsy_db($config);
    
    $sql = <<<EOL
CREATE TABLE names(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  first VARCHAR(32),
  last VARCHAR(32),
  added DATE
  )
EOL;
    
    $db->execute($sql);
  }
  
  public function test_basic()
  {
    $db = $this->db;
    
    $record = array(
      'first' => 'foo',
      'last' => 'bar'
      );
      
    $db->insert('names', $record);
    /*
    $row = $db->insert("SELECT * FROM names WHERE id = 1");
    $this->assertEquals(1, $row->id);
    $this->assertEquals('luke', $row->first);
    $this->assertEquals('antins', $row->last);
    */
  }
}

?>