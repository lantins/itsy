<?php

class test_itsy_db_execute extends PHPUnit_Framework_TestCase
{
  public function test_basic()
  {
    $config = array(
      'dsn' => 'sqlite::memory:'
      );
    $db = new itsy_db($config);
    
    $result = $db->execute("CREATE TABLE names(id INTEGER PRIMARY KEY AUTOINCREMENT, first VARCHAR(32), last VARCHAR(32))");
    $this->assertEquals(1, $db->execute("INSERT INTO names VALUES(null, 'luke', 'antins')"));
    $this->assertEquals(1, $db->execute("INSERT INTO names VALUES(null, 'foo', 'bar')"));
    $this->assertEquals(1, $db->execute("INSERT INTO names VALUES(null, 'foo', 'baaaar')"));
    $this->assertEquals(1, $db->execute("INSERT INTO names VALUES(null, 'jo', 'bloggs')"));
    $this->assertEquals(1, $db->execute("INSERT INTO names VALUES(null, 'meow', 'purrr')"));
    
    $this->assertEquals(2, $db->execute("DELETE FROM names WHERE first == 'foo'"));
    
    // this type of sql statment will yeild an unbufferd results set.
    // so we can't know the number of rows deleted.
    $this->assertEquals(0, $db->execute("DELETE FROM names"));
  }
  
  public function test_exception()
  {
    $config = array(
      'dsn' => 'sqlite::memory:'
      );
    $db = new itsy_db($config);
    
    $this->setExpectedException('itsy_db_exception');
    $db->execute("INSERT INTO foo_bar VALUES('this', 'will', 'fail')");
  }
}

?>