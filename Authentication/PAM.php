<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * PAM authentication module
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
 * PAM authentication module. Implementing PAM authentication in your web
 * application comes with the risk of exposing your shaddow file because the
 * web server needs to be able to read the file. You will also need to do some
 * configuration of /etc/pam.d for this to work. See the PECL PAM
 * documentation.
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
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
