<?php

class test_itsy_validate_basic extends PHPUnit_Framework_TestCase
{
  public function test_basic()
  {
    
    // set our attributes and values
    $input_array = array(
      'name' => 'luke',
      'age' => 23,
      'occupation' => 'developer'
      );
    
    $input_object = (object) $input_array;
    
    $input = new itsy_validate($input_object);
    $input->name->required('may not be blank')
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