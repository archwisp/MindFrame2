<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Test class for MindFrame2_Hash
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
 * Test class for MindFrame2_Hash
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_HashTest extends PHPUnit_Framework_TestCase
{
   public function testGenerateToken()
   {
       $token = MindFrame2_Hash::generateToken('FooBar', '123456', 32);
       $this->assertEquals('8MT4KAeO5zUaNEXcUjGdwU9c08OGrs+iOR+2T/akesk=', base64_encode($token));
   }

   public function testGenerateSalt()
   {
       $token = MindFrame2_Hash::generateSalt(128);
       $this->assertEquals(128, strlen($token));
   }
}
