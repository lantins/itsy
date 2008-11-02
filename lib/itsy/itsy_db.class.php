<?php defined('ITSY_PATH') or die('No direct script access.');
/**
 * itsy_db - pdo database wrapper
 * 
 * @author Luke Antins <luke@lividpenguin.com>
 * @copyright Copyright (c) 2008, Luke Antins
 * @license http://opensource.org/licenses/mit-license.php MIT license
 * @package itsy
 */

/**
 * itsy_db - database wrapper for pdo
 * 
 * This wrapper simplyfies database access. For me anyhow.
 * @package itsy
 */
class itsy_db
{
  private $engine;
  private $host;
  private $database;
  private $user;
  private $pass;
  private $dsn;
  /** PDO Instance */
  private $pdo = null;
  
  /**
   * Class Constructor
   * 
   * Grabs the db settings from $config or itsy_registry (if its available).
   */
  public function __construct($config)
  {
    $settings = array('dsn', 'user', 'pass');
    
    // look for a database config in the itsy_registry
    if (is_string($config) && class_exists('itsy_registry')) {
      foreach ($settings as $setting) {
        $value = itsy_registry::get("/itsy/db/$config/$setting");
        if (!empty($value)) {
          $this->$setting = $value;
        }
      }
    }
    // use configuration array passed to the constructor
    if (is_array($config)) {
      foreach ($settings as $setting) {
        if (!empty($config[$setting])) {
          $this->$setting = $config[$setting];
        }
      }
    }
    
    $this->connect($this->dsn);
  }
  
  /**
   * Connect/Create PDO Instance
   * 
   * Creates a PDO instance using your database settings.
   * @throws itsy_db_exception if were unable to connect; pdo error details 
   *                           will be passed along
   */
  private function connect()
  {
    try {
      // if the user and/or pass is missing; connect without them.
      if (empty($this->user) && empty($this->pass)) {
        $this->pdo = new PDO($this->dsn);
      } else {
        $this->pdo = new PDO($this->dsn, $this->user, $this->pass);
      }
      // exceptions please!
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
    } catch (PDOException $e) {
      throw new itsy_db_exception("Unable to connect to PDO database. (DSN: {$this->dsn})", 0, $e);
    }
  }
  
  /**
   * Begin Transaction
   * 
   * Initiates a transaction
   * @return bool
   */
  public function begin()
  {
    try {
      $this->pdo->beginTransaction();
    } catch (PDOException $e) {
      // TODO: Throw more useful exceptions luke
      throw new itsy_db_exception($e->getMessage());
    }
  }
  
  /**
   * Begin Transaction
   * 
   * Initiates a transaction
   * @return bool
   */
  public function commit()
  {
    try {
      $this->pdo->commit();
    } catch (PDOException $e) {
      // TODO: Throw more useful exceptions luke
      throw new itsy_db_exception($e->getMessage());
    }
  }
  
  /**
   * Roll Back Transaction
   * 
   * Rolls back a transaction
   * @return bool
   */
  public function roll_back()
  {
    try {
      $this->pdo->rollBack();
    } catch (PDOException $e) {
      // TODO: Throw more useful exceptions luke
      throw new itsy_db_exception($e->getMessage());
    }
  }
  
  /**
   * Get Last Insert ID
   * 
   * Returns the id of the last inserted row or sequence object.
   * (n.b. sequences: http://search.cpan.org/~adamk/DBIx-MySQLSequence-1.00/lib/DBIx/MySQLSequence.pm)
   * @param string $name of the sequence object
   * @return bool
   */
  public function get_last_insert_id($name = '')
  {
    try {
      $id = $this->pdo->lastInsertId($name);
    } catch (PDOException $e) {
      // TODO: Throw more useful exceptions luke
      // we should only get an exception if the PDO driver does not support this capability?
      throw new itsy_db_exception($e->getMessage());
    }
    
    return $id;
  }
  
  /**
   * Execute SQL Statement
   * 
   * This method should not be used to gather data from the database, only to
   * execute SQL statments like INSERT; UPDATE. etc...
   * 
   * @param string $sql statement
   * @param array $params single value or an array of values
   * @return integer number of rows affected by the operation
   */
  public function execute($sql, $params = array())
  {
    $statement = $this->pdo_prepare_execute_statement($sql, $params);
    return $statement->rowCount();
  }
  
  /**
   * Get Single Value
   * 
   * Returns the first field, of the first row, of the results.
   * This is useful for COUNT() operations.
   */
  public function get_single_value($sql, $params = array())
  {
    $statement = $this->pdo_prepare_execute_statement($sql, $params);
    return $statement->fetchColumn(0);
  }
  
  /**
   * Get Single Row
   * 
   * Returns the first row of the results.
   */
  public function get_single_row($sql, $params = array())
  {
    $statement = $this->pdo_prepare_execute_statement($sql, $params);
    return $statement->fetchObject('itsy_db_row');
  }
  
