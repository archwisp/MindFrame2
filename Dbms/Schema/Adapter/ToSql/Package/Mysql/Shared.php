<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 *
 * MySQL SELECT module for the SQL adapter
 */

/**
 * MySQL SELECT module for the SQL adapter
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2011-01-24
 */
class MindFrame2_Dbms_Schema_Adapter_ToSql_Package_Mysql_Shared
   extends MindFrame2_Dbms_Schema_Adapter_ToSql_Package_AbstractShared
   implements MindFrame2_Dbms_Schema_Adapter_ToSql_Package_SharedInterface
{
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
      $skel = "GRANT ALL ON %s.* TO %s@'localhost' IDENTIFIED BY %s;";

      $sql = sprintf($skel,
         $this->escapeDbElementName($this->getDatabase()->getName()),
         $this->sanitizeValue($username),
         $this->sanitizeValue($password));

      return $sql;
   }

   /**
    * Escapes database object names for avoideance of reserved word naming
    * collisions
    *
    * @param string $name Element name to be escaped
    *
    * @return string
    */
   public function escapeDbElementName($name)
   {
      return '`'. $name . '`';
   }

   /**
    * Input sanitization
    *
    * @param string $value The value to be sanitized
    *
    * @return string
    */
   public function sanitizeValue($value)
   {
      $sanitized = $value;

      if (trim($sanitized) === '')
      {
         $sanitized = 'NULL';
      }
      elseif (!is_int($sanitized))
      {
         $sanitized = "'" . mysql_escape_string($sanitized) . "'";
      }

      return $sanitized;
   }
}
