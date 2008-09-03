<?php defined('ITSY_PATH') or die('No direct script access.');
/**
 * Copyright (c) 2008 Luke Antins <luke@lividpenguin.com>
 * All rights reserved.
 *
 * http://www.lividpenguin.com/
 */

class itsy_db
{
  private static $instance;
  private $_name;
  private $_db;
  private $_result;
  
  function __construct()
  {
  }
  
  public static function get_instance() {
    if ((self::$instance instanceof self) == false) { 
      self::$instance = new self;
    }
    return self::$instance;
  }
  
  public function connect($name)
  {
    $this->_name = $name;
    $env = itsy::$config['environment'];
    if (is_array(itsy::$config['db'][$env][$name])) {
      $config = itsy::$config['db'][$env][$name];
      $this->_db = @mysql_connect ($config['host'], $config['user'], $config['pass']);
      
      // see if we connected?
      if (empty ($this->_db)) {
        throw new itsy_db_exception('Unable to connect to db: '.$this->_name.'(Error Num: '.mysql_errno().')');
      }
    } else {
      throw new itsy_db_exception('Unable to find db config for: '.$this->_name);
    }
  }
  
  function query ($database, $sql)
  {
    // reset the results back to false.
    $this->_result = false;
    
    // select the database.
    if (!@mysql_select_db ($database)) {
      itsy::log(sprintf('<b>DB - %s: Unable to select database: %s (mysql err no: %s)', $this->_name, $database, mysql_errno()), 'dev');
      return false;
    }
    
    // execute the sql query
    $result = mysql_query($sql);
    
    // check if the query failed.
    if ($result == false) {
      itsy::log(sprintf('<b>DB - %s: Unable to execute SQL query (mysql err no: %s)', $this->_name, mysql_errno()), 'dev');
      return false;
    }
    
    // if we got this far, it worked!
    $this->_result = $result;
    return true;
  }
  
  // fetch a single row on its own
  function fetch_row($column = '')
  {
    // make sure we have some results to work with first
    if ($this->_result != false) {
      $row = mysql_fetch_assoc($this->_result);
      if ($column != '') {
        if (empty($row[$column])) {
          return false;
        }
        
        return $row[$column];
      }
      
      return $row;
    }
    
    // if we got this far, we have no results.
    itsy::log(sprintf('<b>DB - %s: Unable to fetch row, SQL query failed (mysql err no: %s)', $this->_name, mysql_errno()), 'dev');
    return false;
  }
  
  // fetch each row as an array, you can stick this in a while loop as above.
  function fetch_each_row ()
  {
    // make sure we have some results to work with first
    if ($this->_result != false) {
      $array = mysql_fetch_array ($this->_result);
      return $array;
    }
    
    // if we got this far, we have no results.
    itsy::log(sprintf('<b>DB - %s: Unable to fetch array, SQL query failed (mysql err no: %s)', $this->_name, mysql_errno()), 'dev');
    return false;
  }
  
  // count the number of rows in the result.
  function number_of_rows ()
  {
    // make sure we have some results to work with first
    if ($this->_result != false) {
      $count = mysql_num_rows($this->_result);
      return $count;
    }
    
    // if we got this far, we have no results.
    itsy::log(sprintf('<b>DB - %s: Unable count number of rows, SQL query failed (mysql err no: %s)', $this->_name, mysql_errno()), 'dev');
    return false;
  }
  
  function number_of_affected_rows ()
  {
    // make sure we have some results to work with first
    if ($this->_result != false) {
      $count = mysql_affected_rows ($this->_db);
      return $count;
    }
    
    // if we got this far, we have no results.
    itsy::log(sprintf('<b>DB - %s: Unable to count number of affected rows, SQL query failed (mysql err no: %s)', $this->_name, mysql_errno()), 'dev');
    return false;
  }
  
  function fetch_array ()
  {
    // make sure we have some results to work with first
    if ($this->_result != false) {
      $array = array();
      
      // build an array of all the rows.
      while ($row = $this->fetch_each_row()) {
        $array[] = $row;
      }
      
      return $array;
    }
    
    // if we got this far, we have no results.
    itsy::log(sprintf('<b>DB - %s: Unable to fetch array, SQL query failed (mysql err no: %s)', $this->_name, mysql_errno()), 'dev');
    return false;
  }
}

class itsy_db_exception extends itsy_exception
{
  
}

?>