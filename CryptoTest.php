<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Test class for MindFrame2_Crypto
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
 * Test class for MindFrame2_Crypto
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_CryptoTest extends PHPUnit_Framework_TestCase
{
   private $_instance;
   private $_encoded_iv = '6R3yY9RWoIvsE4fR43nAUUtr12hJmssJJ/vnSh1T8e4=';

   public function setUp()
   {
      $this->_instance = new MindFrame2_Crypto(
         MindFrame2_Crypto::RIJNDAEL_256,
         MindFrame2_Crypto::MODE_CBC);
   }

   public function testDecrypt()
   {
      $plaintext = $this->_instance->decrypt(
         base64_decode('k8xz1hVUYUAe9qg2gebMgViyHOEji2q1KUXwUnwHPwk='),
         'Easy Key', base64_decode($this->_encoded_iv));

      $padded = $this->_instance->padWithNulls('FooBar ');
      $this->assertEquals($padded, $plaintext);

      $trimmed = $this->_instance->trimNulls($plaintext);
      $this->assertEquals('FooBar ', $trimmed);
   }

   public function testEncrypt()
   {
      $ciphertext = $this->_instance->encrypt(
         'FooBar ', 'Easy Key', base64_decode($this->_encoded_iv));

       $this->assertEquals('k8xz1hVUYUAe9qg2gebMgViyHOEji2q1KUXwUnwHPwk=', base64_encode($ciphertext));
   }

   public function testGenerateIvLength()
   {
      $iv = $this->_instance->generateIv();
      $this->assertEquals(32, strlen($iv));

      $second_iv = $this->_instance->generateIv();
      $this->assertEquals(32, strlen($iv));

      $this->assertNotEquals($iv, $second_iv);
   }
}
