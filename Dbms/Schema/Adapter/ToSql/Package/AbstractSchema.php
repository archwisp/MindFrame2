<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Abstract implementation of SQL adapter schema functionality module
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
 * Abstract implementation of SQL adapter schema functionality module
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
abstract class MindFrame2_Dbms_Schema_Adapter_ToSql_Package_AbstractSchema
   extends MindFrame2_Dbms_Schema_Adapter_ToSql_Package_Abstract
   implements MindFrame2_Dbms_Schema_Adapter_ToSql_Package_SchemaInterface
{
   protected $auto_increment_keyword;
   protected $field_type_map = array();
   protected $index_keyword;
   protected $index_type_map = array();
   protected $no_length_field_types = array();

   /**
    * Builds SQL CREATE statements for the entire database model
    *
    * @return string
    */
   public function buildCreateDatabaseSql()
   {
      $database_name = $this->getSharedModule()->getDatabase()->getName();
      $table_sql = array();

      foreach ($this->getSharedModule()->getDatabase()->getTables() as $table)
      {
         $table_sql[] = $this->buildCreateTableSql(NULL, $table->getName());
      }
      // end foreach // ($this->getSharedModule()-> ... as $table) //

      $sql = array();

      $create_skel = 'CREATE DATABASE %s;';
      $use_skel = 'USE %s;';

      $escaped_name = $this->getSharedModule()->
         escapeDbElementName($database_name);

      $sql[] = sprintf($create_skel, $escaped_name);
      $sql[] = sprintf($use_skel, $escaped_name);

      $sql = array_merge($sql, $table_sql);

      $sql = join("\n\n", $sql);

      return $sql;
   }

   /**
    * Builds SQL DROP statements for the entire database model
    *
    * @return string
    */
   public function buildDropDatabaseSql()
   {
      $sql = sprintf('DROP DATABASE %s;',
         $this->getSharedModule()->escapeDbElementName(
            $this->getSharedModule()->getDatabase()->getName()));

      return $sql;
   }

   /**
    * Builds an SQL CREATE TABLE statement for the table specified. If the
    * database parameter is NULL, the table name will not be fully-qualified
    * (MySQL behavior).
    *
    * @param string $table_name Table to create
    *
    * @return string
    */
   public function buildCreateTableSql($database_name, $table_name)
   {
      $fields = $this->buildCreateTableSqlFieldDefinitions($table_name);
      $primary_key = $this->buildCreateTableSqlPrimaryKeyDefinition($table_name);

      if ($primary_key !== FALSE)
      {
         $fields[] = $primary_key;
      }

      $indexes = $this->buildCreateTableSqlIndexDefinitions($table_name);

      if (!empty($indexes))
      {
         $fields = array_merge($fields, $indexes);
      }

      if (!is_null($database_name))
      {
         $database_name = $this->getSharedModule()->
            escapeDbElementName($database_name). '.';
      }

      $sql = sprintf("CREATE TABLE %s%s (\n%s\n);",
         $database_name,
         $this->getSharedModule()->escapeDbElementName($table_name),
         join(",\n", $fields));

      return $sql;
   }

   protected function buildCreateTableSqlFieldDefinitions($table_name)
   {
      $fields = $this->getSharedModule()
         ->getDatabase()->getTableFields($table_name);

      $field_definitions = array();

      foreach ($fields as $field)
      {
         $field_definitions[] = $this->buildFieldDefinitionSql($field);
      }

      return $field_definitions;
   }

   protected function buildCreateTableSqlIndexDefinitions($table_name)
   {
      $indexes = $this->getSharedModule()->
         getDatabase()->getTableIndexes($table_name);

      $index_definitions = array();

      foreach ($indexes as $index)
      {
         $index_definitions[] = $this->buildTableLevelIndexDefinitionSql($index);
      }

      return $index_definitions;
   }

   protected function buildCreateTableSqlPrimaryKeyDefinition($table_name)
   {
      $primary_key = $this->getSharedModule()->
         getDatabase()->getTablePrimaryKey($table_name);

      if ($primary_key instanceof MindFrame2_Dbms_Schema_Index)
      {
         // If the primary key is an auto-increment field, it will be specified
         // in the field definition itself so we don't want to redefine it.

         $pk_fields = $primary_key->getFields();

         if (count($pk_fields) === 1)
         {
            $pk_field = reset($pk_fields);

            if ($pk_field->getIsAutoIncrement() === FALSE)
            {
               return $this->buildPrimaryKeyDefinitionSql($primary_key);
            }
            // end if // ($pk_field->getIsAutoIncrement() === FALSE) //
         }
         // end if // (count($pk_fields) === 1) //
      }

      return FALSE;
   }

   /**
    * Builds the SQL definition for an index
    *
    * @param MindFrame2_Dbms_Schema_Index $index Model object used to build the
    * SQL definition
    *
    * @return string
    *
    * @throws InvalidArgumentException If the index type is not supported
    */
   protected function buildDatabaseLevelIndexDefinitionSql(
      $table_name, MindFrame2_Dbms_Schema_Index $index)
   {
      $type = $index->getType();
      $mapped_type = (empty($type)) ? NULL : $this->mapIndexType($type);

      $type_sql = !empty($mapped_type)
         ? $mapped_type . ' ' . $this->index_keyword : $this->index_keyword;

      $sql = sprintf('  %s %s.%s ON %s (%s)',
         $type_sql,
         $this->getSharedModule()->escapeDbElementName(
            $this->getSharedModule()->getDatabase()->getName()),
         $this->getSharedModule()->escapeDbElementName($index->getName()),
         $this->getSharedModule()->escapeDbElementName($table_name),
         $this->buildIndexDefinitionFieldsSql($index));

      return $sql;
   }

   /**
    * Builds the auto-increment portion of an SQL field definition
    *
    * @param MindFrame2_Dbms_Schema_Field $field Model object used to create the SQL
    * definition
    *
    * @return string
    */
   protected function buildFieldAutoIncrementSql(MindFrame2_Dbms_Schema_Field $field)
   {
      return $field->getIsAutoIncrement() ? " $this->auto_increment_keyword" : NULL;
   }

   /**
    * Builds the default value portion of an SQL field definition
    *
    * @param MindFrame2_Dbms_Schema_Field $field Model object used to create the SQL
    * definition
    *
    * @return string
    */
   protected function buildFieldDefaultValueSql(MindFrame2_Dbms_Schema_Field $field)
   {
      $sql = NULL;
      $default_value = $field->getDefaultValue();

      if (!is_null($default_value) && $default_value !== '')
      {
         if (!is_numeric($default_value))
         {
            $default_value = "'". $default_value ."'";
         }
         $sql = " DEFAULT ". $default_value;
      }

      return $sql;
   }

   /**
    * Builds the field definition portion of an SQL statement
    *
    * @param MindFrame2_Dbms_Schema_Field $field Model object used to create the SQL
    * definition
    *
    * @return string
    */
   protected function buildFieldDefinitionSql(MindFrame2_Dbms_Schema_Field $field)
   {
      $skel = "  %s %s %s%s%s";
      $sql = sprintf($skel,
         $this->getSharedModule()->escapeDbElementName($field->getName()),
         $this->buildFieldTypeSql($field),
         $this->buildFieldNullableSql($field),
         $this->buildFieldDefaultValueSql($field),
         $this->buildFieldAutoIncrementSql($field));

      return $sql;
   }

   /**
    * Builds the NULL or not NULL portion of an SQL field definition
    *
    * @param MindFrame2_Dbms_Schema_Field $field Model object used to create the SQL
    * definition
    *
    * @return string
    */
   protected function buildFieldNullableSql(MindFrame2_Dbms_Schema_Field $field)
   {
      $sql = ($field->getAllowNull()) ? NULL : 'NOT NULL';

      if (is_null($sql))
      {
         $default = $field->getDefaultValue();
         $sql = empty($default) ? 'DEFAULT NULL' : 'NULL';
      }

      return $sql;
   }

   /**
    * Builds the field type and length portion of an SQL field definition
    *
    * @param MindFrame2_Dbms_Schema_Field $field Model object used to create the SQL
    * definition
    *
    * @return string
    *
    * @throws InvalidArgumentException If the type agument is not defined as a
    * class TYPE constant
    */
   protected function buildFieldTypeSql(MindFrame2_Dbms_Schema_Field $field)
   {
      $type = $field->getType();

      if (($mapped_type = $this->mapFieldType($type)) === FALSE)
      {
         throw new InvalidArgumentException(sprintf(
            'Unknown type "%s" for field "%s"', $type, $field->getName()));
      }

      $sql = $mapped_type;

      if (!in_array(strtolower($type), $this->_getNoLengthFieldTypes()))
      {
         $sql   .= '(' . $field->getLength() . ')';
      }

      return $sql;
   }

   /**
    * Builds the SQL definition for an index
    *
    * @param MindFrame2_Dbms_Schema_Index $index Model object used to build the
    * SQL definition
    *
    * @return string
    *
    * @throws InvalidArgumentException If the index type is not supported
    */
   protected function buildTableLevelIndexDefinitionSql(MindFrame2_Dbms_Schema_Index $index)
   {
      $type = $index->getType();
      $mapped_type = (empty($type)) ? NULL : $this->mapIndexType($type);

      $type_sql = !empty($mapped_type)
         ? $mapped_type . ' ' . $this->index_keyword : $this->index_keyword;

      $sql = sprintf('  %s %s (%s)',
         $type_sql,
         $this->getSharedModule()->escapeDbElementName($index->getName()),
         $this->buildIndexDefinitionFieldsSql($index));

      return $sql;
   }

   /**
    * Builds the field list portion of an SQL index definition
    *
    * @param MindFrame2_Dbms_Schema_Index $index Model object used to build the SQL
    * definition
    *
    * @return string
    */
   protected function buildIndexDefinitionFieldsSql(
      MindFrame2_Dbms_Schema_Index $index)
   {
      $sql = array();

      foreach ($index->getFields() as $field)
      {
         $sql[] = $this->getSharedModule()->
            escapeDbElementName($field->getName());
      }

      $sql = join(',', $sql);

      return $sql;
   }

   /**
    * Builds the SQL definition for a primary key index
    *
    * @param MindFrame2_Dbms_Schema_Index $primary_key Model object used to build
    * the SQL definition
    *
    * @return string
    */
   protected function buildPrimaryKeyDefinitionSql(
      MindFrame2_Dbms_Schema_Index $primary_key)
   {
      $sql = sprintf('  PRIMARY KEY (%s)',
         $this->buildIndexDefinitionFieldsSql($primary_key));

      return $sql;
   }

   /**
    * Maps the SQLite field type
    *
    * @param string $type The field type to map
    *
    * @return bool
    */
   protected function mapFieldType($type)
   {
      $lower_type = strtolower($type);

      if (array_key_exists($lower_type, $this->field_type_map))
      {
         return $this->field_type_map[$lower_type];
      }

      return FALSE;
   }

   /**
    * Maps the SQLite index type
    *
    * @param string $type The index type to map
    *
    * @return bool
    */
   protected function mapIndexType($type)
   {
      $lower_type = strtolower($type);

      if (!array_key_exists($lower_type, $this->index_type_map))
      {
         throw new InvalidArgumentException(
            sprintf('Unknown index type "%s"', $type));
      }

      return $this->index_type_map[$lower_type];
   }

   /**
    * Returns the field types that shouldn't have a length specified
    *
    * @return array
    */
   private function _getNoLengthFieldTypes()
   {
      return $this->no_length_field_types;
   }
}
