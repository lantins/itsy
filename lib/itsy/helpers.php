<?php
/**
 * This file contains assorted helper functions; useful in the view files.
 * 
 * @author Luke Antins <luke@lividpenguin.com>
 * @copyright Copyright (c) 2008, Luke Antins
 * @package itsy
 */

/**
 * Builds a html <a href></a> to link to a spesific controller and action.
 * @param string $name name of the link
 * @param string $action action to call; default is 'index'
 * @param string $controller controller to call; default is 'default'
 */
function link_to($name = 'Link Name', $action = 'index', $controller = 'default')
{
  if ($action == 'index') {
    return sprintf('<a href="/?c=%s">%s</a>', $controller, $name);
  }
  else {
    return sprintf('<a href="/?c=%s&amp;a=%s">%s</a>', $controller, $action, $name);
  }
}

function check_is_multiarray($multiarray, $limit = 2)
{
  $count = 0;
  if (is_array($multiarray)) {  // confirms array
    foreach ($multiarray as $array) {  // goes one level deeper
      if (is_array($array)) {  // is subarray an array
        return true;  // return will stop function
      }  // end 2nd check
    }  // end loop
  }  // end 1st check
  return false;  // not a multiarray if this far
}

function helper_input_select($name, $options = NULL, $selected = NULL)
{

  $html = '<select name="' . $name . '" size="1">';
  if ($options) {
    foreach ($options as $value => $name) {
      if (is_array($name)) {
        // select with option groups.
        $html .= '<optgroup label="' . $value . '">';
        foreach ($name as $value2 => $name2) {
          if ($value2 == $selected) {
            $html .= '<option value="' . $value2 . '" selected>' . $name2. '</option>';
          } else {
            $html .= '<option value="' . $value2 . '">' . $name2. '</option>';
          }
        }
        $html .= '</optgroup>';
      } else {
        // standard select.
        if ($value == $selected) {
          $html .= '<option value="' . $value . '" selected>' . $name. '</option>';
        } else {
          $html .= '<option value="' . $value . '">' . $name. '</option>';
        } 
      }
    }
  }
  $html .= '</select>';
  
  return $html;
}

?>