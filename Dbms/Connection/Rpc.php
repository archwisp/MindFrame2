<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * DBMS connection model for RPC servers
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   MindFrame2
 * @author    Bryan C. Geraghty <bryan@ravensight.org>
 * @copyright 2005-2011 Bryan C. Geraghty
 * @license   http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link      https://github.com/archwisp/MindFrame2
 */

/**
 * DBMS connection model for RPC servers
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Dbms_Connection_Rpc
   implements MindFrame2_Dbms_Connection_Interface
{
   /**
    * @var string
    */
   private $_url;

   /**
    * @var string
    */
   private $_username;

   /**
    * @var string
    */
   private $_password;

   /**
    * Configures the connection string, username, and password properties.
    *
    * @param string $url The URL of the RPC server
    * @param string $username Username
    * @param string $password Password
    */
   public function __construct($dbms, $url, $username, $password)
   {
      MindFrame2_Validate::argumentIsNotEmpty($dbms, 1, 'dbms');
      MindFrame2_Validate::argumentIsNotEmpty($url, 2, 'url');

      $this->_dbms = $dbms;
      $this->_url = $url;
      $this->_username = $username;
      $this->_password = $password;
   }

   /**
    * Builds the dsn string for the connection
    *
    * @return string
    */
   public function buildDsn()
   {
      return $this->_url;
   }

   /**
    * Retreives the DBMS identifier portion of the DSN
    *
    * @return string
    */
   public function getDbms()
   {
      return $this->_dbms;
   }

   /**
    * Retrieves the username for the connection
    *
    * @return string
    */
   public function getUsername()
   {
      return $this->_username;
   }

   /**
    * Retrieves the password for the connection
    *
    * @return string
    */
   public function getPassword()
   {
      return $this->_password;
   }
}
