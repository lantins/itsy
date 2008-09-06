<?php defined('ITSY_PATH') or die('No direct script access.');
/**
 * Copyright (c) 2008 Luke Antins <luke@lividpenguin.com>
 * All rights reserved.
 *
 * http://www.lividpenguin.com/
 */

/**
 * Itsy Filtering class.
 * Filter data.
 */

class itsy_filter
{
  // alphabetical characters
  static public function alpha($string)
  {
    return preg_replace("/[^a-z]/i", '', $text);
  }
  
  // numbers, negative and decimal numbers allowed
  static public function numeric($number)
  {
    return preg_replace("/[^0-9\-]/i", '', $text);
  }
  
  // alphabetical characters and numbers only
  static public function alpha_numeric($string)
  {
    return preg_replace("/[^a-z0-9]/i", '', $text);
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