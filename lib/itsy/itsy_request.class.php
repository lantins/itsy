<?php defined('ITSY_PATH') or die('No direct script access.');
/**
 * @author Luke Antins <luke@lividpenguin.com>
 * @copyright Copyright (c) 2008, Luke Antins
 * @license http://opensource.org/licenses/mit-license.php MIT license
 * @package itsy
 */

/**
 * itsy_request
 * 
 * This class provides access to $_GET, $_POST and $_COOKIE data; safer.
 * No filtering / validation is done; just the handling of slashes.
 * @package itsy
 */
class itsy_request
{
  private static $instance;
  private $_get;
  private $_post;
  private $_cookie;
  
  /**
   * Request Constructor
   * 
   * Looks for global $_GET, $_POST and $_COOKIE; this data will be used to
   * populate the instance variables if present; otherwise the parameters 
   * are used.
   * 
   * If magic quotes is enabled we will strip the slashes.
   */
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
  
  /**
   * Get Instance
   * 
   * Returns and/or creates a instance of itsy_request.
   * An instance can be created directly if you preffer; this is here for
   * conveniance should other itsy libraries require request information.
   */
  public static function get_instance()
  {
    if ((self::$instance instanceof self) == false) { 
      self::$instance = new self;
    }
    
    return self::$instance;
  }
  
  /**
   * Is Post?
   * 
   * Checks if the request was a post or not.
   * 
   * @return bool true if the request was a post.
   */
  public function is_post()
  {
    if ($this->_post == true) {
      return true;
    }
    
    return false;
  }
  
  /**
   * Retrieve Get Parameter Value
   * 
   * Returns the value of the named get parameter. If the parameter is not set
   * we default the value to ''.
   * 
   * @return mixed
   */
  public function get($name)
  {
    if (empty($this->_get[$name])) {
      $result = '';
    } else {
      $result = $this->_get[$name];
    }
    return $result;
  }
  
  /**
   * Retrieve Post Parameter Value
   * 
   * Returns the value of the named post parameter. If the parameter is not set
   * we default the value to ''.
   * 
   * @return mixed
   */
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
 * Add Slashes Recursively
 * 
 * Walks over a multi-dimensional array or just a string and applies
 * addslashes() to the value(s).
 * 
 * @return array
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

/**
 * Strip Slashes Recursively
 * 
 * Walks over a multi-dimensional array or just a string and applies
 * stripslashes() to the value(s).
 * 
 * @return array
 */
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