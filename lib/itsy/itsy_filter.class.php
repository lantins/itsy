<?php defined('ITSY_PATH') or die('No direct script access.');
/**
 * @author Luke Antins <luke@lividpenguin.com>
 * @copyright Copyright (c) 2008, Luke Antins
 * @license http://opensource.org/licenses/mit-license.php MIT license
 * @package itsy
 */

/**
 * itsy_filter - useful filtering
 * 
 * Useful filtering methods that can be used to sanatize user input.
 * @package itsy
 */
class itsy_filter
{
  // alphabetical characters
  static public function alpha($string)
  {
    return preg_replace("/[^a-z]/i", '', $string);
  }
  
  // numbers, negative and decimal numbers allowed
  static public function numeric($number)
  {
    return preg_replace("/[^0-9\-]/i", '', $number);
  }
  
  // alphabetical characters and numbers only
  static public function alpha_numeric($string)
  {
    return preg_replace("/[^a-z0-9]/i", '', $string);
  }
  
  // digits only (whole numbers) (no dots or dashes)
  static public function digit($digit)
  {
    return preg_replace("/[^0-9]/i", '', $digit);
  }
  
  // letters, numbers, whitespace, dashes, periods, and underscores
  static public function text($text)
  {
    return preg_replace("/[^a-z0-9_\-\ \.]/i", '', $text);
  }
}

?>