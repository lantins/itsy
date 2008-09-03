<?php defined('ITSY_PATH') or die('No direct script access.');
/**
 * Copyright (c) 2008 Luke Antins <luke@lividpenguin.com>
 * All rights reserved.
 *
 * http://www.lividpenguin.com/
 */

/**
 * The flash class provides a way to pass messages between diffrent actions
 * and controllers. This can be useful for displaying error/notice messages.
 * 
 * We have a few namespaces for flash message, you can use any namespace you wish
 * but in the interest of keeping things a little more standard, I suggest starting
 * with the ones below:
 *   - notice
 *   - message
 *   - warning
 *   - all ... this is a special namespace, it will affect all namespaces.
 *     (apart form when your adding a new message).
 */
class itsy_flash
{
  // add a message to a namespace.
  public static function add($namespace = 'message', $message)
  {
    itsy_flash::check_session();
    
    $_SESSION['itsy_flash'][$namespace][] = $message;
    
    return true;
  }
  
  // discard message(s) for a namespace
  public static function discard($namespace = 'all')
  {
    itsy_flash::check_session();
    unset($_SESSION['itsy_flash']);
    itsy_flash::check_session();
  }
  
  // get the messages for a namespace
  public static function get($namespace = 'all')
  {
    itsy_flash::check_session();
    
    $messages = $_SESSION['itsy_flash'];
    itsy_flash::discard();
    
    if ($namespace = 'all') {
      return $messages;
    }
    
    if (array_key_exists($namespace, $messages)) {
      if (is_array($messages[$namespace])) {
        return $messages[$namespace];
      }
    }
    
    // return an empty array if we could not find any messages.
    return array();
  }
  
  // check we have somewhere for our flash data in the session.
  private static function check_session()
  {
    if (isset($_SESSION['itsy_flash']) == false) {
      $_SESSION['itsy_flash'] = array();
    }
    
    if (isset($_SESSION['itsy_flash'])) {
      return true;
    }
    
    return false;
  }
}

?>