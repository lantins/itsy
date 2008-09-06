<?php

class example_controller extends itsy_controller
{
  function index()
  {
  }
  
  function form_raw()
  {
    if ($this->_request->is_post() == true) {
      $this->is_it_a_post = 'yes it is';
    }
  }
  
  function form_error()
  {
    // filtering
    $data = array();
    $data['name'] = itsy_filter::text($this->_request->post('name'));
    $data['age'] = itsy_filter::digit($this->_request->post('age'));
    $data['address_line_1'] = itsy_filter::text($this->_request->post('address_line_1'));
    
    $data['name'] = itsy_filter::text($_post('name'));
    $data['age'] = itsy_filter::digit($_post('age'));
    $data['address_line_1'] = itsy_filter::text($_post('address_line_1'));
    
    $data = new itsy_filter($this->_request->post());
    $data->add('name')->text();
    $data->add('name')->text();
    $data->digit(array('age', 'from_somewhere_else'));
    $data->text('address_line_1');
    
    $data = new itsy_filter($this->_request->post());
    $data['name']->text();
    $data['age']->digit('from_somewhere_else');
    $data['address_line_1']->text();
    
    
    echo $data['name'];
    
    // validation
    $test_form = new itsy_validate($data);
    $test_form->name->text();
    $test_form->age->digit();
    $test_form->address_line_1->text();
    
    $test_form = new itsy_validate($data);
    $test_form['name']->text();
    $test_form['age']->digit();
    $test_form['address_line_1']->text();
    $test_form->validate();
    
    if (itsy_validate::text($name))
    
    if ($name = $test_form['name']->text()) {
      
    }
    
    text_input('name', $test_form['name']);
    
    $test_form = new itsy_validate($data);
    $test_form->add('name')
              ->required()
              ->text();
    $test_form->add('age')
              ->digit();
    $test_form->add('address_line_1')
              ->default('1 ellerbeck barns')
              ->text();
    
    $test_form = array();
    $test_form['name'] = itsy_validate::text($data['name']);
    $test_form['age'] = itsy_validate::digit($data['age']);
    $test_form['address_line_1'] = itsy_validate::text($data['address_line_1']);
  }
  
  function flash()
  {
    itsy_flash::add('warning', 'Example warning.');
    itsy_flash::add('notice', 'Example notice.');
    itsy_flash::add('message', 'Example message.');
  }
  
  function filter()
  {
    
    $test1 = "This is some example text, just as an 123 other bit of text!";
    $test2 = "500.00";
    $test3 = "-500.00";
    
    $foo = array();
    $foo['text_test1'] = itsy_filter::text($test1);
    $foo['decimal_test2'] = itsy_filter::decimal($test2);
    $foo['decimal_test3'] = itsy_filter::decimal($test3);
    
    $this->foo = $foo;
  }
}

?>