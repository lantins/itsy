<?php

class test_itsy_db_transactions extends PHPUnit_Framework_TestCase
{
  public function test_basic()
  {
    $this->markTestIncomplete('This test has not been implemented yet.');
  }
  
  public function test_out_of_order_transaction_calls()
  {
    $config = array(
      'dsn' => 'sqlite::memory:'
      );
    $db = new itsy_db($config);
    
    $this->setExpectedException('itsy_db_exception');
    $db->roll_back();
  }
}

?>