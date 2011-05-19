<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Test case for MindFrame2_Authentication_Sql
 *
 * @author Bryan C. Geraghty <bryan@ravensight.org>
 * @since 2011-05-16
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
       $this->assertEquals(TRUE, $this->_instance->setPassword('Foo', 'Bar'));
       $this->assertEquals(FALSE, $this->_instance->authenticate('Foo', 'NotBar'));
    }
}
