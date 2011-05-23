<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Abstract server authentication module
 */

/**
 * Abstract server authentication module
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
abstract class MindFrame2_Authentication_Server_Abstract
   extends MindFrame2_Authentication_Abstract
{
   /**
    * @var resource
    */
   protected $connection;

   /**
    * @var string
    */
   private $_hostname;

   /**
    * @var string
    */
   private $_username;

   /**
    * @var string
    */
   private $_password;

   /**
    * Establishes the connection to the authentication host and sets the
    * resource in the $connection property.
    *
    * @return bool
    */
   protected abstract function connect();

   /**
    * Initializes the hostname, username, and password for the authentication
    * server. The username and password specified here are NOT used for user
    * authentication; they are used only to establish a connection the
    * authentication server.
    *
    * @param string $hostname Authentication server hostname
    * @param string $username Username for access to the authentication server
    * @param string $password Password for access to the authentication server
    *
    * @throws InvalidArgumentException if hostname is empty
    */
   public function __construct($hostname, $username, $password)
   {
      MindFrame2_Validate::argumentIsNotEmpty($hostname, 1, 'hostname');

      $this->_hostname = $hostname;
      $this->_username = $username;
      $this->_password = $password;
   }

   /**
    * Returns the connection resource
    *
    * @return resource
    */
   protected function getConnection()
   {
      return $this->connection;
   }
}
