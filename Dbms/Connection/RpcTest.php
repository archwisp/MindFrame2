<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Test class for MindFrame2_Dbms_Connection_Ip_Mysql
 */

/**
 * Test class for MindFrame2_Dbms_Connection_Ip_Mysql
 *
 * @author Bryan C. Geraghty <bryan@ravensight.org>
 * @since 2010-12-14
 */
class MindFrame2_Dbms_Connection_RpcTest extends MindFrame2_Test_Abstract
{
   /**
    * @var MindFrame2_Dbms_Connection_Rpc
    */
   protected $object;

   /**
    * Sets up the fixture.
    *
    * @return void
    */
   protected function setUp()
   {
      $this->object = new MindFrame2_Dbms_Connection_Rpc(
         'xsim', 'https://foo/?module=bar', 'Foo', 'Bar');
   }

   /**
    * Tests the buildDsn function.
    *
    * @return void
    */
   public function testBuildDsn()
   {
      $this->assertEquals(
         'https://foo/?module=bar',
         $this->object->buildDsn());
   }

   /**
    * Tests the construct's host parameter validation
    *
    * @param string $host DBMS host
    *
    * @dataProvider nullAndEmptyProvider
    * @expectedException InvalidArgumentException
    *
    * @return void
    */
   public function testConstructUrlValidation($url)
   {
      new MindFrame2_Dbms_Connection_Rpc('xsim', $url, 'Foo', 'Bar');
   }

   /**
    * Tests the getUsername function.
    *
    * @return void
    */
   public function testGetUsername()
   {
      $this->assertEquals(
         'Foo', $this->object->getUsername());
   }

   /**
    * Tests the getPassword function.
    *
    * @return void
    */
   public function testGetPassword()
   {
      $this->assertEquals(
         'Bar', $this->object->getPassword());
   }
}
