<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Abstract test case
 */

/**
 * Abstract test case
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
abstract class MindFrame2_Test_Abstract extends PHPUnit_Framework_TestCase
{
   private $_connection_model;
   private $_database_model;
   private $_dbms_schema_adapter;
   private $_distributed_dbi;
   private $_single_dbi;

   /**
    * Loads the database model from the fixture file
    *
    * @return MindFrame2_Model_Database
    */
   protected function getDatabaseModel()
   {
      if (!$this->_database_model instanceof MindFrame2_Dbms_Schema_Database)
      {
         $factory = new MindFrame2_Dbms_Schema_Builder_FromXml_Database();

         $this->_database_model = $factory->
            loadFromFile('./MindFrame2/Test/fixtures/database.xml');
      }

      return $this->_database_model;
   }

   /**
    * Returns the database model adapter
    *
    * @return MindFrame2_Dbms_Schema_Adapter_ToSql_Interface
    */
   protected function getDbmsSchemaAdapter()
   {
      if (!$this->_dbms_schema_adapter
         instanceof MindFrame2_Dbms_Schema_Adapter_ToSql_Interface)
      {
         $this->_dbms_schema_adapter =
            new MindFrame2_Dbms_Schema_Adapter_ToSql_Sqlite(
               $this->getDatabaseModel(), ':');
      }

      return $this->_dbms_schema_adapter;
   }

   /**
    * Creates a connection model for an SQLite memory instance
    *
    * @return MindFrame2_Dbms_Connection_Interface
    */
   protected function getDbmsConnectionModel()
   {
      if (!$this->_connection_model instanceof MindFrame2_Dbms_Connection_Interface)
      {
         $this->_connection_model = new MindFrame2_Dbms_Connection_File('sqlite', ':memory:');
      }

      return $this->_connection_model;
   }

   /**
    * Returns a single DBI instance
    *
    * @return MindFrame2_Dbms_Dbi_Interface
    */
   protected function getSingleDbi()
   {
      if (!$this->_single_dbi instanceof MindFrame2_Dbms_Dbi_Interface)
      {
         $this->_single_dbi = new MindFrame2_Dbms_Dbi_Single($this->getDbmsConnectionModel());
      }

      return $this->_single_dbi;
   }

   /**
    * Returns a distributed DBI instance
    *
    * @return MindFrame2_Dbms_Dbi_Interface
    */
   protected function getDistributedDbi()
   {
      if (!$this->_distributed_dbi instanceof MindFrame2_Dbms_Dbi_Interface)
      {
         $this->_distributed_dbi = new MindFrame2_Dbms_Dbi_Distributed($this->getDatabaseModelAdapter());
         $this->_distributed_dbi->addDbmsConnection($this->getSingleDbi());
      }

      return $this->_distributed_dbi;
   }

   /**
    * Returns the field delimiter to be used for testing purposes
    *
    * @return string
    */
   protected function getFieldDelimiter()
   {
      return ':';
   }

   /**
    * Provides a NULL parameter and an empty string parameters for testing
    * paramter validation.
    *
    * @return array
    */
   public function nullAndEmptyProvider()
   {
      return array(array(NULL), array(''), array('  '));
   }
}
