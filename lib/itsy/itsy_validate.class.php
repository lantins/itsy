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
  function __construct()
  {
    $this->errors = new itsy_error();
  }
  
  // alphabetical characters
  public function alpha($string)
  {
    return $this;
  }
  
  // numbers, negative and decimal numbers allowed
  public function numeric($number)
  {
    return $this;
  }
  
  // alphabetical characters and numbers only
  public function alpha_numeric($string)
  {
    return $this;
  }
  
  // digits only (whole numbers) (no dots or dashes)
  public function digit($digit)
  {
    return $this;
  }
  
  // letters, numbers, whitespace, dashes, periods, and underscores
  public function text($text)
  {
    return $this;
  }
  
  public function required()
  {
    return $this;
  }
  
  public function regex($regex)
  {
    // eregi
    return $this;
  }
}

?>