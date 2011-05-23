<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Abstract Database Model Adapter for SQL
 */

/**
 * Abstract Database Model Adapter for SQL
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
abstract class MindFrame2_Dbms_Schema_Adapter_ToSql_Abstract
   extends MindFrame2_Dbms_Schema_Adapter_Abstract
   implements MindFrame2_Dbms_Schema_Adapter_ToSql_Interface
{
   const INTEGRITY_CONSTRIANT_VIOLATION = 23000;

   /**
    * Loads the modules associated with the initialized abstraction.
    *
    * @return void
    */
   protected abstract function loadPackageModules();

   /**
    * Builds the object
    *
    * @param MindFrame2_Dbms_Schema_Database $database The database model to be
    * adapted
    * @param string $field_delimiter Delimiter used to separate tables and
    * fields
    */
   public function __construct(
      MindFrame2_Dbms_Schema_Database $database, $field_delimiter)
   {
      parent::__construct($database, $field_delimiter);
      $this->loadPackageModules();
   }

   /**
    * Builds an SQL ALTER TABLE satement which would convert the table
    * specified by $create_table_sql into the specifications of the database
    * model.
    *
    * @param string $table_name Name of the table
    * @param string $create_table_sql Eisting table definition
    *
    * @return string
    */
   public function buildAlterTableSql($table_name, $create_table_sql)
   {
      return $this->_schema_module->
         buildAlterTableSql($table_name, $create_table_sql);
   }

   /**
    * Builds SQL CREATE statements for the entire database model
    *
    * @return string
    */
   public function buildCreateDatabaseSql()
   {
      return $this->_schema_module->buildCreateDatabaseSql();
   }

   /**
    * Builds an SQL CREATE TABLE statement for the table specified
    *
    * @param string $table_name Table to create
    *
    * @return string
    */
   public function buildCreateTableSql($database_name, $table_name)
   {
      return $this->_schema_module->buildCreateTableSql($database_name, $table_name);
   }

   /**
    * Builds an SQL DELETE statement out of the database model with the
    * specified data
    *
    * @param string $table_name Name of the table
    * @param array $delete_data The field values being deleted
    *
    * @return string
    */
   public function buildDeleteTableSql($table_name, array $delete_data)
   {
      return $this->_data_module->
         buildDeleteTableSql($table_name, $delete_data);
   }

   /**
    * Builds SQL DROP statements for the entire database model
    *
    * @return string
    */
   public function buildDropDatabaseSql()
   {
      return $this->_schema_module->buildDropDatabaseSql();
   }

   /**
    * Builds a DROP TEMPORARY TABLE SQL statement for the specified table
    *
    * @param string $table_name Name of the table being created
    *
    * @return string
    */
   public function buildDropTemporaryTableSql($table_name)
   {
      return $this->_schema_module->buildDropTemporaryTableSql($table_name);
   }

   /**
    * Builds the SQL statement for granting full priveleges to the specified
    * user with the specified password
    *
    * @param string $username Username
    * @param string $password Password
    *
    * @return string
    */
   public function buildGrantAllSql($username, $password)
   {
      return $this->_shared_module->buildGrantAllSql($username, $password);
   }

   /**
    * Builds an SQL INSERT statement for an ad-hoc table. The database model is
    * not utilized for table name or field name validation so be careful that
    * it is only used with input from a query that has already been validated.
    *
    * @param string $table_name Table to insert the data into
    * @param array $insert_data Data to be inserted
    *
    * @return string
    */
   public function buildInsertAdHocTableSql($table_name, $insert_data)
   {
      return $this->_data_module->
         buildInsertAdHocTableSql($table_name, $insert_data);
   }

   /**
    * Builds an SQL INSERT statement out of the database model with the
    * specified data
    *
    * @param string $table_name Name of the table the data will be inserted into
    * @param array $insert_data The field values being inserted
    *
    * @return string
    */
   public function buildInsertTableSql($table_name, array $insert_data)
   {
      return $this->_data_module->
         buildInsertTableSql($table_name, $insert_data);
   }

   /**
    * Builds the SQL SELECT statement described by the specified query model.
    *
    * @param MindFrame2_Dbms_Query $query Query model
    *
    * @return string
    */
   public function buildQuerySql(MindFrame2_Dbms_Query $query)
   {
      return $this->_query_module->buildQuerySql($query);
   }

   /**
    * Converts an SQL SELECT statement into a statement that will pull from the
    * DDBI merge table.
    *
    * @param string $table_name Merge table to pull from
    * @param string $sql The original SQL SELECT statement
    *
    * @return string
    */
   public function buildSelectDdbiTableSql($table_name, $sql)
   {
      return $this->_select_module->buildSelectDdbiTableSql($table_name, $sql);
   }

   /**
    * Builds an SQL SELECT ... INTO statement (or equivalent)
    *
    * @param string $table_name Name of the table being created
    * @param string $sql Query to create the new table from
    *
    * @return string
    */
   public function buildSelectIntoTemporaryTableSql($table_name, $sql)
   {
      return $this->_data_module->
         buildSelectIntoTemporaryTableSql($table_name, $sql);
   }

   /**
    * Builds an QL SELECT statement for the specified table in the
    * primary database.
    *
    * @param string $table_name Name of the table being searched
    * @param array $select_data The field values being searched for
    * @param array $order_by_columns How the results should be sorted
    * @param int $limit How many records to retreive (0 = no limit)
    *
    * @return string
    */
   public function buildSelectTableSql($table_name,
      array $select_data, array $order_by_columns, $limit)
   {
      return $this->_select_module->buildSelectTableSql(
         $table_name, $select_data, $order_by_columns, $limit);
   }

   /**
    * Builds the statement for extracting the CREATE statement for the existing
    * table.
    *
    * @param string $table_name Name of the table
    *
    * @return string
    */
   public function buildShowCreateTableSql($table_name)
   {
      return $this->_schema_module->buildShowCreateTableSql($table_name);
   }

   public function buildTruncateTableSql($table_name)
   {
      return $this->_data_module->buildTruncateTableSql($table_name);
   }

   /**
    * Builds an SQL UPDATE statement out of the database model with the
    * specified data
    *
    * @param string $table_name Name of the table being updated
    * @param array $update_data The field values being updated
    *
    * @return string
    */
   public function buildUpdateTableSql($table_name, array $update_data)
   {
      return $this->_data_module->
         buildUpdateTableSql($table_name, $update_data);
   }

   /**
    * Sets the schema module
    *
    * @param MindFrame2_Dbms_Schema_Adapter_ToSql_Package_SchemaInterface
    * $schema_module Schema module
    *
    * @return void
    */
   protected function setSchemaModule(
      MindFrame2_Dbms_Schema_Adapter_ToSql_Package_SchemaInterface $schema_module)
   {
      $this->_schema_module = $schema_module;
   }

   /**
    * Sets the data module
    *
    * @param MindFrame2_Dbms_Schema_Adapter_ToSql_Package_DataInterface
    * $data_module Data module
    *
    * @return void
    */
   protected function setDataModule(
      MindFrame2_Dbms_Schema_Adapter_ToSql_Package_DataInterface $data_module)
   {
      $this->_data_module = $data_module;
   }

   /**
    * Sets the query module
    *
    * @param MindFrame2_Dbms_Schema_Adapter_ToSql_Package_QueryInterface
    * $select_module Query module
    *
    * @return void
    */
   protected function setQueryModule(
      MindFrame2_Dbms_Schema_Adapter_ToSql_Package_QueryInterface $select_module)
   {
      $this->_query_module = $select_module;
   }

   /**
    * Sets the select module
    *
    * @param MindFrame2_Dbms_Schema_Adapter_ToSql_Package_SelectInterface
    * $select_module Select module
    *
    * @return void
    */
   protected function setSelectModule(
      MindFrame2_Dbms_Schema_Adapter_ToSql_Package_SelectInterface $select_module)
   {
      $this->_select_module = $select_module;
   }

   /**
    * Sets the shared module
    *
    * @param MindFrame2_Dbms_Schema_Adapter_ToSql_Package_SharedInterface
    * $shared_module Shared module
    *
    * @return void
    */
   protected function setSharedModule(
      MindFrame2_Dbms_Schema_Adapter_ToSql_Package_SharedInterface $shared_module)
   {
      $this->_shared_module = $shared_module;
   }
}
