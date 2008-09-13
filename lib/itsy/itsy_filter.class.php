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
 * 
 * @todo I say the methods return these spesific types; but do they really?!
 * 
 * @package itsy
 */
class itsy_filter
{
  /**
   * Alphabetical Characters Filter
   * 
   * Strips everything but:
   * - alphabetical characters
   * 
   * @param string $string
   * @return string
   */
  static public function alpha($string)
  {
    return preg_replace("/[^a-z]/i", '', $string);
  }
  
  /**
   * Numeric Filter
   * 
   * Strips everything but:
   * - normal numbers (including negative)
   * - decimal numbers
   * 
   * @param string $number
   * @return int|float
   */
  static public function numeric($number)
  {
    return preg_replace("/[^0-9\-]/i", '', $number);
  }
  
  /**
   * Alpha Numeric Filter
   * 
   * Strips everything but alphabetical characters and numbers.
   * 
   * @param string $string
   * @return string
   */
  static public function alpha_numeric($string)
  {
    return preg_replace("/[^a-z0-9]/i", '', $string);
  }
  
  /**
   * Digit Filter
   * 
   * Strips everything but whole numbers (no dots or dashes).
   * 
   * @param string $digit
   * @return int
   */
  static public function digit($digit)
  {
    return preg_replace("/[^0-9]/i", '', $digit);
  }
  
  /**
   * Text Filter
   * 
   * Strips everything but normal text:
   * - letters
   * - numbers
   * - whitespace
   * - dashes
   * - periods
   * - underscores
   * 
   * @param string $text
   * @return string
   */
   static public function text($text)
  {
    return preg_replace("/[^a-z0-9_\-\ \.]/i", '', $text);
  }
}

?>