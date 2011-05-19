<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Database model
 */

/**
 * Database model
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2008-10-15
 */
class MindFrame2_Dbms_Schema_Database
{
   /**
    * @var string
    */
   private $_name;

   /**
    * @var array
    */
   private $_tables = array();

   /**
    * Construct
    *
    * @param string $name Name of the table
    */
   public function __construct($name)
   {
      $this->_name = $name;
   }

   /**
    * Adds a table object to the collection
    *
    * @param MindFrame2_Dbms_Schema_Table $table Table model
    *
    * @return void
    */
   public function addTable(MindFrame2_Dbms_Schema_Table $table)
   {
      $this->_tables[$table->getName()] = $table;
   }

   /**
    * Returns the name of the table
    *
    * @return string
    */
   public function getName()
   {
      return $this->_name;
   }

   /**
    * Returns the tables in the collection
    *
    * @return array
    */
   public function getTables()
   {
      return $this->_tables;
   }

   public function getTableNames()
   {
      $table_names = array();

      foreach ($this->getTables() as $table)
      {
         $table_names[] = $table->getName();
      }

      return $table_names;
   }

   /**
    * Returns the table object with the specified name
    *
    * @param string $name The name of the table object to retreive
    *
    * @return array
    *
    * @throws InvalidArgumentException If the specified table is not defined in 
    * the database model
    */
   public function getTableByName($name)
   {
      if (!array_key_exists($name, $this->_tables))
      {
         throw new InvalidArgumentException(
            'Table could not be found by name: ' . $name);
      }

      return $this->_tables[$name];
   }
   
   /**
    * Returns the collection of field objects from the specified table in the
    * database model.
    *
    * @param string $table_name The table for which to return the fields
    *
    * @return array
    */
   public function getTableFields($table_name)
   {
      return $this->getTableByName($table_name)->getFields();
   }

   /**
    * Returns the collection of foreign key objects from the specified table in
    * the database model.
    *
    * @param string $table_name The table for which to return the foreign keys
    *
    * @return array
    */
   public function getTableForeignKeyFieldNames($table_name)
   {
      $foreign_keys = $this->getTableForeignKeys($table_name);
      $field_names = array();

      foreach ($foreign_keys as $foreign_key)
      {
         $field_names = array_merge(
            $field_names, $foreign_key->getForeignKeyFieldNames());
      }

      return $field_names;
   }

   /**
    * Returns the collection of foreign key objects from the specified table in
    * the database model.
    *
    * @param string $table_name The table for which to return the foreign keys
    *
    * @return array
    */
   public function getTableForeignKeys($table_name)
   {
      return $this->getTableByName($table_name)->getForeignKeys();
   }

   /**
    * Returns the collection of index objects from the specified table in the
    * database model.
    *
    * @param string $table_name The table for which to return the indexes
    *
    * @return array
    */
   public function getTableIndexes($table_name)
   {
      return $this->getTableByName($table_name)->getIndexes();
   }

   /**
    * Fetches the primary key object for the specified table
    *
    * @param string $table_name The table for which to retreive the primary key
    *
    * @return MindFrame2_Dbms_Schema_Index or FALSE
    */
   public function getTablePrimaryKey($table_name)
   {
      return $this->getTableByName($table_name)->getPrimaryKey();
   }
   
   /**
    * Returns an array containing the names of the fields defined as the primary
    * key for the specified table.
    *
    * @param string $table_name The table for which to retreive the primary key
    *
    * @return array or FALSE
    */
   public function getTablePrimaryKeyFieldNames($table_name)
   {
      $key = $this->getTableByName($table_name)->getPrimaryKey();

      if ($key instanceof MindFrame2_Dbms_Schema_Index)
      {
         return $key->getFieldNames();
      }

      return FALSE;
   }
}
