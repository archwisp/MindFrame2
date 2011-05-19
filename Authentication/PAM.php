<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * PAM authentication module
 */

/**
 * PAM authentication module. Implementing PAM authentication in your web 
 * application comes with the risk of exposing your shaddow file because the 
 * web server needs to be able to read the file. You will also need to do some 
 * configuration of /etc/pam.d for this to work. See the PECL PAM 
 * documentation.
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2010-01-06
 */
class MindFrame2_Authentication_PAM extends MindFrame2_Authentication_Abstract
{
   /**
    * Authenticates the username/password
    *
    * @param string $username Username for the user being authenticated
    * @param string $password Password for the user being authenticated
    *
    * @return bool
    *
    * @throws RuntimeException If an error is returned by the PAM module
    */
   public function authenticate($username, $password)
   {
      $error = NULL;
      $authenticated = pam_auth($username, $password, $error);

      return $authenticated;
   }
}
