<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * SQLite schema module for the SQL adapter
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
 * SQLite schema module for the SQL adapter
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Dbms_Schema_Adapter_ToSql_Package_Sqlite_Schema
   extends MindFrame2_Dbms_Schema_Adapter_ToSql_Package_AbstractSchema
   implements MindFrame2_Dbms_Schema_Adapter_ToSql_Package_SchemaInterface
{
   protected $auto_increment_keyword = 'PRIMARY KEY AUTOINCREMENT';

   protected $field_type_map = array(
      'bigint' => 'INTEGER',
      'binary' => 'INTEGER',
      'bit' => 'INTEGER',
      'blob' => 'BLOB',
      'boolean' => 'INTEGER',
      'bool' => 'INTEGER',
      'char' => 'TEXT',
      'date' => 'TEXT',
      'datetime' => 'TEXT',
      'timestamp' => 'TEXT',
      'decimal' => 'REAL',
      'dec' => 'REAL',
      'double' => 'REAL',
      'enum' => 'TEXT',
      'float' => 'REAL',
      'integer' => 'INTEGER',
      'int' => 'INTEGER',
      'longblob' => 'LONGBLOB',
      'longtext' => 'TEXT',
      'mediumint' => 'INTEGER',
      'meduimblob' => 'MEDUIMBLOB',
      'meduimtexT' => 'TEXT',
      'set' => 'TEXT',
      'smallint' => 'INTEGER',
      'text' => 'TEXT',
      'timestamp' => 'TEXT',
      'tinyblob' => 'TINYBLOB',
      'tinyint' => 'INTEGER',
      'tinytext' => 'TEXT',
      'varbinary' => 'INTEGER',
      'varchar' => 'TEXT',
      'year' => 'TEXT'
   );

   protected $index_keyword = 'INDEX';

   protected $index_type_map = array(
      'index' => NULL,
      'primary' => 'PRIMARY',
      'unique' => 'UNIQUE'
   );

   /**
    * Builds an SQL ALTER TABLE satement which would convert the table specified
    * by $create_table_sql into the specifications of the database model.
    *
    * @param string $table_name Name of the table
    * @param string $create_table_sql Eisting table definition
    *
    * @return string
    */
   public function buildAlterTableSql($table_name, $create_table_sql)
   {
      $new_create_table_sql = $this->buildCreateTableSql($table_name);

      $old_field_names =
         $this->_extractElementNamesFromCreateTableSql($create_table_sql);
      $new_field_names =
         $this->_extractElementNamesFromCreateTableSql($new_create_table_sql);

      $drops = array_diff($old_field_names, $new_field_names);
      $adds = array_diff($new_field_names, $old_field_names);

      $changes = array_diff(
         explode("\n", $new_create_table_sql),
         explode("\n", $create_table_sql));

      array_pop($changes);

      if (count($changes) === 0 && count($drops) === 0)
      {
         return FALSE;
      }

      $alter_table_sql = sprintf("ALTER TABLE %s.%s\n",
         $this->getSharedModule()->escapeDbElementName(
            $this->getSharedModule()->getDatabase()->getName()),
         $this->getSharedModule()->escapeDbElementName($table_name));

      $delimiter = NULL;

      foreach ($drops as $drop)
      {
         $alter_table_sql .= sprintf('%s  DROP COLUMN %s',
            $delimiter, $this->getSharedModule()->escapeDbElementName($drop));
      }

      if (count($drops) !== 0)
      {
         $delimiter = ",\n";
      }

      foreach ($changes as $change)
      {
         $field_name = $this->_extractElementNameFromDefinition($change);

         if ($field_name === 'PRIMARY KEY')
         {
            $alter_table_sql .= sprintf(
               "%s  DROP PRIMARY KEY,\n  ADD %s",
               $delimiter, trim($change));
         }
         else
         {
            if (in_array($field_name, $adds))
            {
               $operation = 'ADD';
            }
            else
            {
               $operation = 'MODIFY';
            }
            // end else // if (in_array($field_name, $adds)) //

            $alter_table_sql .= sprintf('%s  %s COLUMN %s',
               $delimiter, $operation, trim(substr($change, 0, -1)));
         }
         // end else // if $field_name === 'PRIMARY KEY') //

         $delimiter = ",\n";
      }
      // end foreach // ($changes as $change) //

      $alter_table_sql .= "\n;";

      return $alter_table_sql;
   }

   /**
    * Builds SQL CREATE statements for the entire database model
    *
    * @return string
    */
   public function buildCreateDatabaseSql()
   {
      $database_name = $this->getSharedModule()->getDatabase()->getName();

      $sql = array();

      $sql[] = sprintf('ATTACH DATABASE ":memory:" AS %s;',
         $this->getSharedModule()->escapeDbElementName($database_name));

      foreach ($this->getSharedModule()->getDatabase()->getTables() as $table)
      {
         $sql[] = $this->buildCreateTableSql($database_name, $table->getName());
      }
      // end foreach // ($this->getSharedModule()-> ... as $table) //

      $sql = join("\n\n", $sql);

      return $sql;
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
      $fields = $this->buildCreateTableSqlFieldDefinitions($table_name);
      $primary_key = $this->buildCreateTableSqlPrimaryKeyDefinition($table_name);

      if ($primary_key !== FALSE)
      {
         $fields[] = $primary_key;
      }

      if (!is_null($database_name))
      {
         $database_name = $this->getSharedModule()->
            escapeDbElementName($database_name) . '.';
      }

      $sql = sprintf("CREATE TABLE %s%s (\n%s\n);",
         $database_name,
         $this->getSharedModule()->escapeDbElementName($table_name),
         join(",\n", $fields));

      $indexes = $this->buildCreateTableSqlIndexDefinitions($table_name);

      foreach ($indexes as $index)
      {
         $sql .= "\n" . sprintf("CREATE %s;", trim($index));
      }

      return $sql;
   }

   protected function buildCreateTableSqlIndexDefinitions($table_name)
   {
      $indexes = $this->getSharedModule()->
         getDatabase()->getTableIndexes($table_name);

      $index_definitions = array();

      foreach ($indexes as $index)
      {
         $index_definitions[] = $this->buildDatabaseLevelIndexDefinitionSql($table_name, $index);
      }

      return $index_definitions;
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
      $sql = sprintf('DROP TEMPORARY TABLE %s.%s;',
         $this->getSharedModule()->escapeDbElementName(
            $this->getSharedModule()->getDatabase()->getName()),
         $this->getSharedModule()->escapeDbElementName($table_name));

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

      return $sql;
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
      return sprintf('SHOW CREATE TABLE %s.%s;',
         $this->getSharedModule()->escapeDbElementName(
            $this->getSharedModule()->getDatabase()->getName()),
         $this->getSharedModule()->escapeDbElementName($table_name));
   }

   /**
    * Extracts the field names from the given CREATE TABLE SQL statement
    *
    * @param string $create_table_sql SQL statement
    *
    * @return array
    */
   private function _extractElementNamesFromCreateTableSql($create_table_sql)
   {
      $lines = explode("\n", $create_table_sql);
      $field_names = array();

      foreach ($lines as $index => $line)
      {
         if ($index === 0 || $index === count($lines) -1)
         {
            continue;
         }
         // end if // ($index === 0 || $index === count($lines) -1) //

         $field_names[] = $this->_extractElementNameFromDefinition($line);
      }
      // end foreach // ($lines as $index => $line) //

      return $field_names;
   }

   /**
    * Extracts the field name from an SQL field definition
    *
    * @param string $definition SQL field definition
    *
    * @return string
    */
   private function _extractElementNameFromDefinition($definition)
   {
      if (strpos($definition, 'PRIMARY KEY') !== FALSE)
      {
         $name = 'PRIMARY KEY';
      }
      else
      {
         $parts = explode('`', $definition);
         $name = $parts[1];
      }

      return $name;
   }
}
