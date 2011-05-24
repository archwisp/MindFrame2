<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Abstract server authentication module
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
 * Abstract server authentication module
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
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
