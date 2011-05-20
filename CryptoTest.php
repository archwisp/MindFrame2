<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Test class for MindFrame2_Crypto
 */

/**
 * Test class for MindFrame2_Crypto
 *
 * @author Bryan C. Geraghty <bryan@ravensight.org>
 * @since 2010-05-19
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
