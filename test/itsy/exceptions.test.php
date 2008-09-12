<?php

class test_itsy_exceptions extends PHPUnit_Framework_TestCase
{
  public function test_exception_basic()
  {
    $this->setExpectedException('itsy_exception');
    itsy::load_controller('this_will_fail_to_load');
  }
  
  public function test_exception_real()
  {
    $original_app_path = itsy_registry::get('/itsy/app_path');
    itsy_registry::set('/itsy/app_path', '/dev/null');

    try {
      throw new itsy_exception('Test exception.');
    } catch (itsy_exception $e) {
      try {
        itsy::itsy_exception_handler($e);
      } catch (itsy_exception $e) {
        $this->assertEquals('The view "_exception" was not found', $e->getMessage());
      }
      
      // and in production mode...
      try {
        itsy_registry::set('/itsy/environment', 'production');
        itsy::itsy_exception_handler($e);
      } catch (itsy_exception $e) {
        $this->assertEquals('The view "_error404" was not found', $e->getMessage());
      }
      
      // and something that will give a 503
      try {
        throw new itsy_db_exception('Test db exception.');
      } catch (itsy_db_exception $e) {
        try {
          itsy_registry::set('/itsy/environment', 'production');
          itsy::itsy_exception_handler($e);
        } catch (itsy_exception $e) {
          $this->assertEquals('The view "_error503" was not found', $e->getMessage());
        }
      }
      
      itsy_registry::set('/itsy/app_path', $original_app_path);
      return;
    }
    $this->fail('An expected exception has not been raised.');
  }
  
  
}

?>