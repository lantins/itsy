<?php

class test_itsy_validate_basic extends PHPUnit_Framework_TestCase
{
  public function test_basic()
  {
    
    // set our attributes and values.
    // this could be GET or POST data.
    $input = array(
      'name' => array('first' => 'luke', 'last' => 'antins'),
      'age' => 23,
      'occupation' => 'developer',
      'hobbies_ok' => array('climbing', 'coding', 'meowing'),
      'hobbies_fail' => array('toooooooooooooooolooooooonnnnnnngggggggggg', 'hissing'),
      'no_rules' => 'no rules applied to this attribute',
      'not_required' => '',
      'foo1' => 'meow',
      'foo2' => 'bar',
      'error_1' => '',
      'error_2' => 'should be a digit!'
      );
    
    // TODO: should be able to pass an array OR object?
    // Lets set some validation rules.
    $validate = new itsy_validate($input);
    
    $validate->name->required()
                   ->length(3, 16)
                   ->text();
    
    $validate->age->required()
                  ->length(1, 3)
                  ->digit();
    
    $validate->occupation->regex('^[a-z0-9_\ \.-]{3,16}$');
    
    $validate->hobbies_ok->required()
                         ->text();
    
    // so far everything _should_ be valid.
    $this->assertEquals(true, $validate->is_valid());
    
    $validate->hobbies_fail->required()
                           ->length(6, 12)
                           ->text();
    
    // this is not required; do not apply rules if no input is given.
    $validate->not_required->length(6, 12)
                           ->text()
                           ->regex('^[a-z]{8,16}$');
    
    // custom validation function.
    function does_foo_equal_bar($value)
    {
      if ($value == 'bar') {
        return true;
      }
      
      return false;
    }
    
    $validate->foo1->length(1, 8)
                   ->custom('does_foo_equal_bar', 'foo should be bar!');
    
    $validate->foo2->length(1, 8)
                   ->custom('does_foo_equal_bar', 'foo should be bar!');
    
    $validate->error_1->required()
                      ->length(6, 12)
                      ->digit();
    
    $validate->error_2->required()
                      ->length(6, 12)
                      ->digit();
    
    $validate->error_3->required();
    
    $this->assertEquals(false, $validate->is_valid());
    $this->assertEquals(false, $validate->errors_on('name'));
    $this->assertEquals(false, $validate->errors_on('age'));
    $this->assertEquals(false, $validate->errors_on('occupation'));
    $this->assertEquals(false, $validate->errors_on('hobbies_ok'));
    $this->assertEquals(false, $validate->errors_on('not_required'));
    $this->assertEquals(1, count((array) $validate->errors_on('hobbies_fail')));
    $this->assertEquals(1, count((array) $validate->errors_on('foo1')));
    $this->assertEquals(1, count((array) $validate->errors_on('foo2')));
    $this->assertEquals(2, count((array) $validate->errors_on('error_1')));
    $this->assertEquals(2, count((array) $validate->errors_on('error_2')));
    $this->assertEquals(1, count((array) $validate->errors_on('error_3')));
    #$foo = $validate->errors_on_all(); // returns an object of attributes and their error messages.
  }
}

?>