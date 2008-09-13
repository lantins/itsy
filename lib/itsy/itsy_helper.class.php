<?php defined('ITSY_PATH') or die('No direct script access.');
/**
 * @author Luke Antins <luke@lividpenguin.com>
 * @copyright Copyright (c) 2008, Luke Antins
 * @license http://opensource.org/licenses/mit-license.php MIT license
 * @package itsy
 */

/**
 * itsy_helper - assorted helpful functions.
 * @package itsy
 */
class itsy_helper
{
  /**
   * Default Value
   * 
   * Checks if $value is set; if it is return that value. If not return the
   * default value. A string of '' is considered not set.
   * 
   * @param mixed $default to use if valid is empty.
   * @param mixed $value you wish to check first.
   * @return mixed
   */
  public static function default_to($default, $value)
  {
    $default = (!empty($default)) ? $default : '';
    if (!empty($value)) {
      return $value;
    }
    return $default;
  }
  
  /**
   * Cycle Between Two Options
   * 
   * The return of this method is a 'cycle' between the two options.
   * cycle(0, 1) == 0
   * cycle(0, 1) == 1
   * cycle(0, 1) == 0
   * cycle(0, 1) == 1
   * 
   * @param mixed $first option
   * @param mixed $second option
   * @return mixed
   */
  public static function cycle($first = '0', $second = '1')
  {
    static $count;
    
    $count = ($count <= 0) ? 0 : $count; // set the count to 0 first time round.
    $value = (($count % 2) == 0) ? $first : $second;
    $count++;
    
    return $value;
  }
  
}

?>