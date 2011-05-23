<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 *
 * SQLite data manipulation module for the SQL adapter
 */

/**
 * SQLite data manipulation module for the SQL adapter
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
class MindFrame2_Dbms_Schema_Adapter_ToSql_Package_Sqlite_Data
   extends MindFrame2_Dbms_Schema_Adapter_ToSql_Package_AbstractData
   implements MindFrame2_Dbms_Schema_Adapter_ToSql_Package_DataInterface
{
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
      $sql = sprintf("CREATE TEMPORARY TABLE %s.%s ENGINE=MEMORY\n%s",
         $this->getSharedModule()->escapeDbElementName(
            $this->getSharedModule()->getDatabase()->getName()),
         $this->getSharedModule()->escapeDbElementName($table_name),
         $sql);

      return $sql;
   }

   /**
    * Covers the specified value to the corresponding value that the DBMS is
    * expecting.
    *
    * @param mixed $value Value
    * @param mixed $type Field type
    *
    * @return mixed
    */
   protected function convertFieldValue($value, $type)
   {
      $new_value = $value;

      if (strtolower($type) == 'bit')
      {
         if (is_bool($value))
         {
            $new_value = ($value === TRUE) ? 1 : 0;
         }
      }

      return $value;
   }

   /**
    * Input sanitization
    *
    * @param MindFrame2_Dbms_Schema_Field $field Field model
    * @param string $value The value to be sanitized
    *
    * @return string
    */
   protected function sanitizeUpdateValue(
      MindFrame2_Dbms_Schema_Field $field, $value)
   {
      $sanitized = $value;

      if ((strpos($sanitized, '+') === 0)
         && is_numeric($number = substr($sanitized, 1)))
      {
         $sanitized = sprintf('%s + %d',
            $this->getSharedModule()->escapeDbElementName($field->getName()),
            $number);
      }
      else
      {
         $sanitized = $this->getSharedModule()->sanitizeValue($sanitized);
      }

      return $sanitized;
   }
}
