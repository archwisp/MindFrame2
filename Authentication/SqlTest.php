<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Test case for MindFrame2_Authentication_Sql
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
 * Test case for MindFrame2_Authentication_Sql
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Authentication_SqlTest extends MindFrame2_Test_Abstract
{
   private $_instance;

   public function setUp()
   {
      $dbi = $this->getSingleDbi();
      $adapter = $this->getDbmsSchemaAdapter();

      $dbi->exec($adapter->buildCreateDatabaseSql());

      $crypto_module = new MindFrame2_Crypto(
         MindFrame2_Crypto::RIJNDAEL_256,
         MindFrame2_Crypto::MODE_CBC);

      $this->_instance = new MindFrame2_Authentication_Sql(
         $dbi, $adapter, $crypto_module, 'Easy Key',
         'Password', 'Username', 'Ciphertext', 'Iv');
   }

   public function testAuthenticateCorrectPassword()
   {
      $this->assertEquals(TRUE, $this->_instance->setPassword('Foo', 'Bar'));
      $this->assertEquals(TRUE, $this->_instance->authenticate('Foo', 'Bar'));
   }

   public function testAuthenticateNoPassword()
   {
      $this->assertEquals(TRUE, $this->_instance->setPassword('Foo', 'Bar'));
      $this->assertEquals(FALSE, $this->_instance->authenticate('Foo', ''));
   }

   public function testAuthenticateNoRecords()
   {
      $this->assertEquals(FALSE, $this->_instance->authenticate('Foo', 'Bar'));
   }

   public function testAuthenticateNoUsername()
   {
      $this->assertEquals(TRUE, $this->_instance->setPassword('Foo', 'Bar'));
      $this->assertEquals(FALSE, $this->_instance->authenticate('', 'Bar'));
   }

   public function testAuthenticateNoUsernameOrPassword()
   {
      $this->assertEquals(TRUE, $this->_instance->setPassword('Foo', 'Bar'));
      $this->assertEquals(FALSE, $this->_instance->authenticate('', ''));
   }

   public function testAuthenticateWrongPassword()
   {
      $this->assertEquals(TRUE,
         $this->_instance->setPassword('Foo', 'Bar'));
      $this->assertEquals(FALSE,
         $this->_instance->authenticate('Foo', 'NotBar'));
   }
}
