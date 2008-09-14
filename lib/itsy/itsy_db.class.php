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
   * Grabs the db settings from itsy_registry.
   */
  public function __construct($config)
  {
    $settings = array('engine', 'host', 'database', 'user', 'pass');
    
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
    
    $this->build_dsn();
    $this->connect($this->dsn);
  }
  
  /**
   * Build DSN String
   * 
   * Builds a DSN string needed to connect to the database.
   * @todo make sure we look in the correct dir for the databases.
   * @throws itsy_db_exception if were unable to build a dsn
   */
  private function build_dsn()
  {
    if ($this->engine == 'sqlite') {
      if ($this->database == ':memory:') {
        $this->dsn = "sqlite::memory:";
      } else {
        if ($this->database{0} != '/') {
          $app_path = itsy_registry::get('/itsy/app_path');
          if (is_dir($app_path)) {
            $this->database = $app_path . $this->database;
          }
        }
        
        $this->dsn = "sqlite:{$this->database}";
      }
      return;
    }
    
    if ($this->engine == 'mysql') {
      $this->dsn = "mysql:dbname={$this->database}";
      if (strlen($this->host) > 0) {
        $this->dsn .= ";host={$this->host}";
      }
      return;
    }
    
    throw new itsy_db_exception('Unable to build pdo dsn; please check your configuration.');
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
      if ($this->engine == 'sqlite') {
        $this->pdo = new PDO($this->dsn);
      }

      if ($this->engine == 'mysql') {
        $this->pdo = new PDO($this->dsn, $this->user, $this->pass);
      }
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
    return $this->pdo->beginTransaction();
  }
  
  /**
   * Begin Transaction
   * 
   * Initiates a transaction
   * @return bool
   */
  public function commit()
  {
    return $this->pdo->commit();
  }
  
  /**
   * Roll Back Transaction
   * 
   * Rolls back a transaction
   * @return bool
   */
  public function roll_back()
  {
    return $this->pdo->rollBack();
  }
  
  /**
   * Get Last Insert ID
   * 
   * Returns the id of the last inserted row or sequence object.
   * @param string $name of the sequence object
   * @return bool
   */
  public function get_last_insert_id($name = '')
  {
    return $this->pdo->lastInsertId($name);
  }
  
  /**
   * Execute SQL Statement
   * 
   * This method should not be used to gather data from the database, only to
   * execute SQL statments like INSERT; UPDATE. etc...
   * 
   * The number of rows affected by the operation is returned.
   */
  public function execute()
  {
  }
  
  /**
   * Get Single Value
   * 
   * Returns the first field, of the first row, of the results.
   * This is useful for COUNT() operations.
   */
  public function get_single_value()
  {
  }
  
  /**
   * Get Single Row
   * 
   * Returns the first row of the results.
   */
  public function get_single_row()
  {
  }
  
  /**
   * Select
   * 
   * Returns an iteratable object of results for a SELECT statement.
   */
  public function select()
  {
  }
  
  /**
   * Insert
   * 
   * Builds and executes SQL for an INSERT statement.
   * The SQL is built from an associative array of: field => value
   */
  public function insert()
  {
  }
  
  /**
   * Update
   * 
   * The same as {@link insert()}, however this time we build a UPDATE statement.
   */
  public function update()
  {
  }
  
  // PDO::prepare — Prepares a statement for execution and returns a statement object
  // PDO::query — Executes an SQL statement, returning a result set as a PDOStatement object
  // PDO::exec — Execute an SQL statement and return the number of affected rows
  
  // PDO::quote — Quotes a string for use in a query.
  // PDO::errorCode — Fetch the SQLSTATE associated with the last operation on the database handle
  // PDO::errorInfo — Fetch extended error information associated with the last operation on the database handle
  
  // PDO::getAttribute — Retrieve a database connection attribute
  // PDO::getAvailableDrivers — Return an array of available PDO drivers
  // PDO::setAttribute — Set an attribute
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