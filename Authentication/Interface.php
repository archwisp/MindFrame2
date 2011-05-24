<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Authentication module interface
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
 * Authentication module interface
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
interface MindFrame2_Authentication_Interface
{
   /**
    * Authenticates the username/password against the host
    *
    * @param string $username Username for the user being authenticated
    * @param string $password Password for the user being authenticated
    *
    * @return bool
    */
   public function authenticate($username, $password);

   /**
    * Sets the specified user's password to the specified string
    *
    * @param string $username The user for which to set the password
    * @param string $password The password to be set
    *
    * @return bool
    */
   public function setPassword($username, $password);
}
