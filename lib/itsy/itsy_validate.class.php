<?php defined('ITSY_PATH') or die('No direct script access.');
/**
 * Copyright (c) 2008 Luke Antins <luke@lividpenguin.com>
 * All rights reserved.
 *
 * http://www.lividpenguin.com/
 */

abstract class itsy_validate
{
  // alphabetical characters
  static public function alpha($string)
  {
    
  }
  
  // numbers, negative and decimal numbers allowed
  static public function numeric($number)
  {
    
  }
  
  // alphabetical characters and numbers only
  static public function alpha_numeric($string)
  {
    
  }
  
  // digits only (whole numbers) (no dots or dashes)
  static public function digit($digit)
  {
    
  }
  
  // letters, numbers, whitespace, dashes, periods, and underscores
  static public function text($text)
  {
    
  }
}

?>