  /**
   * Get Array
   * 
   * Returns all results as an array
   */
  public function get_array($sql, $params = array())
  {
    $statement = $this->pdo_prepare_execute_statement($sql, $params);
    return $statement->fetchAll();
  }
  
  /**
   * Select
   * 
   * Returns an iteratable object of results for a SELECT statement.
   */
  public function select($sql, $params = array())
  {
    try {
      $statement = $this->pdo->prepare($sql);
    } catch (PDOException $e) {
      throw new itsy_db_exception($e->getMessage());
    }
    
    $params = is_array($params) ? $params : array($params);
    return new itsy_db_recordset($statement, $params);
  }
  
  /**
   * Insert
   * 
   * Builds and executes SQL for an INSERT statement.
   * The SQL is built from an associative array of: field => value
   */
  public function insert($table, array $data)
  {
    $column_names = implode(', ', array_keys($data));
    foreach ($data as $name => $value) {
      $column_values[] = ':' . $name;
    }
    $column_values = implode(', ', $column_values);
    
    $sql = 'INSERT INTO %s(%s) VALUES(%s)';
    $sql = sprintf($sql, $table, $column_names, $column_values);
    
    return $this->execute($sql, $data);
  }
  
  /**
   * Update
   * 
   * The same as {@link insert()}, however this time we build a UPDATE statement.
   */
  public function update($table, $data, $criteria)
  {
    foreach ($data as $name => $value) {
      $column_values[] = "$name = :$name";
    }
    $column_values = implode(', ', $column_values);
    
    $sql = 'UPDATE %s SET (%s) WHERE %s';
    $sql = sprintf($sql, $table, $column_values, $criteria);
    
    return $this->execute($sql, $data);
  }
  
  private function pdo_prepare_execute_statement($sql, $params = array())
  {
    try {
      $statement = $this->pdo->prepare($sql);
      $params = is_array($params) ? $params : array($params);
      $statement->execute($params);
    } catch (PDOException $e) {
      throw new itsy_db_exception($e->getMessage());
    }
    
    return $statement;
  }
}

/**
 * itsy_db_row - represent a single row
 * 
 * Simple class to provide oo access to sql data.
 * @package itsy
 */
class itsy_db_row
{
  public function __get($name) {
    throw new itsy_db_exception("Unable to get unexistent field: $name");
  }
}

/**
 * itsy_db_recordset - a set of itsy_db_row records
 * 
 * Provides an iteratable; countable object for dealing with multiple rows.
 * @package itsy
 */
class itsy_db_recordset implements Iterator, Countable
{
  private $statement = null; // PDO statement object.
  private $params = array(); // SQL query parameters.
  private $current_row_object = null; // represents the current row in the set.
  private $current_row_index = 0; // index of the current row.
  
  public function __construct(PDOStatement $statement, array $params) {
    $this->statement = $statement;
    $this->params = $params;
  }
 
  public function refresh() {
    $this->statement->execute($this->params);
    if ($this->statement->errorCode() !== '00000') {
      throw new itsy_db_exception($this->statement->errorInfo());
    }
  }
 
  public function current() {
    return $this->current_row_object;
  }
 
  public function key() {
    return $this->current_row_index;
  }

  public function next() {
    $this->current_row_object = $this->statement->fetchObject('itsy_db_row');
    if ($this->statement->errorCode() !== '00000') {
      throw new itsy_db_exception($this->statement->errorInfo());
    }
    $this->current_row_index++;
    
    return $this->current_row_object;
  }
 
  public function rewind() {
    $this->refresh();
    $this->current_row_index = 0;
    $this->current_row_object = $this->statement->fetchObject('itsy_db_row');
    if ($this->statement->errorCode() !== '00000') {
      throw new itsy_db_exception($this->statement->errorInfo());
    }
 
  }
 
  public function valid() {
    return $this->current_row_object !== false;
  }
 
  public function count() {
    return $this->statement->rowCount();
  }
 
  function __destruct() {
    $this->statement->closeCursor();
  }
}


/**
 * itsy_db_exception - database related exceptions
 * 
 * Any database related exceptions will throw itsy_db_exception.
 * @package itsy
 */
class itsy_db_exception extends Exception
{
  /** a five-character alphanumeric identifier defined in the ANSI SQL standard */
  private $sql_state;
  /** driver specific error code */
  private $driver_error_code;
  /** driver specific error message */
  private $driver_error_message;
  
  public function __construct($message = null, $code = 0, $e = null)
  {
    parent::__construct($message, $code);
    
    if ($e instanceof PDOException) {
      $pdo_error_message = $e->getMessage();
      $this->message = "$message (PDO: $pdo_error_message)";
    }
  }
  
  /**
   * Get SQLSTATE
   * @todo need to ensure the sql_state is reliable set?
   */
  public function get_sql_state()
  {
    return $this->sql_state;
  }
}

?>