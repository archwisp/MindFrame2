<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Unit test for MindFrame2_Dbms_Schema_Adapter_ToSql_Sqlite
 */

/**
 * Unit test for MindFrame2_Dbms_Schema_Adapter_ToSql_Sqlite
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
class MindFrame2_Dbms_Schema_Adapter_ToSql_SqliteTest extends MindFrame2_Test_Abstract
{
   private $_instance;

   private $_expected_sql;

   /**
    * Sets up the testing environment
    *
    * @return void
    */
   protected function setUp()
   {
      $this->_instance = new MindFrame2_Dbms_Schema_Adapter_ToSql_Sqlite(
         $this->getDatabaseModel(), $this->getFieldDelimiter());

      $this->_loadExpectedSql();
   }

   /**
    * Loads the expected SQL statements from the fixture file
    *
    * @return void
    */
   private function _loadExpectedSql()
   {
      $this->_expected_sql = file('./MindFrame2/Test/fixtures/sqlite-expected.sql');
   }

   /**
    * Extracts the expected statement result for the specified test
    *
    * @param string $test_name Name of the test
    *
    * @return string
    */
   private function _getExpectedSql($test_name)
   {
      $sql = NULL;
      $begin = NULL;
      $signature = sprintf('-- //* %s *//', $test_name);
      $line_count = count($this->_expected_sql);

      foreach ($this->_expected_sql as $index => $line)
      {
         if ((strpos($line, $signature) === 0)
            && (trim($this->_expected_sql[$index + 1]) === ''))
         {
            $begin = $index + 2;
         }
         elseif (is_int($begin) && (trim($line) === '')
            && ((($index + 1) === $line_count)
               || strpos($this->_expected_sql[$index + 1], '-- //* ') === 0))
         {
            // Remove the last line-break because it's not actually part of the
            // SQL statement

            $sql = substr($sql, 0, -1);
            break;
         }
         // end elseif // (is_int($begin) && (trim($line) === '') ... //

         if (is_int($begin) && $index >= $begin)
         {
            $sql .= $line;
         }
         // end if // (is_int($begin) && $index >= $begin) //
      }
      // end foreach // ($this->_expected_sql as $index => $line) //

      return $sql;
   }

   /**
    * Unit test for buildCreateDatabaseSql()
    *
    * @return void
    */
   public function testBuildCreateDatabaseSql()
   {

      $expected = $this->_getExpectedSql('testBuildCreateDatabaseSql');

      $this->assertEquals($expected,
         $this->_instance->buildCreateDatabaseSql());
   }

   /**
    * Unit test for buildDeleteTableSql()
    *
    * @return void
    */
   public function testBuildDeleteTableSql()
   {
      $expected = $this->_getExpectedSql('testBuildDeleteTableSql');

      $result = $this->_instance->buildDeleteTableSql(
         'User', array('User:User_Id' => '1'));

      $this->assertEquals($expected, $result);
   }

   /**
    * Unit test for buildDeleteTableSql()
    *
    * @return void
    */
   public function testBuildDeleteTableSqlNoPrimaryKeyException()
   {
      $this->setExpectedException('RuntimeException');

      $this->assertEquals("Doesn't matter",
         $this->_instance->buildDeleteTableSql('User', array()));
   }

   /**
    * Unit test for buildDropDatabaseSql()
    *
    * @return void
    */
   public function testBuildDropDatabaseSql()
   {
      $expected = $this->_getExpectedSql('testBuildDropDatabaseSql');
      $this->assertEquals($expected, $this->_instance->buildDropDatabaseSql());
   }

   /**
    * Unit test for buildDropTemporaryTableSql()
    *
    * @return void
    */
   public function testBuildDropTemporaryTableSql()
   {
      $expected = $this->_getExpectedSql('testBuildDropTemporaryTableSql');

      $this->assertEquals($expected,
         $this->_instance->buildDropTemporaryTableSql('#Test'));
   }

   /**
    * Unit test for buildGrantAllSql()
    *
    * @return void
    */
   public function testBuildGrantAllSql()
   {
      $expected = $this->_getExpectedSql('testBuildGrantAllSql');

      $this->assertEquals($expected,
         $this->_instance->buildGrantAllSql('User', 'Pass'));
   }

   /**
    * Unit test for buildInsertTableSql()
    *
    * @return void
    */
   public function testBuildInsertTableSql()
   {
      $expected = $this->_getExpectedSql('testBuildInsertTableSql');

      $insert_data = array(
         'User:Username' => 'Test',
         'User:Last_Login' => '2010-01-01 22:55:33',
         'User:Login_Count' => 8);

      $result = $this->_instance->buildInsertTableSql('User', $insert_data);

      $this->assertEquals($expected, $result);
   }

   /**
    * Unit test for buildSelectIntoTemporaryTableSql()
    *
    * @return void
    */
   public function testBuildSelectIntoTemporaryTableSql()
   {
      $expected = $this->_getExpectedSql(
         'testBuildSelectIntoTemporaryTableSql');

      $result = $this->_instance->buildSelectIntoTemporaryTableSql(
         '#Test', 'SELECT Col1, Col2 FROM Test;');

      $this->assertEquals($expected, $result);
   }

   /**
    * Unit test for buildSelectTableSql()
    *
    * @return void
    */
   public function testBuildSelectTableSql()
   {
      $expected = $this->_getExpectedSql('testBuildSelectTableSql');

      $this->assertEquals($expected,
         $this->_instance->buildSelectTableSql('User',
         array(
            'User:User_Id' => '<= 100',
            'User:Username' => "Test's",
            'User:Email_Address' => 54.66,
            'User:Last_Login' => 'BETWEEN 2010-01-01 AND 2010-01-15',
            'User:Login_Count' => '> 5',
            'User:Status' => 'Active, 5, Inactive',
            'User:Fk_User_Id_Supervisor' => 'NULL',
            'Fk_Supervisor:Username' => 'Test*',
         ),
         array(
            'User:Username' => 'ASC'
         ), 1));
   }

   /**
    * Unit test for buildInsertAdHocTableSql()
    *
    * @return void
    *
    * @todo Implement testBuildInsertAdHocTableSql().
    */
   public function testBuildInsertAdHocTableSql()
   {
      // Remove the following lines when you implement this test.
      $this->markTestIncomplete('This test has not been implemented yet.');
   }

   /**
    * Unit test for buildSelectDbiTableSql()
    *
    * @return void
    *
    * @todo Implement testBuildSelectDdbiTableSql().
    */
   public function testBuildSelectDdbiTableSql()
   {
      // Remove the following lines when you implement this test.
      $this->markTestIncomplete('This test has not been implemented yet.');
   }

   /**
    * Unit test for buildUpdateTableSql()
    *
    * @return void
    *
    * @todo Implement testBuildUpdateTableSql().
    */
   public function testBuildUpdateTableSql()
   {
      // Remove the following lines when you implement this test.
      $this->markTestIncomplete('This test has not been implemented yet.');
   }
}
