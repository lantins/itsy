<?php defined('ITSY_PATH') or die('No direct script access.');
/**
 * itsy - minimalistic php framework
 * 
 * A small framework centerd around the view/controller.
 * @author Luke Antins <luke@lividpenguin.com>
 * @copyright Copyright (c) 2008, Luke Antins
 * @package itsy
 */

/**
 * itsy - main framework class
 * 
 * Explain the 'core' of itsy more...
 * itsy uses a front-controller to handel all requests.
 * @package itsy
 */
abstract class itsy
{
  public static $log_dev = array();
  public static $db = array();
  
  // main setup method.
  public static function setup()
  {
    itsy::log("<b>CORE - Starting Itsy</b>", 'dev');
    spl_autoload_register(array('itsy', 'autoloader'));
    set_exception_handler(array('itsy', 'itsy_exception_handler'));
    itsy::load_config();
  }
  
  // opposite to setup method.
  public static function shutdown()
  {
    itsy_registry::delete('/');
    spl_autoload_unregister(array('itsy', 'autoloader'));
    restore_exception_handler();
  }
  
  // load the config file that will override any defaults.
  private static function load_config()
  {
    // default config settings.
    itsy_registry::delete('/');
    $defaults = array(
      '/itsy/environment' => 'development',
      '/itsy/controller_path' => 'app/controllers/',
      '/itsy/view_path' => 'app/views/',
      '/itsy/layout_path' => 'app/layouts/',
      );
    itsy_registry::load($defaults);
    
    // load the config file to get the $config var.
    $file = ROOT_PATH . 'etc/config.php';
    if (!file_exists($file) || !is_readable($file)) {
      return;
    }
    
    require_once $file;
  }
  
  public static function partial($controller, $action = '', $param = null)
  {
    itsy::log("<b>CORE - Partial:</b> controller: $controller, action: $action, " . 
              "param: $param", 'dev');
    return itsy::dispatch($controller, $action, $param, $partial = true, $itsy = false);
  }
  
  /**
   * TODO:
   *   If the action starts with an _ it is private and may not be called by
   *   the end user (ie using a browser), it may only be called internally.
   */
  public static function dispatch($controller, $action = '', $param = null, $partial = false, $itsy = false)
  {
    itsy::log("<b>CORE - Dispatching:</b> controller: $controller, action: $action, " . 
              "param: $param, partial: $partial, itsy: $itsy", 'dev');
    
    if ($itsy == true) {
      if ($action[0] == '_') {
        throw new itsy_exception('Access Denied to internal action.');
      }
    }
    
    $controller_name = $controller . '_controller';
      
      // check the controller exists first.
      if (class_exists($controller_name) == false) {
        self::load_controller($controller_name);
      } 
      
      $control = new $controller_name();
      
      $result = null;
      $result = $control->_execute($action, $param); // all the action on the controller.
      if (is_array($result)) {
        // see if we should forward or something.
        switch ($result['directive']) {
          case 'forward': {
            itsy::dispatch($result['controller'], $result['action'], $param);
            return;
            break;
          }
        }
      }
      
      // render the view.
      $control->_render($action, $itsy);
              
      if ($partial) {
        return $control->view_content;
      } elseif ($control->_layout == null) {
        echo $control->view_content;
      } else {
        $control->_render_layout();
      }
      
  }

  /**
   * Load a controller file.
   */
  public static function load_controller($controller)
  {
    itsy::log("<b>CORE - Loading Controller:</b> $controller", 'dev');
    $controller_classname = $controller;
    $controller = str_replace('_controller', '', $controller);
    $file_loc = str_replace('_', '/', $controller);
    $file = ROOT_PATH . itsy_registry::get('/itsy/controller_path') . $file_loc . '.php';
    
    if (file_exists($file)) {
      require_once($file);
    }
    
    if (class_exists($controller_classname) == true) {
      return;
    }
    
    throw new itsy_exception('Could not load controller ' . $controller . ' - please check the file exists and the class is defined.');
  }
  
  /**
   * Class autoloader
   */
  public static function autoloader($class)
  {
    // check the class is not already loaded
    if (class_exists($class, false)) {
        return true;
    }
    
    // check if the class name starts with itsy_
    if ((strrpos($class, 'itsy_')) !== false) {
      $class_file = ITSY_PATH . $class . '.class.php';
      if (is_readable($class_file) && file_exists($class_file)) {
        require_once($class_file);
      }
    }
    
    // look for a class type.
    // foo_bar will become foo/bar.class.php
    if (($class_name = strpos($class, '_')) !== false) {
        // a class suffix is set, this will be our type.
        $lib_dir = substr_replace($class, '', $class_name);
        $class_name = substr($class, $class_name + 1);
        $class_file = ROOT_PATH.'lib/'.$lib_dir.'/'.$class_name . '.class.php';
        if (is_readable($class_file) && file_exists($class_file)) {
          require_once($class_file);
        }
    }
    //itsy::log("<b>CORE - Auto Loader:</b> $class", 'dev');
    
    // TODO: look for class file in the libs dir.
    
    return class_exists($class, false);
  }
  
  public static function log($message, $kind = 'info')
  {
    if (method_exists('itsy_registry', 'get') == false) {
      return;
    }
    
    switch ($kind) {
      case 'dev': {
        if (itsy_registry::get('/itsy/environment') == 'development') {
          array_push(itsy::$log_dev, $message);
        }
        break;
      }
    }
  }
  
  public static function itsy_exception_handler($e)
  {
    $param['message'] = $e->getMessage();
    $param['code'] = $e->getCode();
    $param['trace'] = $e->getTraceAsString();
    
    if (itsy_registry::get('/itsy/environment') == 'development') {
      itsy::dispatch('itsy_error', '_exception', $param);
    } else {
      // if its a db error, show a 503
      if (get_class($e) == 'itsy_db_exception') {
        itsy::dispatch('itsy_error', '_error503');
      } else {
        itsy::dispatch('itsy_error', '_error404');
      }
    }
  }
}

/**
 * Base exception class.
 * @package itsy
 */
class itsy_exception extends Exception
{
  public function __construct($message = null, $code = 0)
  {
    parent::__construct($message, $code);
  }
}

?>