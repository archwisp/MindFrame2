<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * MySQL SELECT module for the SQL adapter
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
 * MySQL SELECT module for the SQL adapter
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
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

   public function escapeInput($value)
   {
      return mysql_escape_string($value);
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
         $sanitized = "'" . $this->escapeInput($sanitized) . "'";
      }

      return $sanitized;
   }
}
