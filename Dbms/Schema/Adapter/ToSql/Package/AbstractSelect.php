<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Interface for SQL adapter SELECT modules
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
 * Interface for SQL adapter SELECT modules
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
abstract class MindFrame2_Dbms_Schema_Adapter_ToSql_Package_AbstractSelect
   extends MindFrame2_Dbms_Schema_Adapter_ToSql_Package_Abstract
   implements MindFrame2_Dbms_Schema_Adapter_ToSql_Package_SelectInterface
{
   /**
    * Wraps the field name with a conversion for instances where the data would
    * be otherwise un-usable (e.g. binary).
    *
    * @param mixed $value The value to be converted
    * @param string $type The field type
    *
    * @return string or FALSE
    */
   protected abstract function buildSelectTableSqlConvertFieldTypeSql(
      $value, $type);

   /**
    * Select statement input sanitization
    *
    * @param string $value The value to be sanitized
    *
    * @return string
    */
   protected abstract function sanitizeSelectValue($value);

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
      $qualified_table = sprintf('%s.%s',
         $this->getSharedModule()->escapeDbElementName(
            $this->getSharedModule()->getDatabase()->getName()),
         $this->getSharedModule()->escapeDbElementName($table_name));

      // Remove JOIN and WHERE statements because we are now selecting against
      // the merged data which already has those filters applied.

      $sql = preg_replace('/\n^.*?JOIN.*?\n.*?$/m', '', $sql);
      $sql = preg_replace('/\n^WHERE\n^.*?$((\n^\s+AND.*$)?)+/m', '', $sql);

      // Change aliased column names to the alias only because that is how they
      // will be named in the merge table.

      $sql = preg_replace('/^(\s+).*?AS (.*?)/m', '\1\2', $sql);

      // Change all of the fully qualified names into the relative name because
      // that is how they will be named in the merge table. This will affect
      // the FROM statement also, be we will fix that with the next statement.

      $sql = preg_replace(
         '/(.*?\s)[`\[\]].*?[`\[\]]\.([`\[\]].*?[`\[\]])/m', '\1\2', $sql);

      // Change the FROM statement to the fully-qualified merge table.

      $sql = preg_replace('/(FROM\n\s+).*$/m', '\1' . $qualified_table, $sql);

      return $sql;
   }

   /**
    * Converts an SQL SELECT statement into a statement that will pull from the
    * DDBI merge table.
    *
    * @param string $database_suffix Merge table to pull from
    * @param string $sql The original SQL SELECT statement
    *
    * @return string
    */
   public function buildSelectDdbiReplicaTableSql($database_suffix, $sql)
   {
      $sql = preg_replace(
         '/(FROM\n\s+.*?)(`\..*)/', '\1' . $database_suffix . '\2', $sql);

      return $sql;
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
      array $select_data, array $order_by_columns, $offset, $limit)
   {                                             
      return $this->_buildSelectDatabaseTableSql(
         $this->getSharedModule()->getDatabase()->getName(),
         $table_name, $select_data, $order_by_columns, $offset, $limit);
   }

   /**
    * Builds the field index which is used to uniquely identify the foreign key
    * label field.
    *
    * @param MindFrame2_Dbms_Schema_ForeignKey $foreign_key Foreign key object model
    *
    * @return string
    */
   private function _buildForeignKeyLabelFieldIndex(
      MindFrame2_Dbms_Schema_ForeignKey $foreign_key)
   {
      return sprintf('%s%s%s',
         $foreign_key->getName(),
         $this->getSharedModule()->getFieldDelimiter(),
         $foreign_key->getLabelField()->getName());
   }

   /**
    * Builds an SQL SELECT statement for the specified table in the specified
    * database.
    *
    * @param string $database_name Name of the database being searched
    * @param string $table_name Name of the table being searched
    * @param array $select_data The field values being searched for
    * @param array $order_by_columns How the results should be sorted
    * @param int $limit How many records to retreive (0 = no limit)
    *
    * @return string
    *
    * @throws Exception If the user doesn't have select permissions on the table
    */
   private function _buildSelectDatabaseTableSql($database_name, $table_name,
      array $select_data, array $order_by_columns, $offset, $limit)
   {
      MindFrame2_Validate::argumentIsNotEmpty($database_name, 1, 'database_name');
      MindFrame2_Validate::argumentIsNotEmpty($table_name, 2, 'table_name');
      MindFrame2_Validate::argumentIsInt($offset, 4, 'offset');
      MindFrame2_Validate::argumentIsInt($limit, 4, 'limit');

      $skel = "SELECT\n  %s\nFROM\n  %s.%s\n%s%s%s%s;";

      $sql = sprintf($skel,
         $this->_buildSelectTableSqlFieldClause($table_name),
         $this->getSharedModule()->escapeDbElementName($database_name),
         $this->getSharedModule()->escapeDbElementName($table_name),
         $this->_buildSelectTableSqlJoinClause(
            $this->getSharedModule()->getDatabase()->getName(), $table_name),
         $this->_buildSelectTableSqlWhereClause($table_name, $select_data),
         $this->_buildSelectTableSqlOrderByClause(
            $table_name, $order_by_columns),
         $this->_buildSelectTableSqlLimitClause($offset, $limit));

      return $sql;
   }

   /**
    * Builds the field clause portion of an SQL SELECT statement
    *
    * @param string $table_name Name of the table being searched
    *
    * @return string
    */
   private function _buildSelectTableSqlFieldClause($table_name)
   {
      $fields = $this->getSharedModule()->
         getDatabase()->getTableFields($table_name);

      $sql = array();

      foreach ($fields as $field)
      {
         $qualified_field = sprintf('%s.%s',
            $this->getSharedModule()->escapeDbElementName($table_name),
            $this->getSharedModule()->escapeDbElementName($field->getName()));

         $converted = $this->buildSelectTableSqlConvertFieldTypeSql(
            $qualified_field, $field->getType());

         if ($converted !== FALSE)
         {
            $qualified_field = sprintf('%s AS %s',
               $converted, $field->getName());
         }

         $sql[] = $qualified_field;
      }
      // end foreach // ($fields as $field) //

      $foreign_keys = $this->getSharedModule()->
         getDatabase()->getTableForeignKeys($table_name);

      foreach ($foreign_keys as $foreign_key)
      {
         $pk_table_name = $foreign_key->getName();
         $label_field_name = $foreign_key->getLabelField()->getName();

         $sql[] = sprintf('%s.%s AS %s',
            $this->getSharedModule()->escapeDbElementName($pk_table_name),
            $this->getSharedModule()->escapeDbElementName($label_field_name),
            $this->getSharedModule()->escapeDbElementName(
               $this->_buildForeignKeyLabelFieldIndex($foreign_key)));
      }
      // end foreach // ($foreign_keys as $foreign_key) //

      $sql = join(",\n  ", $sql);

      return $sql;
   }

   /**
    * Builds the JOIN clause for an SQL SELECT statement. The joins built from
    * defined foreign keys
    *
    * @param string $database_name Name of the database in which the table being
    * joined resides
    * @param string $table_name Name of the table being searched
    *
    * @return string
    */
   private function _buildSelectTableSqlJoinClause($database_name, $table_name)
   {
      $foreign_keys = $this->getSharedModule()->
         getDatabase()->getTableForeignKeys($table_name);

      $sql = array();

      foreach ($foreign_keys as $foreign_key)
      {
         $sql[] = sprintf("LEFT OUTER JOIN\n  %s.%s AS %s ON %s",
            $this->getSharedModule()->escapeDbElementName($database_name),
            $this->getSharedModule()->escapeDbElementName(
               $foreign_key->getPrimaryKeyTable()->getName()),
            $this->getSharedModule()->
               escapeDbElementName($foreign_key->getName()),
            $this->_buildSelectTableSqlJoinClauseOnDefinition(
               $table_name, $foreign_key));
      }
      // end foreach // ($foreign_keys as $foreign_key) //

      $sql = join("\n", $sql);

      return $sql;
   }

   /**
    * Builds the ON definition for the JOIN portion of an SQL SELECT statement
    *
    * @param string $table_name Name of the table being searched
    * @param MindFrame2_Dbms_Schema_ForeignKey $foreign_key Foreign key model
    *
    * @return string
    */
   private function _buildSelectTableSqlJoinClauseOnDefinition(
      $table_name, MindFrame2_Dbms_Schema_ForeignKey $foreign_key)
   {
      $fk_field_names = $foreign_key->getForeignKeyFieldNames();
      $pk_field_names = $foreign_key->getPrimaryKeyFieldNames();
      $field_count = count($fk_field_names);

      $sql = array();

      for ($x = 0; $x < $field_count; $x++)
      {
         $sql[] = sprintf("%s.%s = %s.%s",
            $this->getSharedModule()->
               escapeDbElementName($table_name),
            $this->getSharedModule()->
               escapeDbElementName($fk_field_names[$x]),
            $this->getSharedModule()->
               escapeDbElementName($foreign_key->getName()),
            $this->getSharedModule()->
               escapeDbElementName($pk_field_names[$x]));
      }

      $sql = join(" AND ", $sql);

      return $sql;
   }

   /**
    * Builds the WHERE clause portion of an SQL SELECT statement
    *
    * @param string $table_name Name of the table being searched
    * @param array $select_data The field values being searched for
    *
    * @return string
    */
   private function _buildSelectTableSqlWhereClause(
      $table_name, array $select_data)
   {
      $fields = $this->getSharedModule()->
         getDatabase()->getTableFields($table_name);

      $sql = array();

      foreach ($fields as $field)
      {
         $input_field_name = $table_name . $this->getSharedModule()->
            getFieldDelimiter() . $field->getName();
                                                     
         if (array_key_exists($input_field_name,$select_data))
         {
            $field_value = $select_data[$input_field_name];

            $sql[] = sprintf('%s.%s %s',
               $this->getSharedModule()->escapeDbElementName($table_name),
               $this->getSharedModule()->escapeDbElementName($field->getName()),
               $this->sanitizeSelectValue($field_value));
         }
         // end if // (array_key_exists($field_name, $select_data)) //
      }
      // end foreach // ($fields as $field) //

      $foreign_keys = $this->getSharedModule()->
         getDatabase()->getTableForeignKeys($table_name);

      foreach ($foreign_keys as $foreign_key)
      {
         $fk_name = $foreign_key->getName();
         $field_name = $foreign_key->getLabelField()->getName();

         $input_field_name = $fk_name . $this->getSharedModule()->
            getFieldDelimiter() . $field_name;

         if (!empty($select_data[$input_field_name]))
         {
            $field_value = $select_data[$input_field_name];

            $sql[] = sprintf('%s.%s %s',
               $this->getSharedModule()->escapeDbElementName($fk_name),
               $this->getSharedModule()->escapeDbElementName($field_name),
               $this->sanitizeSelectValue($field_value));
         }
         // end if // (array_key_exists($field_name, $select_data)) //
      }
      // end foreach // ($foreign_keys as $foreign_key) //

      return empty($sql) ? NULL : "\nWHERE\n  " . join("\n  AND ", $sql);
   }

   /**
    * Builds the ORDER BY clause portion of an SQL SELECT statement
    *
    * @param string $table_name Name of the table being searched
    * @param array $order_by_columns How the results should be sorted
    *
    * @return string
    *
    * @throws InvalidArgumentException If an order argument is invald
    */
   private function _buildSelectTableSqlOrderByClause(
      $table_name, array $order_by_columns)
   {                          
      $fields = $this->getSharedModule()->
         getDatabase()->getTableFields($table_name);

      $sql = array();

      foreach ($fields as $field)
      {
         $input_field_name = $table_name . $this->getSharedModule()->
            getFieldDelimiter() . $field->getName();

         if (isset($order_by_columns[$input_field_name]))
         {
            $direction = $order_by_columns[$input_field_name];

            if (!in_array($direction, array('ASC', 'DESC')))
            {
               throw new InvalidArgumentException('Invalid sort argument');
            }

            $sql[] = sprintf('%s.%s %s',
               $this->getSharedModule()->escapeDbElementName($table_name),
               $this->getSharedModule()->escapeDbElementName($field->getName()),
               strtoupper($direction));
         }
         // end if // (array_key_exists($field_name, $select_data)) //
      }
      // end foreach // ($fields as $field) //

      $sql = join(", ", $sql);

      if (!empty($sql))
      {
         $sql = "\nORDER BY " . $sql;
      }

      return $sql;
   }

   /**
    * Builds the LIMIT clause for an SQL SELECT statement
    *
    * @param int $limit How many records to retreive
    *
    * @return string
    */
   private function _buildSelectTableSqlLimitClause($offset,$limit)
   {
      if ($limit !== 0)
      {
         $sql = sprintf("\nLIMIT %d, %d",$offset, $limit);
      }
      else
      {
         $sql = NULL;
      }

      return $sql;
   }

}
