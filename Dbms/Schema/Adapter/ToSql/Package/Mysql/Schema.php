<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * MySQL schema module for the SQL adapter
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
 * MySQL schema module for the SQL adapter
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Dbms_Schema_Adapter_ToSql_Package_Mysql_Schema
   extends MindFrame2_Dbms_Schema_Adapter_ToSql_Package_AbstractSchema
   implements MindFrame2_Dbms_Schema_Adapter_ToSql_Package_SchemaInterface
{
   protected $auto_increment_keyword = 'auto_increment';

   protected $field_type_map = array(
      'bigint' => 'bigint',
      'binary' => 'binary',
      'bit' => 'bit',
      'blob' => 'blob',
      'boolean' => 'tinyint',
      'bool' => 'tinyint',
      'char' => 'char',
      'date' => 'date',
      'datetime' => 'datetime',
      'dec' => 'decimal',
      'decimal' => 'decimal',
      'double' => 'double',
      'enum' => 'enum',
      'float' => 'float',
      'integer' => 'int',
      'int' => 'int',
      'longblob' => 'longblob',
      'longtext' => 'longtext',
      'mediumint' => 'mediumint',
      'meduimblob' => 'meduimblob',
      'meduimtext' => 'meduimtext',
      'set' => 'set',
      'smallint' => 'smallint',
      'text' => 'text',
      'timestamp' => 'timestamp',
      'tinyblob' => 'tinyblob',
      'tinyint' => 'tinyint',
      'tinytext' => 'tinytext',
      'varbinary' => 'varbinary',
      'varchar' => 'varchar',
      'year' => 'year'
   );

   protected $index_keyword = 'KEY';

   protected $index_type_map = array(
      'fulltext' => 'FULLTEXT',
      'index' => NULL,
      'primary' => 'PRIMARY',
      'spatial' => 'SPATIAL',
      'unique' => 'UNIQUE'
   );

   protected $no_length_field_types = array('blob', 'datetime',
      'float', 'longblob', 'longtext', 'mediumblob', 'mediumtext',
      'smallblob', 'smalltext', 'text');

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
      $new_create_table_sql = $this->buildCreateTableSql(NULL, $table_name);

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

   protected function buildCreateTableSqlPrimaryKeyDefinition($table_name)
   {
      $primary_key = $this->getSharedModule()->
         getDatabase()->getTablePrimaryKey($table_name);

      if ($primary_key instanceof MindFrame2_Dbms_Schema_Index)
      {
         return $this->buildPrimaryKeyDefinitionSql($primary_key);
      }

      return FALSE;
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
    * Validates the MYSQL field type
    *
    * @param string $type The field type to validate
    *
    * @return bool
    */
   protected function validateFieldType($type)
   {
      if (defined('self::FIELD_TYPE_' . strtoupper($type)))
      {
         return TRUE;
      }

      return FALSE;
   }

   /**
    * Validates the MYSQL index type
    *
    * @param string $type The index type to validate
    *
    * @return bool
    */
   protected function validateIndexType($type)
   {
      if (empty($type))
      {
         return TRUE;
      }

      if (defined('self::INDEX_TYPE_' . strtoupper($type)))
      {
         return TRUE;
      }

      return FALSE;
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
