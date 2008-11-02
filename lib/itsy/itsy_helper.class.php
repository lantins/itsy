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
    if (!empty($value) && $value != '0000-00-00 00:00:00') {
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
  
  public static function link_to($name = 'Link Name', $action = 'index', $controller = 'default')
  {
    if ($action == 'index') {
      return sprintf('<a href="/?c=%s">%s</a>', $controller, $name);
    }
    else {
      return sprintf('<a href="/?c=%s&amp;a=%s">%s</a>', $controller, $action, $name);
    }
  }
  
  public static function link_to_with_current($name = 'Link Name', $action = 'index', $controller = 'default')
  {
    $request = new itsy_request();
    $request->get_instance();
    
    $c = ($request->get('c') != '') ? $request->get('c') : 'default';
    $a = ($request->get('a') != '') ? $request->get('a') : 'index';
    
    if ($c == $controller && $a == $action) {
      return sprintf('<a href="/?c=%s&amp;a=%s" class="current">%s</a>', $controller, $action, $name);
    }
    
    return itsy_helper::link_to($name, $action, $controller, $options);
  }
  
  public static function input_select($name, $options = NULL, $selected = NULL)
  {
    $html = '<select name="' . $name . '" size="1">';
    if ($options) {
      foreach ($options as $value => $name) {
        if (is_array($name)) {
          // select with option groups.
          $html .= '<optgroup label="' . $value . '">';
          foreach ($name as $value2 => $name2) {
            if ($value2 == $selected) {
              $html .= '<option value="' . $value2 . '" selected="selected">' . $name2. '</option>';
            } else {
              $html .= '<option value="' . $value2 . '">' . $name2. '</option>';
            }
          }
          $html .= '</optgroup>';
        } else {
          // standard select.
          if ($value == $selected) {
            $html .= '<option value="' . $value . '" selected="selected">' . $name. '</option>';
          } else {
            $html .= '<option value="' . $value . '">' . $name. '</option>';
          } 
        }
      }
    }
    $html .= '</select>';

    return $html;
  }
  
}

?>