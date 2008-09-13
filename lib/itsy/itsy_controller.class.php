<?php defined('ITSY_PATH') or die('No direct script access.');
/**
 * itsy_controller
 * 
 * @author Luke Antins <luke@lividpenguin.com>
 * @copyright Copyright (c) 2008, Luke Antins
 * @license http://opensource.org/licenses/mit-license.php MIT license
 * @version 1.0
 * @package itsy
 */

/**
 * itsy_controller - base controller class
 * 
 * A controller processes and responds to actions (methods in the class).
 * It handles the input from the user and may call libraries or 'models'.
 * 
 * You should extend this class to build your own controller.
 * 
 * Actions and variables starting with an underscore _ are considered
 * internal / private and should not be used directly.
 * 
 * Variables that do not start with _ will be passed to the views scope.
 * 
 * @version 1.0
 * @package itsy
 */
class itsy_controller
{
  /** Layout file (without the .phtml) used by _render() */
  public $_layout = 'default';
  /** This is were the view content goes once _render() is called */
  public $view_content = '';
  /** View file (without the .phtml) used by _render() */
  private $_view;
  /** If set to true; class vars won't be braught into scope for the view */
  private $_skip_vars;
  /** Holds an instance of itsy_request to access $_GET, $_POST and $_COOKIE data */
  public $_request = '';
  /** Name of the controller without the _controller part */
  public $_name = '';
  
  /**
   * Controller Constructor
   * 
   * Sets the name of the controller and gets an instance of itsy_request.
   */
  function __construct()
  {
    $this->_request = new itsy_request();
    $this->_request->get_instance();
    
    $this->_name = str_replace('_controller', '', get_class($this));
  }
  
  /**
   * Execute a Action
   * 
   * Execute a action inside a controller. Actions are just public methods
   * inside your controller class. You may optionally pass parameters.
   * 
   * @param string $action name of the action you wish to execute
   * @param array $param optional parameters
   * @return mixed
   */
  function _execute($action, $param)
  {
    itsy::log("<b>CONTROLLER - " . get_class($this) . " - Execute:</b> action: $action, param: $param", 'dev');
    
    $this->_view = $action;
    
    // check the action exists in the controller.
    //if (method_exists($this, $action)) {
    //  $this->{$action}($param); // call the method.
    //}
    
    if (method_exists($this, $action) == false) {
      $this->_skip_vars = true;
      return false;
    }
    
    $reflector = new ReflectionMethod($this, $action);
    $args_info = $reflector->getParameters();
    $args = array();
    
    foreach ($args_info as $arg) {
      $args[$arg->name] = $param[$arg->name];
    }
    $result = $reflector->invokeArgs($this, $args);
    return $result;
  }
  
  /**
   * Render a Action
   * 
   * Execute an action inside a controller. Actions are just public methods
   * inside your controller class. You may optionally pass parameters.
   * 
   * @param string $action name of the action
   * @param bool $itsy false if this is called internaly to itsy
   * @return mixed
   * @throws itsy_exception if the view file was not found
   * @throws itsy_exception if were trying to call a iternal action 
   *                        (starting with a _) externally
   */
  function _render($action, $itsy = false)
  {
    itsy::log("<b>CONTROLLER - " . get_class($this) . " - Render</b>", 'dev');
    
    if ($itsy == true) {
      if (method_exists($this, '_' . $action)) {
        throw new itsy_exception('Access Denied to internal view: ' . $action);
      }
    }
    
    $this->_view = $action;
    
    if ($this->_skip_vars == false) {
      $vars = get_object_vars($this);
      extract($vars, EXTR_REFS); // bring the class vars into scope for the layout.
    }
    
    // grab the view output
    $controller = str_replace('controller', '', get_class($this));
    $controller_view_dir = str_replace('_', '/', $controller);
    $view_file = itsy_registry::get('/itsy/app_path') . itsy_registry::get('/itsy/view_path') . $controller_view_dir . $this->_view . '.phtml';
    if (is_readable($view_file)) {
      ob_start();
      include($view_file);
      $this->view_content = ob_get_contents();
      ob_end_clean();
    } else {
      throw new itsy_exception('The view "'.$this->_view.'" was not found');
    }
    
    $this->_skip_vars = false;
  }
  
  /**
   * Render Layout
   * 
   * Unless were getting called by a partial (or an ajax request?) we will want
   * to display the layout around our views. This is the method that does just
   * that.
   */
  function _render_layout()
  {
    itsy::log("<b>CONTROLLER - " . get_class($this) . " - Render Layout</b>", 'dev');
    
    $vars = get_object_vars($this);
    if (itsy_registry::get('/itsy/environment') == 'development') {
      $vars['itsy_log_dev'] = itsy::$log_dev;
    }
    extract($vars, EXTR_REFS); // bring the class vars into scope for the layout.

    @include(itsy_registry::get('/itsy/app_path') . itsy_registry::get('/itsy/layout_path') . $this->_layout . '.phtml');
  }
  
  /**
   * Forward To Another Action
   * 
   * This is used inside a controllers action to 'forward' to request to 
   * another controller/action.
   * 
   * @param string $controller name of the controller
   * @param string $action name of the action
   * @return array directive information tells {@link itsy::dispatch()} what to do
   */
  function _forward($controller, $action) {
    itsy::log("<b>CONTROLLER - " . get_class($this) . " - Forward:</b> controller: $controller, action: $action", 'dev');
    return array('directive' => 'forward', 'controller' => $controller, 'action' => $action);
  }
}

?>