<?php

class itsy_error_controller extends itsy_controller
{
  function __construct()
  {
    parent::__construct();
  }
  
  function _error404()
  {
    $this->_layout = 'itsy/error';
    $this->title = '404 - Not Found';
  }
  
  function _error503()
  {
    $this->_layout = 'itsy/error';
    $this->title = '503 - Service Unavailable';
  }
  
  function _exception($message, $code, $trace)
  {
    $this->message = $message;
    $this->code = $code;
    $this->trace = $trace;
    
    $this->_layout = 'itsy/error_dev';
    $this->title = 'Exception';
  }
}

?>