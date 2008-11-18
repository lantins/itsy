<?php

class test_itsy_validate_basic extends PHPUnit_Framework_TestCase
{
  public function test_basic()
  {
    
    // set our attributes and values
    $input_array = array(
      'name' => 'luke',
      'age' => 23,
      'occupation' => 'developer',
      'no_rules' => 'no rules applied to this attribute'
      );
    
    // should be able to pass an array OR object.
    $input = new itsy_validate($input_array);
    $input->name->required()
                ->length(3, 16)
                ->text();
                
    $input->age->required()
                ->length(1, 3)
                ->digit();
                
    $input->occupation->length(0, 32)
                      ->regex('^[a-z0-9_\ \.-]{3,16}$');
    
    $input->is_valid(); // returns true/false
    $input->errors_on_all(); // returns an object of attributes and their error messages.
    $input->errors_on('name'); // returns an object of error messages for the spesific attribute.
    
    $input->name->errors(); // returns an object of error messages for the spesific attribute.
  }
}

?>