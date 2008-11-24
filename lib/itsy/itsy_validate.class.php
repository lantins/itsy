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
    $this->rules = array();
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
   * Add Validation Rule Proxy Method
   * 
   * This magic method will add validation rules to an attribute, if the
   * validation call back exists. Essentially gives a nicer interface.
   * 
   * @param string $name of the static validation call back.
   * @param array $arguments to be passed to the specified method.
   * @return object this object, for method chaining.
   * @throws itsy_exception if the call back method does not exist.
   */
  public function __call($name, $arguments)
  {
    // check if we have a matching static method.
    $callback = "test_$name";
    if (method_exists('itsy_validate', $callback)) {
      $value = $this->get_current_attribute_value();
      if ($value === false) return $this;
      
      $this->add_rule($this->current_attribute, $callback, $arguments);
      
      return $this;
    }
    throw new itsy_exception("Validation method: '$name' could not be found.");
  }
  
  /**
   * Add Validation Rule
   * 
   * Adds a validation rule for a specific attribute
   * 
   * @param string $attribute_name to add the rule to.
   * @param mixed $callback to be passed to the call back method.
   * @param array $arguments to be passed to the specified method.
   * @return object this object, for method chaining.
   * @throws itsy_exception if the call back method does not exist.
   */
  private function add_rule($attribute_name, $callback, $arguments)
  {
    $this->rules[$attribute_name][$callback][] = $arguments;
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
    // call the validation rules for each attribute.
    foreach ($this->rules as $attribute_name => $rules) {
      $this->current_attribute = $attribute_name;
      
      $value = $this->get_current_attribute_value();
      // we don't _always_ need to run the validation rules.
      if (array_key_exists('test_required', $rules) == false && $value == false) {
        continue;
      }
      
      foreach ($rules as $rule => $arguments) {
        //call_user_func("itsy_validate::$rule")
        // lets call each rule!
        array_unshift($arguments, $value);
        $reflector = new ReflectionMethod($this, $rule);
        $reflector->invokeArgs($this, $arguments);
      } 
    }
    
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
   * @return mixed value of current attribute, false if the attribute is not set.
   */
  private function get_current_attribute_value()
  {
    return $this->get_attribute_value($this->current_attribute);
  }
  
  /**
   * Get Specific Attribute Value
   * 
   * Gets the value for a specific attribute.
   * 
   * @return mixed value of specified attribute, false if the attribute is not set.
   */
  private function get_attribute_value($attribute)
  {
    if (array_key_exists($attribute, $this->attributes)) {
      return $this->attributes[$attribute];
    }
    
    return false;
  }
  
  
  
  /**
   * Validate Alphabetical Characters
   * 
   * @param mixed $value to test.
   * @return bool false if we found an error.
   */
  public function test_alpha($value)
  {
    if (is_array($value)) {
      foreach ($value as $index => $val) {
        $this->test_alpha($val);
      }
    } else {
      $after = itsy_filter::alpha($value);
      if ($after != $value) {
        $this->errors->add($this->current_attribute, "must be a alphabetical characters only.");
        return false;
      }
    }
  }
  
  /**
   * Validate Numeric
   * 
   * Numbers, negative and decimal is allowed
   * 
   * @param mixed $value to test.
   * @return bool false if we found an error.
   */
  public function test_numeric($value)
  {
    if (is_array($value)) {
      foreach ($value as $index => $val) {
        $this->test_numeric($val);
      }
    } else {
      $after = itsy_filter::numeric($value);
      if ($after != $value) {
        $this->errors->add($this->current_attribute, "must be a valid numeric number. (negative and decimal numbers allowed)");
        return false;
      }
    }
  }
  
  /**
   * Validate Digit
   * 
   * Digits only (whole numbers) (no dots or dashes)
   * 
   * @param mixed $value to test.
   * @return bool false if we found an error.
   */
  public function test_digit($value)
  {
    if (is_array($value)) {
      foreach ($value as $index => $val) {
        $this->test_digit($val);
      }
    } else {
      $after = itsy_filter::digit($value);
      if ($after != $value) {
        $this->errors->add($this->current_attribute, "must be a valid digit. (whole numbers only, no dots or dashes)");
        return false;
      }
    }
  }
  
  /**
   * Validate Alphabetical Characters and Numbers
   * 
   * @param mixed $value to test.
   * @return bool false if we found an error.
   */
  public function test_alpha_numeric($value)
  {
    if (is_array($value)) {
      foreach ($value as $index => $val) {
        $this->test_alpha_numeric($val);
      }
    } else {
      $after = itsy_filter::alpha_numeric($value);
      if ($after != $value) {
        $this->errors->add($this->current_attribute, "must be a alphabetical characters and numbers only.");
        return false;
      }
    }
  }
  
  /**
   * Validate Required
   * 
   * Makes an attribute required.
   * 
   * @param mixed $value to test.
   * @return bool false if we found an error.
   */
  public function test_required($value)
  {
    if ($value == false) {
      $this->errors->add($this->current_attribute, 'is required and may not be left empty.');
    }
  }
  
  /**
   * Validate Length
   * 
   * Validates using a minimum and maximum length.
   * 
   * @param mixed $value to test.
   * @param array $arguments (min length, max length)
   * @return bool false if we found an error.
   */
  public function test_length($value, $arguments)
  {
    list($min, $max) = $arguments;
    
    if (is_array($value)) {
      foreach ($value as $index => $val) {
        $this->test_length($val, $arguments);
      }
    } else {
      $length = strlen($value);
      if ($length < $min || $length > $max) {
        $this->errors->add($this->current_attribute, "length must be between or equal to $min and $max.");
        return false;
      }
    }
  }
  
  /**
   * Validate Text
   * 
   * Standard text validation, letters, numbers, whitespace, dashes, periods
   * and underscores
   * 
   * @param mixed $value to test.
   * @return bool false if we found an error.
   */
  public function test_text($value)
  {
    if (is_array($value)) {
      foreach ($value as $index => $val) {
        $this->test_text($val);
      }
    } else {
      $after = itsy_filter::text($value);
      if ($after != $value) {
        $this->errors->add($this->current_attribute, "must be standard text. (allowed: letters, numbers, spaces, dashes, periods, underscores)");
        return false;
      }
    }
  }
  
  /**
   * Validate Against Regular Expression
   * 
   * @param mixed $value to test.
   * @param array $arguments (regex, (optional) custom error message)
   * @return bool false if we found an error.
   */
  public function test_regex($value, $arguments)
  {
    $regex = $arguments[0];
    $message = (array_key_exists(1, $arguments)) ? $arguments[1] : false;
    
    if (is_array($value)) {
      foreach ($value as $index => $val) {
        $this->test_regex($val, $arguments);
      }
    } else {
      if (eregi($regex, $value) == false) {
        if ($message == false) {
          $message = 'is not valid.';
        }
        $this->errors->add($this->current_attribute, $message);
        return false;
      }
    }
  }
  
  /**
   * Validate Custom Rule
   * 
   * Validates using the supplied call back with an optional custom error message.
   * 
   * @param mixed $value to test.
   * @param array $arguments (call back, (optional) custom error message)
   * @return bool false if we found an error.
   */
  public function test_custom($value, $arguments)
  {
    $callback = $arguments[0];
    $message = (array_key_exists(1, $arguments)) ? $arguments[1] : false;
    
    if (is_array($value)) {
      foreach ($value as $index => $val) {
        $this->test_custom($val, $arguments);
      }
    } else {
      $result = call_user_func($callback, $value);
      if ($result == false) {
        if ($message == false) {
          $this->errors->add($this->current_attribute, 'is not valid.');
        } elseif(is_string($message)) {
          $this->errors->add($this->current_attribute, $message);
        }
        return false;
      }
    }
  }
}

?>