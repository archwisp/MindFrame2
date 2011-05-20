<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Apache htpasswd authentication module
 */

/**
 * Apache htpasswd authentication module. This module currently only supports
 * DES, the default mode of htpasswd encryption.
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2010-01-06
 */
class MindFrame2_Authentication_File_Htpasswd
   extends MindFrame2_Authentication_File_Abstract
{
   /**
    * @var array
    */
   private $_logins = array();

   /**
    * @var bool
    */
   private $_logins_loaded = FALSE;

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
      $this->loadLoginsOnce();

      if (!isset($this->_logins[$username]))
      {
         return FALSE;
      }

      $hash = $this->_logins[$username];
      $salt = $this->parseSalt($hash);
      $password_hash = $this->generateHash($password, $salt);

      if ($hash === $password_hash)
      {
         return TRUE;
      }

      return FALSE;
   }

   /**
    * Builds a DES hash out of the specified plaintext and salt
    *
    * @param string $plaintext The plaintext to be hashed
    * @param string $salt The salt to be used for hashing
    *
    * @return string
    */
   protected function generateHash($plaintext, $salt)
   {
      return crypt($plaintext, $salt);
   }

   /**
    * Returns the salt portion of a DES string
    *
    * @param string $hash A DES hash
    *
    * @return string
    */
   protected function parseSalt($hash)
   {
      return substr($hash, 0, 2);
   }

   /**
    * Loads all of the logins as a username => ciphertext associative array
    * into the logins property of the object.
    *
    * @return void
    */
   protected function loadLoginsOnce()
   {
      if ($this->_logins_loaded !== TRUE)
      {
         $logins = $this->readFile();
         $this->_logins = MindFrame2_Array::reKey($logins, ':');
         $this->_logins_loaded = TRUE;

         return TRUE;
      }

      return FALSE;
   }

   /**
    * Sets the specified user's password to the specified string
    *
    * @param string $username The user for which to set the password
    * @param string $password The password to be set
    *
    * @return bool
    */
   public function setPassword($username, $password)
   {
      $this->loadLoginsOnce();

      $this->_logins[$username] = $this->generateHash(
         $password, base64_encode($this->generateSalt(2)));

      return $this->writeLogins();
   }

   /**
    * Writes the logins property of the object back to the password file
    *
    * @return void
    */
   protected function writeLogins()
   {
      $content = NULL;

      foreach ($this->_logins as $username => $hash)
      {
         $content .= sprintf("%s:%s\n", $username, $hash);
      }

      return $this->writeFile($content);
   }
}
