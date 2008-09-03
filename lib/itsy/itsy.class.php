<?php defined('ITSY_PATH') or die('No direct script access.');
/**
 * Copyright (c) 2008 Luke Antins <luke@lividpenguin.com>
 * All rights reserved.
 *
 * http://www.lividpenguin.com/
 */

abstract class itsy
{
    public static $config = array();
    public static $log_dev = array();
    public static $db = array();

    // constructor
    function __construct()
    {
        # code...
    }

    // main setup method.
    public static function setup()
    {
      itsy::log("<b>CORE - Starting Itsy</b>", 'dev');
      
      $default_config = array(
        'environment' => 'production'
      );
      
        // load the config file to get the $config var.
        require_once ROOT_PATH . 'etc/config.php';
        
        itsy::$config = array_merge($default_config, $config);
        

        spl_autoload_register(array('itsy', 'autoloader'));
        set_exception_handler('itsy_exception_handler');
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
            
            default: {
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
      $file = ROOT_PATH . itsy::$config['controller_dir'] . $file_loc . '.php';
      
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
      itsy::log("<b>CORE - Auto Loader:</b> $class", 'dev');
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
        if (($class_name = strpos($class, '_')) !== false) {
            // a class suffix is set, this will be our type.
            $lib_dir = substr_replace($class, '', $class_name);
            $class_name = substr($class, $class_name + 1);
            $class_file = ROOT_PATH.'lib/'.$lib_dir.'/'.$class_name . '.class.php';
            if (is_readable($class_file) && file_exists($class_file)) {
              require_once($class_file);
            }
        }

        // look for class file in the libs dir.

        return class_exists($class, false);
    }
    
    public static function log($message, $kind = 'info')
    {
      switch ($kind) {
        case 'dev': {
          if (empty(itsy::$config['environment']) || itsy::$config['environment'] == 'development') {
            array_push(itsy::$log_dev, $message);
          }
          break;
        }
        
        default: {
          # code...
          break;
        }
      }
    }
}

/**
 * Itsy's own exception!
 */
class itsy_exception extends Exception
{
  
}

function itsy_exception_handler($e)
{
  $param['message'] = $e->getMessage();
  $param['code'] = $e->getCode();
  $param['trace'] = $e->getTraceAsString();
  
  if (itsy::$config['environment'] == 'development') {
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

?>