<?php defined('ITSY_PATH') or die('No direct script access.');
/**
 * @author Luke Antins <luke@lividpenguin.com>
 * @copyright Copyright (c) 2008, Luke Antins
 * @package itsy
 */

/**
 * itsy_filter - useful filtering
 * 
 * Useful filtering methods that can be used to sanatize user input.
 * @package itsy
 */
abstract class itsy_registry
{
  private static $data = array();
  
  // path to start searching from.
  public static function set_base_path($path)
  {
    return true;
  }
  
  // returns the value of the path.
  public static function get($path)
  {
    $path = itsy_registry::parse_path($path);
    if (array_key_exists($path, itsy_registry::$data)) {
      return itsy_registry::$data[$path];
    }
    
    return null;
  }
  
  // sets the paths value.
  public static function set($path, $value)
  {
    $path = itsy_registry::parse_path($path);
    itsy_registry::$data[$path] = $value;
  }
  
  // load data from an array.
  // this will clear any current data.
  public static function load($data)
  {
    if (is_array($data)) {
      foreach ($data as $path => $value) {
        itsy_registry::set($path, $value);
      }
      return true;
    }
    return false;
  }
  
  // delete the given path.
  public static function delete($path)
  {
    $path = itsy_registry::parse_path($path);
    $paths_to_delete = itsy_registry::search($path);
    foreach ($paths_to_delete as $delete_path) {
      unset(itsy_registry::$data[$delete_path]);
    }
  }
  
  public static function search($search)
  {
    $paths = array_keys(itsy_registry::$data);
    $results = array();
    foreach ($paths as $path) {
      // see if the path matches
      if (strpos($path, $search) !== false) {
        $results[] = $path;
      }
    }
    
    return $results;
  }
  
  private static function parse_path($path)
  {
    if ($path != '/') {
      // strip the ending /
      $path = rtrim($path, "\x2F");
      return $path;
    }
  }
}

?>