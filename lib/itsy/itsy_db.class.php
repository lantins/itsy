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
    
    try {
      $this->connect($this->dsn);
    } catch (PDOException $e) {
      throw new itsy_db_exception('Unable to connect to database.', 0, $e);
    }
  }
  
  /**
   * Build DSN String
   * 
   * Builds a DSN string needed to connect to the database.
   * @todo make sure we look in the correct dir for the databases.
   */
  private function build_dsn()
  {
    if ($this->engine == 'sqlite') {
      if ($this->database == ':memory:') {
        $this->dsn = "sqlite::memory:";
      } else {
        $this->dsn = "sqlite:dbname={$this->database}";
      }
      
      if (strlen($this->host) > 0) {
        $this->dsn .= ";host={$this->host}";
      }
    }
    
    if ($this->engine == 'mysql') {
      $this->dsn = "mysql:dbname={$this->database}";
      if (strlen($this->host) > 0) {
        $this->dsn .= ";host={$this->host}";
      }
    }
  }
  
  /**
   * Connect/Create PDO Instance
   * 
   * Creates a PDO instance using your database settings.
   */
  private function connect()
  {
    if ($this->engine == 'sqlite') {
      $this->pdo = new PDO($this->dsn);
    }
    
    if ($this->engine == 'mysql') {
      $this->pdo = new PDO($this->dsn, $this->user, $this->pass);
    }
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
  
  public function get_sql_state()
  {
    return $this->sql_state;
  }
}

?>