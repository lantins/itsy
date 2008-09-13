<?php defined('ITSY_PATH') or die('No direct script access.');
/**
 * @author Luke Antins <luke@lividpenguin.com>
 * @copyright Copyright (c) 2008, Luke Antins
 * @license http://opensource.org/licenses/mit-license.php MIT license
 * @package itsy
 */

/**
 * itsy_flash - pass messages between requests/actions.
 * 
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
 * @package itsy
 */
abstract class itsy_flash
{
  /**
   * Add a Message
   * 
   * Adds a message to the spesified namespace.
   * @return bool successes value
   */
  public static function add($namespace = 'message', $message)
  {
    itsy_flash::init_session();
    $_SESSION['itsy_flash'][$namespace][] = $message;
    
    return true;
  }
  
  /**
   * Discard Messages
   * 
   * Discards all messages in the spesified namespace.
   * @return bool successes value
   */
  public static function discard($namespace = 'all')
  {
    $messages = itsy_flash::init_session();
    
    if ($namespace == 'all') {
      unset($_SESSION['itsy_flash']);
    }
    
    if (array_key_exists($namespace, $messages)) {
      if (is_array($messages[$namespace])) {
        unset($messages[$namespace]);
      }
    }
  }
  
  /**
   * Get Messages
   * 
   * Gets all messages in the spesified namespace and automatically discard the
   * messages returned.
   * @return array of messages
   */
  public static function get($namespace = 'all')
  {
    $messages = itsy_flash::init_session();
    
    if ($namespace == 'all') {
      itsy_flash::discard();
      return $messages;
    }
    
    if (array_key_exists($namespace, $messages)) {
      if (is_array($messages[$namespace])) {
        itsy_flash::discard($namespace);
        return $messages[$namespace];
      }
    }
    
    // return an empty array if we could not find any messages.
    return array();
  }
  
  /**
   * Init Session
   * 
   * This method trys to ensure we have somewhere to store our flash data.
   * The current messages are returned.
   * @return array of messages
   */
  private static function init_session()
  {
    if (isset($_SESSION['itsy_flash']) == false) {
      $_SESSION['itsy_flash'] = array();
    }
    
    // perhaps throw an exception if were unable to save session data?
    return $_SESSION['itsy_flash'];
  }
}

?>