<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Test class for MindFrame2_Dbms_Result
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
 * Test class for MindFrame2_Dbms_Result
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Dbms_ResultTest extends MindFrame2_Test_Abstract
{
   /**
    * @var MindFrame2_Dbms_Dbi_Single
    */
   protected $object;

   /**
    * Sets up the fixture.
    *
    * @return void
    * @throws RuntimeException If there is an error while creating the result
    * fixture
    */
   protected function setUp()
   {
      $dbi = $this->getSingleDbi();

      $dbi->exec(
         'CREATE TABLE MindFrame2_Dbms_Dbi_SingleTest (foo integer, bar);');
      $dbi->exec(
         'INSERT INTO MindFrame2_Dbms_Dbi_SingleTest ' .
         'VALUES (1, "Hello, Alice.");');
      $dbi->exec(
         'INSERT INTO MindFrame2_Dbms_Dbi_SingleTest ' .
         'VALUES (2, "Hello, Eve.");');
      $dbi->exec(
         'INSERT INTO MindFrame2_Dbms_Dbi_SingleTest ' .
         'VALUES (3, "Hello, Bob.");');
      $dbi->exec(
         'INSERT INTO MindFrame2_Dbms_Dbi_SingleTest ' .
         'VALUES (4, "Hello, Zoe.");');

      $this->object = $dbi->query(
         'SELECT * FROM MindFrame2_Dbms_Dbi_SingleTest;', NULL);

      if (!$this->object instanceof MindFrame2_Dbms_Result)
      {
         throw new RuntimeException('Unable to setup result instance');
      }
   }

   /**
    * Data provider for testing the fetch and fetchAll functions
    *
    * @return array
    */
   public function fetchProvider()
   {
      return array(array(array(
         array('foo' => 1, 'bar' => 'Hello, Alice.'),
         array('foo' => 2, 'bar' => 'Hello, Eve.'),
         array('foo' => 3, 'bar' => 'Hello, Bob.'),
         array('foo' => 4, 'bar' => 'Hello, Zoe.'),
      )));
   }

   /**
    * Asserts that the fetch function resturns the expected results
    *
    * @param array $expected Expected results
    *
    * @dataProvider fetchProvider
    *
    * @return void
    */
   public function testFetch(array $expected)
   {
      $count = count($expected);

      for ($key = 0; $key < $count; $key++)
      {
         $this->assertEquals($expected[$key],
            $this->object->fetch(MindFrame2_Dbms_Result::FETCH_ASSOC));
      }
   }

   /**
    * Asserts that the fetchAll function resturns the expected results
    *
    * @param array $expected Expected results
    *
    * @dataProvider fetchProvider
    *
    * @return void
    */
   public function testFetchAll(array $expected)
   {
      $results = $this->object->fetchAll(MindFrame2_Dbms_Result::FETCH_ASSOC);

      $this->assertEquals($expected, $results);
   }

   /**
    * Data provider for the testing the fetchAll(FETCH_COLUMN) and fetchColumn
    * functions
    *
    * @return array
    */
   public function fetchColumnProvider()
   {
      return array(
         array(array(1, 2, 3, 4), 0),
         array(
            array(
               'Hello, Alice.',
               'Hello, Eve.',
               'Hello, Bob.',
               'Hello, Zoe.'
            ), 1
         )
      );
   }

   /**
    * Asserts that the fetchAll function resturns the expected results when the
    * FETCH_COLUMN argument is supplied
    *
    * @param array $expected Expected results
    * @param int $column Column index
    *
    * @dataProvider fetchColumnProvider
    *
    * @return void
    */
   public function testFetchAllFetchColumn($expected, $column)
   {
      $results = $this->object->fetchAll(
         MindFrame2_Dbms_Result::FETCH_COLUMN, $column);

      $this->assertEquals($expected, $results);
   }

   /**
    * Asserts that the fetchColumn function resturns the expected results
    *
    * @param array $expected Expected results
    * @param int $column Column index
    *
    * @dataProvider fetchColumnProvider
    *
    * @return void
    */
   public function testFetchColumn($expected, $column)
   {
      $count = count($expected);

      for ($key = 0; $key < $count; $key++)
      {
         $this->assertEquals($expected[$key],
            $this->object->fetchColumn($column));
      }
   }
}
