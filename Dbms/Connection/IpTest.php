<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Test class for MindFrame2_Dbms_Connection_Ip
 */

/**
 * Test class for MindFrame2_Dbms_Connection_Ip
 *
 * @author Bryan C. Geraghty <bryan@ravensight.org>
 */
class MindFrame2_Dbms_Connection_IpTest extends MindFrame2_Test_Abstract
{
   /**
    * @var MindFrame2_Dbms_Connection_Ip
    */
   protected $object;

   /**
    * Sets up the fixture.
    *
    * @return void
    */
   protected function setUp()
   {
      $this->object = new MindFrame2_Dbms_Connection_Ip(
        'mysql', 'foobar', 3306, 'Foo', 'Bar');
   }

   /**
    * Tests the buildDsn function.
    *
    * @return void
    */
   public function testBuildDsn()
   {
      $this->assertEquals(
         'mysql:host=foobar;port=3306',
         $this->object->buildDsn());
   }

   /**
    * Tests the construct's dbms parameter validation
    *
    * @param string $dbms DBMS identifier
    *
    * @dataProvider nullAndEmptyProvider
    * @expectedException InvalidArgumentException
    *
    * @return void
    */
   public function testConstructDbmsValidation($dbms)
   {
      new MindFrame2_Dbms_Connection_Ip(
         $dbms, 'foobar', 3306, 'Foo', 'Bar');
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
   public function testConstructHostValidation($host)
   {
      new MindFrame2_Dbms_Connection_Ip(
         'mysql', $host, 3306, 'Foo', 'Bar');
   }

   /**
    * Tests the construct's port parameter validation
    *
    * @param int $port DBMS port
    *
    * @dataProvider nullAndEmptyProvider
    * @expectedException InvalidArgumentException
    *
    * @return void
    */
   public function testConstructPortValidation($port)
   {
      new MindFrame2_Dbms_Connection_Ip(
         'mysql', 'foo', $port, 'Foo', 'Bar');
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
