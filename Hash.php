<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Hash module
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
 * This hash "module" is really just an implementation wrapper for various
 * mhash/mcrypt functions.
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Hash
{
   /**
    * Generates and authentication token that can be used to verify sessions.
    * For salt values, the generateSalt() function can be used.
    *
    * @param string $key Key to be used to create the hash from
    * @param string $salt A unique random value for this hash
    *
    * @return string
    */
   public static function generateToken($key, $salt, $bytes)
   {
      return base64_encode(mhash_keygen_s2k(MHASH_SHA256, $key, $salt, $bytes));
   }

   /**
    * Generates a random string containing the number of specified bytes
    *
    * @param int $bytes Number of bytes to generate
    *
    * @return string
    */
   public static function generateSalt($bytes)
   {
      MindFrame2_Validate::argumentIsInt($bytes, 1, 'bytes');

      $salt = substr(mcrypt_create_iv($bytes, MCRYPT_DEV_URANDOM), 0, $bytes);

      return $salt;
   }
}
