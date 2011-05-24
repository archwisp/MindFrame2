<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Abstract implementation of SQL adapter data functionality module
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
 * Abstract implementation of SQL adapter data functionality module
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
abstract class MindFrame2_Dbms_Schema_Adapter_ToSql_Package_AbstractData
   extends MindFrame2_Dbms_Schema_Adapter_ToSql_Package_Abstract
   implements MindFrame2_Dbms_Schema_Adapter_ToSql_Package_DataInterface
{
   /**
    * Input sanitization
    *
    * @param MindFrame2_Dbms_Schema_Field $field Field model
    * @param string $value The value to be sanitized
    *
    * @return string
    */
   protected abstract function sanitizeUpdateValue(
      MindFrame2_Dbms_Schema_Field $field, $value);

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
   public function buildDeleteTableSql($table_name, array $delete_data)
   {
      $sql = sprintf("DELETE FROM %s.%s\nWHERE\n  %s;",
         $this->getSharedModule()->escapeDbElementName(
            $this->getSharedModule()->getDatabase()->getName()),
         $this->getSharedModule()->escapeDbElementName($table_name),
         $this->_buildUpdateTableSqlWhereClause($table_name, $delete_data));

      return $sql;
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
      $skel = "INSERT INTO %s.%s\nVALUES(%s);";

      $sql = sprintf($skel,
         $this->getSharedModule()->escapeDbElementName(
            $this->getSharedModule()->getDatabase()->getName()),
         $this->getSharedModule()->escapeDbElementName($table_name),
         $this->_buildInsertAdHocTableSqlValuesClause(
            $table_name, $insert_data));

      return $sql;
   }

   /**
    * Builds an SQL INSERT statement out of the database model with the
    * specified data
    *
    * @param string $table_name Name of the table the data will be inserted into
    * @param array $insert_data The field values being inserted
    *
    * @return string
    *
    * @throws Exception If the user doesn't have insert permissions
    */
   public function buildInsertTableSql($table_name, array $insert_data)
   {
      $sql = sprintf("INSERT INTO %s.%s\n(%s)\nVALUES(%s);",
         $this->getSharedModule()->escapeDbElementName(
            $this->getSharedModule()->getDatabase()->getName()),
         $this->getSharedModule()->escapeDbElementName($table_name),
         $this->_buildInsertTableSqlFieldClause($table_name),
         $this->_buildInsertTableSqlValuesClause($table_name, $insert_data));

      return $sql;
   }

   /**
    * Builds an SQL UPDATE statement out of the database model with the
    * specified data
    *
    * @param string $table_name Name of the table being updated
    * @param array $update_data The field values being updated
    *
    * @return string
    *
    * @throws Exception If the user doesn't have update permissions on the table
    */
   public function buildUpdateTableSql($table_name, array $update_data)
   {
      $sql = sprintf("UPDATE %s.%s \nSET \n  %s \nWHERE \n  %s;",
         $this->getSharedModule()->escapeDbElementName(
            $this->getSharedModule()->getDatabase()->getName()),
         $this->getSharedModule()->escapeDbElementName($table_name),
         $this->_buildUpdateTableSqlSetClause($table_name, $update_data),
         $this->_buildUpdateTableSqlWhereClause($table_name, $update_data));

      return $sql;
   }

   public function buildTruncateTableSql($table_name)
   {
      $sql = sprintf("TRUNCATE TABLE %s.%s;",
         $this->getSharedModule()->escapeDbElementName(
            $this->getSharedModule()->getDatabase()->getName()),
         $this->getSharedModule()->escapeDbElementName($table_name));

      return $sql;
   }

   /**
    * Builds the values clause of an SQL INSERT statement without consulting the
    * database model
    *
    * @param string $table_name Name of the table the data will be inserted into
    * @param array $insert_data The field values being inserted
    *
    * @return string
    */
   private function _buildInsertAdHocTableSqlValuesClause(
      $table_name, array $insert_data)
   {
      $sql = array();

      foreach ($insert_data as $field_name => $field_value)
      {
         $field_name = $table_name .
            $this->getSharedModule()->getFieldDelimiter() . $field_name;

         $sql[] = $this->getSharedModule()->sanitizeValue($field_value);
      }
      // end foreach // ($inset_field_names as $field_name) //

      $sql = join(', ', $sql);

      return $sql;
   }

   /**
    * Builds the field clause of an SQL INSERT statement from the database model
    *
    * @param string $table_name Name of the table the data will be inserted into
    *
    * @return string
    */
   private function _buildInsertTableSqlFieldClause($table_name)
   {
      $fields = $this->getSharedModule()->
         getDatabase()->getTableFields($table_name);

      $sql = array();

      foreach ($fields as $field)
      {
         if (!$field->getIsAutoIncrement())
         {
            $sql[] = $this->getSharedModule()->
               escapeDbElementName($field->getName());
         }
         // end if // (!$field->getIsAutoIncrement()) //
      }
      // end foreach // ($fields as $field) //

      $sql = join(', ', $sql);

      return $sql;
   }

   /**
    * Builds the values clause of an SQL INSERT statement
    *
    * @param string $table_name Name of the table the data will be inserted into
    * @param array $insert_data The field values being inserted
    *
    * @return string
    */
   private function _buildInsertTableSqlValuesClause(
      $table_name, array $insert_data)
   {
      $fields = $this->getSharedModule()->
         getDatabase()->getTableFields($table_name);

      $sql = array();

      foreach ($fields as $field)
      {
         $field_name = $table_name
            . $this->getSharedModule()->getFieldDelimiter() . $field->getName();

         $field_value = array_key_exists($field_name, $insert_data)
            ? $insert_data[$field_name] : NULL;

         if (!$field->getIsAutoIncrement())
         {
            $field_value = $this->convertFieldValue(
               $field_value, $field->getType());

            $sql[] = $this->getSharedModule()->sanitizeValue($field_value);
         }
         // end if // (!$field->getIsAutoIncrement()) //
      }
      // end foreach // ($inset_field_names as $field_name) //

      $sql = join(', ', $sql);

      return $sql;
   }

   /**
    * Builds the SET clause of an SQL UPDATE statement
    *
    * @param string $table_name Name of the table being updated
    * @param array $update_data The field values being updated
    *
    * @return string
    *
    * @throws RunTimeException If no fields have been specified for updating
    */
   private function _buildUpdateTableSqlSetClause(
      $table_name, array $update_data)
   {
      $fields = $this->getSharedModule()->
         getDatabase()->getTableFields($table_name);

      $sql = array();

      foreach ($fields as $field)
      {
         $field_name = $table_name
            . $this->getSharedModule()->getFieldDelimiter() . $field->getName();

         if (!$field->getIsAutoIncrement()
            && array_key_exists($field_name, $update_data))
         {
            $field_value = $update_data[$field_name];

            $field_value = $this->convertFieldValue(
               $field_value, $field->getType());

            $field_value = $this->sanitizeUpdateValue(
               $field, $field_value);

            if (substr($field_value, 0, 1) == '+')
            {
               $field_value = $this->getSharedModule()->escapeDbElementName(
                  $field->getName()) . $field_value;
            }

            $sql[] = sprintf('%s = %s',
               $this->getSharedModule()->escapeDbElementName($field->getName()),
               $field_value);
         }
         // end if // (!$field->getIsAutoIncrement()) //
      }
      // end foreach // ($fields as $field) //

      $sql = join(", \n  ", $sql);

      if (empty($sql))
      {
         throw new RunTimeException('No fields to update');
      }

      return $sql;
   }

   /**
    * Builds the WHERE clause of an SQL UPDATE statement. Returns FALSE if
    * no primary key is defined.
    *
    * @param string $table_name Name of the table being updated
    * @param array $update_data The field values being updated
    *
    * @return string or FALSE
    *
    * @throws RunTimeException If the primary key field values are empty
    */
   private function _buildUpdateTableSqlWhereClause(
      $table_name, array $update_data)
   {
      $primary_key = $this->getSharedModule()->
         getDatabase()->getTablePrimaryKey($table_name);

      if (!$primary_key instanceof MindFrame2_Dbms_Schema_Index)
      {
         return FALSE;
      }

      $pk_fields = $primary_key->getFields();

      $sql = array();

      foreach ($pk_fields as $pk_field)
      {
         $field_name = $pk_field->getName();

         $input_field_name = $table_name
            . $this->getSharedModule()->getFieldDelimiter() . $field_name;

         $field_value = isset($update_data[$input_field_name])
            ? $update_data[$input_field_name] : NULL;

         $field_value = $this->convertFieldValue(
            $field_value, $pk_field->getType());

         if (is_null($field_value))
         {
            throw new RunTimeException(
               sprintf('Primary key fields cannot be NULL on update (%s)',
               $input_field_name));
         }

         $sql[] = sprintf('%s = %s',
            $this->getSharedModule()->escapeDbElementName($field_name),
            $this->getSharedModule()->sanitizeValue($field_value));
      }
      // end foreach // ($pk_field_names as $field_name) //

      $sql = join(' AND ', $sql);

      return $sql;
   }
}
