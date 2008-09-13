<?php defined('ITSY_PATH') or die('No direct script access.');
/**
 * @author Luke Antins <luke@lividpenguin.com>
 * @copyright Copyright (c) 2008, Luke Antins
 * @package itsy
 */

/**
 * itsy_controller
 * 
 * Simple overview as to what the controller does.
 * @package itsy
 */
class itsy_controller
{
    public $_layout = 'default';
    public $view_content = '';
    private $_view;
    private $_skip_vars;
    public $_request = '';
    public $_name = '';
    
    function __construct()
    {
      $this->_request = new itsy_request();
      $this->_request->get_instance();
      
      $this->_name = str_replace('_controller', '', get_class($this));
    }
    
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
    
    function _forward($controller, $action) {
      itsy::log("<b>CONTROLLER - " . get_class($this) . " - Forward:</b> controller: $controller, action: $action", 'dev');
      return array('directive' => 'forward', 'controller' => $controller, 'action' => $action);
    }
}

?>