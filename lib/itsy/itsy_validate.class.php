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
  private $rules;
  private $errors;
  private $current_attribute;
  
  /**
   * Validate Constructor
   * 
   * Accepts an (key => value) array of attributes.
   * This could be GET or POST data.
   */
  function __construct($attributes)
  {
    $this->attributes = $attributes;
    $this->errors = new itsy_error();
  }
  
  /**
   * Set Current Attribute
   * 
   * This magic method sets the current attribute were about to add rules to.
   * The attribute may or may not have been provided to the constructor.
   * 
   * @return object this object, for method chaining.
   */
  public function __get($key)
  {
    $this->current_attribute = $key;
    return $this;
  }
  
  // TODO: Do we need this? May be useful to update the data of an attribute.
  public function __set($key, $value)
  {
    // do nothing!
    return $this;
  }
  
  /**
   * Is Valid?
   * 
   * Applies the validation rules.
   * 
   * @return bool true if the data is classed as valid.
   */
  public function is_valid()
  {
    if ($this->errors->count() == 0) {
      return true;
    }
    
    return false;
  }
  
  /**
   * Errors On All
   * 
   * Get's the error messages for all attributes.
   * 
   * @return mixed object of error messages, false if we have no errors.
   */
  public function errors_on_all()
  {
    return $this->errors->on_all();
  }
  
  /**
   * Errors On
   * 
   * Get's the error messages for the specified attributes.
   * 
   * @param string $attribute to get errors for.
   * @return mixed object of error messages, false if we have no errors.
   */
  public function errors_on($attribute)
  {
    return $this->errors->on($attribute);
  }
  
  /**
   * Get Current Attribute Value
   * 
   * Gets the value for the 'current attribute'.
   * 
   * @return mixed value of specified attribute, false if the current attribute is not set.
   */
  private function get_current_attribute_value()
  {
    if (array_key_exists($this->current_attribute, $this->attributes)) {
      return $this->attributes[$this->current_attribute];
    }
    
    return false;
  }
  
  /**
   * ----------------------------------------------------------------------
   * VALIDATION RULES
   * The below methods are used to add validation rules to an attribute.
   * ----------------------------------------------------------------------
   */
  
  /**
   * Validate Alphabetical Characters
   * 
   * @return object this object, for method chaining.
   */
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
  
  /**
   * Validate Numeric
   * 
   * Numbers, negative and decimal is allowed
   * 
   * @return object this object, for method chaining.
   */
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
  
  /**
   * Validate Alphabetical Characters and Numbers
   * 
   * @return object this object, for method chaining.
   */
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
  
  /**
   * Validate Digit
   * 
   * Digits only (whole numbers) (no dots or dashes)
   * 
   * @return object this object, for method chaining.
   */
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
  
  /**
   * Validate Text
   * 
   * Standard text validation, letters, numbers, whitespace, dashes, periods
   * and underscores
   * 
   * @return object this object, for method chaining.
   */
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
  
  /**
   * Validate Against Regular Expression
   * 
   * @return object this object, for method chaining.
   */
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
  
  /**
   * Validate Required
   * 
   * Makes an attribute required.
   * 
   * @return object this object, for method chaining.
   */
  public function required()
  {
    $value = $this->get_current_attribute_value();
    
    if (strlen($value) == 0) {
      $this->errors->add($this->current_attribute, 'is required and may not be left empty.');
    }
    
    return $this;
  }
  
  /**
   * Validate Length
   * 
   * Makes an attribute required.
   * 
   * @param string $min length to allow
   * @param string $max length to allow.
   * @return object this object, for method chaining.
   */
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
}

?>