<?php defined('ITSY_PATH') or die('No direct script access.');
/**
 * @author Luke Antins <luke@lividpenguin.com>
 * @copyright Copyright (c) 2008, Luke Antins
 * @license http://opensource.org/licenses/mit-license.php MIT license
 * @package itsy
 */

/**
 * itsy_validate - validation class
 * 
 * Helping you validate user input.
 * @package itsy
 */
class itsy_validate
{
  private $attributes;
  private $errors;
  private $current_attribute;
  
  function __construct($attributes)
  {
    $this->attributes = $attributes;
    $this->errors = new itsy_error();
  }
  
  public function __get($key)
  {
    $this->current_attribute = $key;
    return $this;
  }
  
  public function __set($key, $value)
  {
    // do nothing!
    return $this;
  }
  
  // alphabetical characters
  public function alpha()
  {
    $value = $this->get_current_attribute_value();
    if ($value === false) return $this;
    
    $after = itsy_filter::alpha($value);
    
    if ($after != $value) {
      $this->errors->add($this->current_attribute, "must be a alphabetical characters only.");
    }
    
    return $this;
  }
  
  // numbers, negative and decimal numbers allowed
  public function numeric()
  {
    $value = $this->get_current_attribute_value();
    if ($value === false) return $this;
    
    $after = itsy_filter::numeric($value);
    
    if ($after != $value) {
      $this->errors->add($this->current_attribute, "must be a valid numeric number. (negative and decimal numbers allowed)");
    }
    
    return $this;
  }
  
  // alphabetical characters and numbers only
  public function alpha_numeric()
  {
    $value = $this->get_current_attribute_value();
    if ($value === false) return $this;
    
    $after = itsy_filter::alpha_numeric($value);
    
    if ($after != $value) {
      $this->errors->add($this->current_attribute, "must be a alphabetical characters and numbers only.");
    }
    
    return $this;
  }
  
  // digits only (whole numbers) (no dots or dashes)
  public function digit()
  {
    $value = $this->get_current_attribute_value();
    if ($value === false) return $this;
    
    $after = itsy_filter::digit($value);
    
    if ($after != $value) {
      $this->errors->add($this->current_attribute, "must be a valid digit. (whole numbers only, no dots or dashes)");
    }
    
    return $this;
  }
  
  // letters, numbers, whitespace, dashes, periods, and underscores
  public function text()
  {
    $value = $this->get_current_attribute_value();
    if ($value === false) return $this;
    
    $after = itsy_filter::text($value);
    
    if ($after != $value) {
      $this->errors->add($this->current_attribute, "must be standard text. (allowed: letters, numbers, spaces, dashes, periods, underscores)");
    }
    
    return $this;
  }
  
  public function regex($regex, $message = false)
  {
    $value = $this->get_current_attribute_value();
    if ($value === false) return $this;
    
    if (eregi($regex, $value) == false) {
      if ($message == false) {
        $message = 'is not valid.';
      }
      $this->errors->add($this->current_attribute, $message);
    }
    
    return $this;
  }
  
  public function required()
  {
    $value = $this->get_current_attribute_value();
    
    if (strlen($value) == 0) {
      $this->errors->add($this->current_attribute, 'is required and may not be left empty.');
    }
    
    return $this;
  }
  
  public function length($min, $max)
  {
    $value = $this->get_current_attribute_value();
    if ($value === false) return $this;
    
    $length = strlen($value);
    
    if ($length < $min || $length > $max) {
      $this->errors->add($this->current_attribute, "length must be between or equal to $min and $max.");
    }
    
    return $this;
  }
  
  public function is_valid()
  {
    if ($this->errors->count() == 0) {
      return true;
    }
    
    return false;
  }
  
  public function errors_on_all()
  {
    return $this->errors->on_all();
  }
  
  public function errors_on($attribute)
  {
    return $this->errors->on($attribute);
  }
  
  public function errors()
  {
    return $this;
  }
  
  private function get_current_attribute_value()
  {
    if (array_key_exists($this->current_attribute, $this->attributes)) {
      return $this->attributes[$this->current_attribute];
    }
    
    return false;
  }
}

?>