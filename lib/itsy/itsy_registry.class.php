<?php defined('ITSY_PATH') or die('No direct script access.');
/**
 * @author Luke Antins <luke@lividpenguin.com>
 * @copyright Copyright (c) 2008, Luke Antins
 * @license http://opensource.org/licenses/mit-license.php MIT license
 * @package itsy
 */

/**
 * itsy_registry
 * 
 * Implements a 'registry' to store data.
 * Useful for passing data around.
 * @package itsy
 */
abstract class itsy_registry
{
  /** Stores our registry data */
  private static $data = array();
  
  /**
   * Set Base Path
   * 
   * Sets the base path used when searching paths.
   * Think of it as being a few directories deep in a filesystem.
   * 
   * @param string $path path we wish to make the base path
   * @return bool were we able to change the base path?
   */
  public static function set_base_path($path)
  {
    return true;
  }
  
  /**
   * Get Value
   * 
   * Gets the value of the spesified path.
   * 
   * @param string $path path name to query
   * @return mixed|null null is returned if the path does not exist
   */
  public static function get($path)
  {
    $path = itsy_registry::parse_path($path);
    if (array_key_exists($path, itsy_registry::$data)) {
      return itsy_registry::$data[$path];
    }
    
    return null;
  }
  
  /**
   * Set Value
   * 
   * Sets the value of the spesified path.
   * 
   * @param string $path path name
   * @param mixed $value value you wish to set to the spesified path
   */
  public static function set($path, $value)
  {
    $path = itsy_registry::parse_path($path);
    itsy_registry::$data[$path] = $value;
  }
  
  /**
   * Load Data
   * 
   * Loads data into the registry from an array.
   * The array is simply (path => value).
   * 
   * @param string $data path data to load
   * @return bool true if we managed to load anything
   */
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
  
  /**
   * Delete Path
   * 
   * Deletes a path from the registry.
   * 
   * @param string $path path name to delete
   */
  public static function delete($path)
  {
    $path = itsy_registry::parse_path($path);
    $paths_to_delete = itsy_registry::search($path);
    foreach ($paths_to_delete as $delete_path) {
      unset(itsy_registry::$data[$delete_path]);
    }
  }
  
  /**
   * Search Path
   * 
   * Looks through all the paths in the registry and returns an array of ones
   * that match.
   * 
   * @todo This could be done better
   * @param string $search string to search on
   * @return array paths that match the search criteria
   */
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
  
  /**
   * Parse Path
   * 
   * This simply removes any / from the end of the path.
   * 
   * @param string $path path to parse
   * @return string parsed path
   */
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