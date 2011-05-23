<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Test class for MindFrame2_Dbms_Connection_File
 */

/**
 * Test class for MindFrame2_Dbms_Connection_File
 *
 * @author Bryan C. Geraghty <bryan@ravensight.org>
 */
class MindFrame2_Dbms_Connection_FileTest extends MindFrame2_Test_Abstract
{
   /**
    * @var MindFrame2_Dbms_Connection_File
    */
   protected $object;

   /**
    * Sets up the fixture.
    *
    * @return void
    */
   protected function setUp()
   {
      $this->object = new MindFrame2_Dbms_Connection_File('sqlite', '/foo/bar.db');
   }

   /**
    * Tests the buildDsn function.
    *
    * @return void
    */
   public function testBuildDsn()
   {
      $this->assertEquals(
         'sqlite:/foo/bar.db',
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
      new MindFrame2_Dbms_Connection_File($dbms, '/foo/bar.db');
   }

   /**
    * Tests the construct's file parameter validation
    *
    * @param string $file Database file
    *
    * @dataProvider nullAndEmptyProvider
    * @expectedException InvalidArgumentException
    *
    * @return void
    */
   public function testConstructFileValidation($file)
   {
      new MindFrame2_Dbms_Connection_File('sqlite', $file);
   }
}
