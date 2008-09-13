<?php defined('ITSY_PATH') or die('No direct script access.');
/**
 * @author Luke Antins <luke@lividpenguin.com>
 * @copyright Copyright (c) 2008, Luke Antins
 * @package itsy
 */

/**
 * itsy_request
 * 
 * This class deals with raw $_GET, $_POST and $_COOKIE data.
 * @package itsy
 */
class itsy_request
{
  private static $instance;
  private $_get;
  private $_post;
  private $_cookie;
  
  function __construct($_get = array(), $_post = array(), $_cookie = array())
  {
    $this->_get = ($_GET) ? $_GET : $_get;
    $this->_post = ($_POST) ? $_POST : $_post;
    $this->_cookie = ($_COOKIE) ? $_COOKIE : $_cookie;
    
    // we dont want php fooling around.
    if (get_magic_quotes_gpc()) {
      // Recursively apply stripslashes() to all data
      $this->_get = stripslashes_recursive($this->_get);
      $this->_post = stripslashes_recursive($this->_post);
      $this->_cookie = stripslashes_recursive($this->_cookie);
    }
  }
  
  public static function get_instance() {
    if ((self::$instance instanceof self) == false) { 
      self::$instance = new self;
    }
    
    return self::$instance;
  }
  
  public function is_post()
  {
    if ($this->_post == true) {
      return true;
    }
    
    return false;
  }
  
  public function get($name)
  {
    if (empty($this->_get[$name])) {
      $result = '';
    } else {
      $result = $this->_get[$name];
    }
    return $result;
  }
  
  public function post($name)
  {
    if (empty($this->_post[$name])) {
      $result = '';
    } else {
      $result = $this->_post[$name];
    }
    return $result;
  }
}

/**
 * Request related functions
 */

function addslashes_recursive($value) {
  if (is_array($value)) {
    foreach ($value as $index => $val) {
      $value[$index] = addslashes_recursive($val);
    }
    return $value;
  } else {
    return addslashes($value);
  }
}

function stripslashes_recursive($value) {
  if (is_array($value)) {
    foreach ($value as $index => $val) {
      $value[$index] = stripslashes_recursive($val);
    }
    return $value;
  } else {
    return stripslashes($value);
  }
}

?>