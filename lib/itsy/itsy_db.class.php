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
  function __construct()
  {
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
  
}

?>