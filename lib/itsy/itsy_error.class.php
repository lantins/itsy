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
   * Add Error Message
   * 
   * Adds an error message to the specified attribute.
   * 
   * @param string $attribute to add the error to
   * @param string $message you wish to add
   * @return bool success value
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
   * Clear Errors From Attribute(s)
   * 
   * Clears all errors from all attribute unless you spesify the specific
   * attribute.
   * 
   * @param string $attribute optionally specify a specific attribute to clear
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
  * Count Error Message
  * 
  * Counts the number of error messages for all attributes by default unless you
  * specify an attribute.
  * 
  * @param string $attribute optionally specify a specific attribute to count
  * @return int number of errors
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
  * Errors For An Attribute
  * 
  * Returns the errors for the specified attribute.
  * 
  * @param string $attribute to get errors for
  * @return array of error messages
  */
  public function on($attribute)
  {
    if (array_key_exists($attribute, $this->messages)) {
      // we found a key, now lets get the errors.
      return $this->messages[$attribute];
    }
    
    return array();
  }
  
  /**
   * Errors For All Attribute
   * 
   * Returns the all errors for attributes.
   * 
   * @return array of error messages
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