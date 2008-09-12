<?php

class test_itsy_registry_basic extends PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    $init = array(
      '/db/test_db/user' => 'testuser',
      '/db/test_db/path' => 'pAsSwOrD',
      '/db/test_db/host' => 'localhost',
      '/itsy/controller_path' => 'app/controllers/',
      '/itsy/view_path' => 'app/controllers/',
      '/itsy/layout_path' => 'app/controllers/',
      );
    itsy_registry::delete('/');
    itsy_registry::load($init);
  }
  
  protected function tearDown()
  {
    itsy_registry::delete('/');
  }
  
  public function test_basic()
  {
    $this->assertEquals('localhost', itsy_registry::get('/db/test_db/host'));
    
    itsy_registry::set('/basic/test/foo', 'foo');
    itsy_registry::set('/basic/test/bar/', 'bar');
    itsy_registry::set('/basic/test/bar/cats_say', 'meow');
    $this->assertEquals('foo', itsy_registry::get('/basic/test/foo'));
    $this->assertEquals('bar', itsy_registry::get('/basic/test/bar/'));
    $this->assertEquals('meow', itsy_registry::get('/basic/test/bar/cats_say'));
    
    itsy_registry::delete('/basic/test/bar/cats_say');
    $this->assertEquals(null, itsy_registry::get('/basic/test/bar/cats_say'));
    
    echo "end of cats say; meow.\n";
    
    itsy_registry::delete('/basic/test/');
    $this->assertEquals(null, itsy_registry::get('/basic/test/foo'));
    $this->assertEquals(null, itsy_registry::get('/basic/test/bar'));
    
    $this->assertEquals('localhost', itsy_registry::get('/db/test_db/host'));
  }
  
  public function test_base_path()
  {
    $this->markTestIncomplete('This test and/or feature has not been implemented yet.');
  }
  
  public function test_invalid_load_data()
  {
    // invalid load data.
    $init = 'foo';
    itsy_registry::load($init);
    
    $this->assertEquals('localhost', itsy_registry::get('/db/test_db/host'));
  }
}

?>