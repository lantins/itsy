<?php defined('ITSY_PATH') or die('No direct script access.');
/**
 * itsy - minimalistic php framework.
 * 
 * A small framework centerd around the view/controller.
 * @author Luke Antins <luke@lividpenguin.com>
 * @copyright Copyright (c) 2008, Luke Antins
 * @license http://opensource.org/licenses/mit-license.php MIT license
 * @version 1.0
 * @package itsy
 */

/**
 * itsy - core framework class
 * 
 * This class (along with {@link itsy_controller}) provid the core 
 * functionality of the itsy framework:
 * - autoloader
 * - dispatching requests to controllers and actions.
 * - calling partials
 * 
 * @version 1.0
 * @package itsy
 */
abstract class itsy
{
  /** development log information */
  public static $log_dev = array();
  public static $db = array();
  
  /**
   * Get itsy ready.
   * Setup the autoloader and exception handler; load the config file.
   */
  public static function setup()
  {
    itsy::log("<b>CORE - Starting Itsy</b>", 'dev');
    spl_autoload_register(array('itsy', 'autoloader'));
    set_exception_handler(array('itsy', 'itsy_exception_handler'));
    itsy::load_config();
  }
  
  /**
   * Shutdown itsy.
   * Does the opposite of the {@link setup()} method.
   */
  public static function shutdown()
  {
    itsy_registry::delete('/');
    spl_autoload_unregister(array('itsy', 'autoloader'));
    restore_exception_handler();
  }
  
  /**
   * Load configuration file.
   * Loads the etc/config.php file if it exists. The config defaults are 
   * overwrote by the configuration file.
   */
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
  
  /**
   * Load a Partial
   * 
   * Partials can be used to include the content of one view/controller in
   * another. You may pass parameters to the partial.
   * 
   * @param string $controller controller name
   * @param string $action action to call from the specified controller name
   * @param array $param optional parameters to pass to the action
   * @return string html output of the partial
   */
  public static function partial($controller, $action = '', $param = null)
  {
    itsy::log("<b>CORE - Partial:</b> controller: $controller, action: $action, " . 
              "param: $param", 'dev');
    return itsy::dispatch($controller, $action, $param, $partial = true, $itsy = false);
  }
  
  /**
   * Dispatch a Request
   * 
   * This method is called from the front controller to dispatch requests to
   * the appropriate controller and action; however you may use it elsewhere in
   * your code.
   * 
   * @todo It seems more logical that $itsy should be true if its an internal call.
   * 
   * @param string $controller controller name
   * @param string $action action to call from the specified controller name
   * @param string $param optionally pass additional parameters
   * @param bool $partial true if you want the view content without the layout
   * @param bool $itsy false if this is called internaly to itsy
   * @return void|string
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
    return;
  }
  
  /**
   * Load a Controller File
   * 
   * Takes 'test_controller' and removes the '_controller'.
   * It then looks for the file in the controller directory.
   * The file is loaded and we check if the 'test' class exists.
   * 
   * @param string $controller name of the controller
   * @throws itsy_exception if loading the controller failed. in production mode
   *                        this will show a 404.
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
   * Itsy Autoloader
   * 
   * Once the autoloader is registerd {@link setup()} it is called whenever
   * you try using a class thats not been defined. An effort will be made to
   * load the undefined class.
   * 
   * @param string $class class name of the undefined class.
   * @return bool true on successes
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
  
  /**
   * Internal Log
   * 
   * This log method is currently used to log internal itsy calls for 
   * debugging and will probably be removed/replaced later on.
   * However it does the job; and is simple.
   * 
   * @param string $message the message you wish to log
   * @param string $kind use 'dev' if you wish to access during development
   */
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
  
  /**
   * Exception Handler
   * 
   * The exception handler is registerd by the {@link setup()} method.
   * It is called whenever an exception has not been caught by a try/catch block.
   * This is our last chance to handle the exception.
   * 
   * In development mode the message, code and trace information will be showen.
   * In production mode we will show either a 404 or 503 error.
   * 
   * @param Exception $e the thrown exception
   */
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
 * itsy_exception - base exception class
 * 
 * Exception class for itsy stuff.
 * 
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