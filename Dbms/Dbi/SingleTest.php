<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Test class for MindFrame2_Dbms_Dbi_Single
 */

/**
 * Test class for MindFrame2_Dbms_Dbi_Single
 *
 * @author Bryan C. Geraghty <bryan@ravensight.org>
 * @since 2010-12-14
 */
class MindFrame2_Dbms_Dbi_SingleTest extends MindFrame2_Test_Abstract
{
   /**
    * @var MindFrame2_Dbms_Dbi_Single
    */
   protected $object;

   /**
    * Sets up the fixture.
    *
    * @return void
    */
   protected function setUp()
   {
      $this->object = $this->getSingleDbi();

      $this->object->exec(
         'CREATE TABLE MindFrame2_Dbms_Dbi_SingleTest (foo integer, bar);');
   }

   /**
    * Asserts that insert statements return the correct number of rows affected
    *
    * @return void
    */
   public function testInsert()
   {
      $result = $this->object->exec(
         'INSERT INTO MindFrame2_Dbms_Dbi_SingleTest VALUES (1, "Hello, World.");');

      $this->assertEquals(1, $result);
   }

   /**
    * Asserts that the query function returns a MindFrame2_Dbms_Result object
    *
    * @return void
    */
   public function testSelect()
   {
      $result = $this->object->query(
         'SELECT * FROM MindFrame2_Dbms_Dbi_SingleTest;', NULL);

      $this->assertTrue($result instanceof MindFrame2_Dbms_Result);

      $this->testInsert();

      $result = $this->object->query(
         'SELECT * FROM MindFrame2_Dbms_Dbi_SingleTest;', NULL);

      $this->assertTrue($result instanceof MindFrame2_Dbms_Result);
   }

   /**
    * Asserts that update statements return the correct number of rows affected
    *
    * @return void
    */
   public function testUpdate()
   {
      $this->testInsert();

      $result = $this->object->exec(
         'UPDATE MindFrame2_Dbms_Dbi_SingleTest SET bar = "Bleh" WHERE foo = 0;');

      $this->assertEquals(0, $result);

      $result = $this->object->exec(
         'UPDATE MindFrame2_Dbms_Dbi_SingleTest SET foo = 2 WHERE foo = 1;');

      $this->assertEquals(1, $result);

      $this->testInsert();

      $result = $this->object->exec(
         'UPDATE MindFrame2_Dbms_Dbi_SingleTest SET bar = "Goodbye, World";');

      $this->assertEquals(2, $result);
   }

   /**
    * Asserts that delete statements return the correct number of rows affected
    *
    * @return void
    */
   public function testDelete()
   {
      $result = $this->object->exec(
         'DELETE FROM MindFrame2_Dbms_Dbi_SingleTest;');

      $this->assertEquals(0, $result);

      $this->testInsert();

      $result = $this->object->exec(
         'DELETE FROM MindFrame2_Dbms_Dbi_SingleTest WHERE foo = 1;');

      $this->assertEquals(1, $result);

      $this->testInsert();
      $this->testInsert();

      $result = $this->object->exec(
         'DELETE FROM MindFrame2_Dbms_Dbi_SingleTest;');

      $this->assertEquals(2, $result);
   }
}
