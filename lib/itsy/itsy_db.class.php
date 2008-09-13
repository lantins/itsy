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
  private $pdo;
  
  /**
   * Class Constructor
   * 
   * Grabs the db settings from itsy_registry.
   */
  public function __construct($config)
  {
    $this->engine = itsy_registry::get("/itsy/db/$config/engine");
    $this->host = itsy_registry::get("/itsy/db/$config/host");
    $this->database = itsy_registry::get("/itsy/db/$config/database");
    $this->user = itsy_registry::get("/itsy/db/$config/user");
    $this->pass = itsy_registry::get("/itsy/db/$config/pass");
    
    $this->build_dsn();
    
    try {
      $this->connect($this->dsn);
    } catch (Exception $e) {
      // re-throw as itsy_db_exception?
    }
  }
  
  /**
   * Build DSN String
   * 
   * Builds a DSN string needed to connect to the database.
   */
  private function build_dsn()
  {
    if ($this->engine == 'sqlite') {
      $this->dsn = "{sqlite}:dbname={$this->database}";
      if (strlen($this->host) > 0) {
        $this->dsn .= ";host={$this->host}";
      }
    }
    
    if ($this->engine == 'mysql') {
      $this->dsn = "{mysql}:dbname={$this->database}";
      if (strlen($this->host) > 0) {
        $this->dsn .= ";host={$this->host}";
      }
    }
    
    return $dsn;
  }
  
  /**
   * Connect/Create PDO Instance
   * 
   * Creates a PDO instance using your database settings.
   */
  private function connect()
  {
    if ($this->engine == 'sqlite') {
      $this->pdo = new PDO($dsn);
    }
    
    if ($this->engine == 'mysql') {
      $this->pdo = new PDO($dsn, $this->user, $this->pass);
    }
  }
}

/**
 * itsy_db_exception - database related exceptions
 * 
 * Any database related errors will be thrown as itsy_db_exception.
 * @package itsy
 */
class itsy_db_exception extends itsy_exception
{
  /** a five-character alphanumeric identifier defined in the ANSI SQL standard */
  private $sql_state;
  /** driver specific error code */
  private $driver_error_code;
  /** driver specific error message */
  private $driver_error_message;
  
  
}

?>