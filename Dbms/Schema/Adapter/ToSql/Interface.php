<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Interface for SQL Database Model Adapters
 */

/**
 * Interface for SQL Database Model Adapters
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2009-09-16
 */
interface MindFrame2_Dbms_Schema_Adapter_ToSql_Interface
{
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
   public function buildAlterTableSql($table_name, $create_table_sql);

   /**
    * Builds SQL CREATE statements for the entire database model
    *
    * @return string
    */
   public function buildCreateDatabaseSql();
   
   /**
    * Builds an SQL CREATE TABLE statement for the table specified
    *
    * @param string $table_name Table to create
    *
    * @return string
    */
   public function buildCreateTableSql($table_name);

   /**
    * Builds an SQL DELETE statement out of the database model with the 
    * specified data
    *
    * @param string $table_name Name of the table
    * @param array $delete_data The field values being deleted
    *
    * @return string
    *
    * @throws Exception If the user doesn't have delete permissions on the table
    */
   public function buildDeleteTableSql($table_name, array $delete_data);

   /**
    * Builds SQL DROP statements for the entire database model
    *
    * @return string
    */
   public function buildDropDatabaseSql();

   /**
    * Builds a DROP TEMPORARY TABLE SQL statement for the specified table
    *
    * @param string $table_name Name of the table being created
    *
    * @return string
    */
   public function buildDropTemporaryTableSql($table_name);

   /**
    * Builds the SQL statement for granting full priveleges to the specified 
    * user with the specified password
    *
    * @param string $username Username
    * @param string $password Password
    *
    * @return string
    */
   public function buildGrantAllSql($username, $password);

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
   public function buildInsertAdHocTableSql($table_name, $insert_data);

   /**
    * Builds an SQL INSERT statement out of the database model with the 
    * specified data
    *
    * @param string $table_name Name of the table the data will be inserted into
    * @param array $insert_data The field values being inserted
    *
    * @return string
    */
   public function buildInsertTableSql($table_name, array $insert_data);

   /**
    * Builds the SQL SELECT statement described by the specified query model.
    *
    * @param MindFrame2_Dbms_Query $query Query model
    *
    * @return string
    */
   public function buildQuerySql(MindFrame2_Dbms_Query $query);

   /**
    * Converts an SQL SELECT statement into a statement that will pull from the
    * DDBI merge table.
    *
    * @param string $table_name Merge table to pull from
    * @param string $sql The original SQL SELECT statement
    *
    * @return string
    */
   public function buildSelectDdbiTableSql($table_name, $sql);

   /**
    * Builds an SQL SELECT ... INTO statement (or equivalent)
    *
    * @param string $table_name Name of the table being created
    * @param string $sql Query to create the new table from
    *
    * @return string
    */
   public function buildSelectIntoTemporaryTableSql($table_name, $sql);

   /**
    * Builds an SQL SELECT statement
    *
    * @param string $table_name Name of the table being searched
    * @param array $select_data The field values being searched for
    * @param array $order_by_columns How the results should be sorted
    * @param int $limit How many records to retreive (0 = no limit)
    *
    * @return string
    *
    * @throws RuntimeException If limit is not an integer
    */
   public function buildSelectTableSql(
      $table_name, array $select_data, array $order_by_columns, $limit);

   /**
    * Builds the statement for extracting the CREATE statement for the existing
    * table.
    *
    * @param string $table_name Name of the table
    *
    * @return string
    */
   public function buildShowCreateTableSql($table_name);

   /**
    * Builds an SQL UPDATE statement out of the database model with the 
    * specified data
    *
    * @param string $table_name Name of the table being updated
    * @param array $update_data The field values being updated
    *
    * @return string
    */
   public function buildUpdateTableSql($table_name, array $update_data);
}
