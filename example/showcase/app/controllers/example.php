<?php

class example_controller extends itsy_controller
{
  function index()
  {
  }
  
  function form_raw()
  {
    $this->is_it_a_post = 'no, not yet';
    
    if ($this->_request->is_post() == true) {
      $this->is_it_a_post = 'yes it is';
    }
  }
  
  function form_error()
  {
  }
  
  function flash()
  {
    itsy_flash::add('warning', 'Example warning.');
    itsy_flash::add('notice', 'Example notice.');
    itsy_flash::add('message', 'Example message.');
  }
  
  function filter()
  {
    
  }
}

?>