<?php

class test_itsy_autoloader extends PHPUnit_Framework_TestCase
{
  protected function setUp()
  {
    itsy::shutdown();
  }
  
  protected function tearDown()
  {
    itsy::shutdown();
  }
  
  /**
   * @dataProvider provider
   */
  public function test_autoloader($class)
  {
    $this->markTestIncomplete('This test has not been implemented yet.');

    $this->assertFalse(spl_autoload_functions(), 'autoload stack should not be activated.');
    $this->assertFalse(class_exists($class));
    itsy::setup();
    $this->assertTrue(class_exists($class));
    
    $expected_result = array(array('itsy', 'autoloader'));
    $autoloaded_activated = ($expected_result == spl_autoload_functions());
    $this->assertTrue($autoloaded_activated, 'itsy autoloader registerd in the autoload stack.');
  }
  
  public static function provider()
  {
    return array(array('itsy_controller'));
  }
  
  // try to load the class: foo_bar
  // found at lib/foo/bar.class.php
  public function test_autoloader_with_user_class_in_dir()
  {
    $this->markTestIncomplete('This test has not been implemented yet.');
  }
  
  // try to load the class: bar_foo
  // found at lib/bar_foo.class.php
  public function test_autoloader_with_user_class()
  {
    $this->markTestIncomplete('This test has not been implemented yet.');
  }
  
}

?>