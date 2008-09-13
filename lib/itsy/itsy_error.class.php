<?php defined('ITSY_PATH') or die('No direct script access.');
/**
 * @author Luke Antins <luke@lividpenguin.com>
 * @copyright Copyright (c) 2008, Luke Antins
 * @license http://opensource.org/licenses/mit-license.php MIT license
 * @package itsy
 */

/**
 * itsy_error - simple error class
 * 
 * This class allows you to store errors for a spesific 'attribute'.
 * @package itsy
 */
class itsy_error
{
  private $messages; // store array of attributes and their error messages.
  
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
  public function clear($attribute = '')
  {
    if (array_key_exists($attribute, $this->messages) && $attribute != '') {
      $this->messages[$attribute] = array();
      return;
    }
    
    $this->messages = array();
  }
  
  /**
   * Count the number of errors.
   * This includes multiple errors for one attribute.
   */
  public function count($attribute = '')
  {
    if (array_key_exists($attribute, $this->messages) && $attribute != '') {
      return count($this->messages[$attribute]);
    }
    
    $total = 0;
    foreach ($this->messages as $attribute) {
      $total += count($attribute);
    }
    
    return $total;
  }
  
  /**
   * Grab the error(s) for a spesific attribute.
   */
  public function on($attribute = '')
  {
    if (array_key_exists($attribute, $this->messages) && $attribute != '') {
      // we found a key, now lets get the errors.
      return $this->messages[$attribute];
    }
  }
  
  /**
   * Return an array of all errors.
   */
  public function on_all()
  {
    // make sure we got some errors to return.
    if ($this->count() == 0) {
      return array();
    }
    
    return $this->messages;
  }
}

?>