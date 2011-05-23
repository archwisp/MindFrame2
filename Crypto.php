<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Encryption/decryption module
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
 * Encryption/decryption module
 * 
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Crypto
{
   const RIJNDAEL_256 = MCRYPT_RIJNDAEL_256;
   const MODE_CBC = MCRYPT_MODE_CBC;

   private $_algorithm;
   private $_mode;

   public function __construct($algorithm, $mode)
   {
      $this->_algorithm = $algorithm;
      $this->_mode = $mode;
   }

   public function encrypt($plaintext, $key, $iv)
   {
      return mcrypt_encrypt($this->_algorithm,
         $key, $plaintext, $this->_mode, $iv);
   }

   public function decrypt($ciphertext, $key, $iv)
   {
      return mcrypt_decrypt($this->_algorithm,
         $key, $ciphertext, $this->_mode, $iv);
   }

   public function getBlockSize()
   {
      return mcrypt_get_iv_size($this->_algorithm, $this->_mode);
   }

   public function generateIv()
   {
      return mcrypt_create_iv($this->getBlockSize(), MCRYPT_DEV_URANDOM);
   }

   public function padWithNulls($plaintext)
   {
      return str_pad($plaintext, $this->getBlockSize(), "\x0");
   }

   public function trimNulls($plaintext)
   {
      return rtrim($plaintext, "\x0");
   }
}
