<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * DBMS connection model for IP networked servers
 */

/**
 * DBMS connection model for IP networked servers
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2010-12-09
 */
class MindFrame2_Dbms_Connection_Ip
   implements MindFrame2_Dbms_Connection_Interface
{
   /**
    * @param string
    */
   private $_dbms;

   /**
    * @var string
    */
   private $_host;

   /**
    * @var MindFrame2_Dbms_Schema_Node
    */
   private $_port;

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
    * @param string $dbms DBMS identifier
    * @param string $host Host on which the database resides
    * @param string $port Port on which to connect
    * @param string $username Username
    * @param string $password Password
    */
   public function __construct($dbms, $host, $port, $username, $password)
   {
      MindFrame2_Validate::argumentIsNotEmpty($dbms, 1, 'dbms');
      MindFrame2_Validate::argumentIsNotEmpty($host, 2, 'host');
      MindFrame2_Validate::argumentIsInt($port, 3, 'port');

      $this->_dbms = $dbms;
      $this->_host = $host;
      $this->_port = $port;
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
      return sprintf('%s:host=%s;port=%d',
         $this->_dbms, $this->_host, $this->_port);
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
