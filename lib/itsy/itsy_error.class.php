<?php defined('ITSY_PATH') or die('No direct script access.');
/**
 * Copyright (c) 2008 Luke Antins <luke@lividpenguin.com>
 * All rights reserved.
 *
 * http://www.lividpenguin.com/
 */

/**
 * Itsy Error class.
 * Control errors.
 */
class itsy_error
{
  private $messages; // store array of attributes and their error messages.
  
  /**
   * 
   */
  function __construct()
  {
    $this->messages = array();
  }
  
  /**
   * Add an error message for a spesific attribute.
   */
  public function add($attribute, $message)
  {
    if (strlen($attribute) <= 0 || strlen($message) <= 0) {
      return false;
    }
    
    $this->messages[$attribute][] = $message;
    return true;
  }
  
  /**
   * Removes all errors that have been added.
   */
  public function clear()
  {
    $this->message = array();
    return true;
  }
  
  /**
   * Count the number of errors.
   * This includes multiple errors for one attribute.
   */
  public function count()
  {
    $total = 0;
    foreach ($this->messages as $message) {
      $count = count($message, COUNT_RECURSIVE);
      $total += $count;
    }
    return $total;
  }
  
  /**
   * Grab the error(s) for a spesific attribute.
   */
  public function on($attribute)
  {
    if (array_key_exists($attribute, $this->messages)) {
      // we found a key, now lets get the errors.
      return $this->messages[$attribute];
    }
  }
  
  /**
   * Return an array of all errors.
   */
  public function all($messages_only = true)
  {
    // make sure we got some errors to return.
    if ($this->count() < 0) {
      return false;
    }

    // were returning all the error information. for all attributes.
    if ($messages_only == false) {
      return $this->messages;
    } else { // were returning just the error messages.
      
    }
    
    return false;
  }
}

?>