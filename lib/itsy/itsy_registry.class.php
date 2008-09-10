<?php defined('ITSY_PATH') or die('No direct script access.');
/**
 * Copyright (c) 2008 Luke Antins <luke@lividpenguin.com>
 * All rights reserved.
 *
 * http://www.lividpenguin.com/
 */

abstract class itsy_registry
{
  private static $data;
  
  // path to start searching from.
  public static function set_base_path($path)
  {
    
  }
  
  /**
   * Example Paths:
   * /itsy/
   * /itsy/controller_dir
   * /db/
   * /db/my_db/username
   * /db/my_db/password
   */
  
  public static function get($path)
  {
  }
  
  public static function set($path, $value)
  {
  }
}

?>