<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Test class for MindFrame2_Dbms_Connection_File
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
 * Test class for MindFrame2_Dbms_Connection_File
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
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
