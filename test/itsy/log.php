<?php

require_once 'PHPUnit/Framework.php';

class test_itsy_log extends PHPUnit_Framework_TestCase
{
  // development message should be added to the static array itsy::$log_dev
  // only when your enviroment is set to development mode.
  public function test_dev_log()
  {
    // returns true if the error count is not 0.
    function error_count()
    {
      return count(itsy::$log_dev);
    }
    
    // just to be sure..
    $this->assertEquals(error_count(), 0, 'count should be 0 before we start.');
    
    // no enviroment set.
    itsy::log('no environment', 'dev');
    $this->assertEquals(error_count(), 0, 'count should be 0 if no enviroment is set.');
    
    // production enviroment set.
    itsy::$config['environment'] = 'production';
    itsy::log('production environment', 'dev');
    $this->assertEquals(error_count(), 0, 'count should be 0.');

    // development enviroment set.
    itsy::$config['environment'] = 'development';
    itsy::log('development environment 1', 'dev');
    itsy::log('development environment 2', 'dev');
    $this->assertEquals(error_count(), 2, 'count should be 2.');
  }
}

?>