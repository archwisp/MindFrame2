<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Test class for MindFrame2_Hash
 */

/**
 * Test class for MindFrame2_Hash
 *
 * @author Bryan C. Geraghty <bryan@ravensight.org>
 */
class MindFrame2_HashTest extends PHPUnit_Framework_TestCase
{
   public function testGenerateToken()
   {
       $token = MindFrame2_Hash::generateToken('FooBar', '123456', 32);
       $this->assertEquals('8MT4KAeO5zUaNEXcUjGdwU9c08OGrs+iOR+2T/akesk=', $token);
   }

   public function testGenerateSalt()
   {
       $token = MindFrame2_Hash::generateSalt(128);
       $this->assertEquals(128, strlen($token));
   }
}
