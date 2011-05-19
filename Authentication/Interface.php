<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Authentication module interface
 */

/**
 * Authentication module interface
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2010-01-06
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
