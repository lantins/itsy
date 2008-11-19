<?php

class test_itsy_validate_basic extends PHPUnit_Framework_TestCase
{
  public function test_basic()
  {
    
    // set our attributes and values
    $input = array(
      'name' => 'luke',
      'age' => 23,
      'occupation' => 'developer',
      'no_rules' => 'no rules applied to this attribute',
      'not_required' => '',
      'error_1' => '',
      'error_2' => 'should be a digit!'
      );
    
    // should be able to pass an array OR object?
    $validate = new itsy_validate($input);
    $validate->name->required()
                   ->length(3, 16)
                   ->text();
                
    $validate->age->required()
                  ->length(1, 3)
                  ->digit();
                
    $validate->occupation->regex('^[a-z0-9_\ \.-]{3,16}$');
    
    $this->assertEquals(true, $validate->is_valid());
    
    $validate->not_required->length(6, 12)
                           ->text()
                           ->regex('^[a-z]{8,16}$');
                         
    $validate->error_1->required()
                      ->length(6, 12)
                      ->digit();
                      
    $validate->error_2->required()
                      ->length(6, 12)
                      ->digit();
                      
    $validate->error_3->required();
    
    $this->assertEquals(false, $validate->is_valid());
    $this->assertEquals(false, $validate->errors_on('name'));
    $this->assertEquals(false, $validate->errors_on('not_required'));
    $this->assertEquals(2, count((array) $validate->errors_on('error_1')));
    $this->assertEquals(2, count((array) $validate->errors_on('error_2')));
    $this->assertEquals(1, count((array) $validate->errors_on('error_3')));
    
    #$foo = $validate->errors_on_all(); // returns an object of attributes and their error messages.
    #$validate->name->errors(); // returns an object of error messages for the spesific attribute.
  }
}

?>