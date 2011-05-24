<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * SQLite SELECT module for the SQL adapter
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
 * SQLite SELECT module for the SQL adapter
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Dbms_Schema_Adapter_ToSql_Package_Sqlite_Select
   extends MindFrame2_Dbms_Schema_Adapter_ToSql_Package_AbstractSelect
   implements MindFrame2_Dbms_Schema_Adapter_ToSql_Package_SelectInterface
{
   /**
    * Wraps the field name with a conversion for instances where the data would
    * be otherwise un-usable (e.g. binary).
    *
    * @param mixed $value The value to be converted
    * @param string $type The type the value is to be converted to
    *
    * @return string or FALSE
    */
   protected function buildSelectTableSqlConvertFieldTypeSql($value, $type)
   {
      if (strtolower($type) == 'bit')
      {
         return sprintf('BIN(%s)', $value);
      }

      return FALSE;
   }

   /**
    * Select statement input sanitization
    *
    * @param string $value The value to be sanitized
    *
    * @return string
    */
   protected function sanitizeSelectValue($value)
   {
      $sanitized = $value;

      if ($sanitized === 'NULL')
      {
         $sanitized = 'IS NULL';
      }
      elseif ($sanitized === 'NOT NULL')
      {
         $sanitized = 'IS NOT NULL';
      }
      elseif (in_array(substr($value, 0, 2), array('> ', '< ')))
      {
         $sanitized = substr($value, 0, 2)
            . $this->getSharedModule()->sanitizeValue(substr($value, 2));
      }
      elseif (in_array(substr($value, 0, 3), array('>= ', '<= ')))
      {
         $sanitized = substr($value, 0, 3)
            . $this->getSharedModule()->sanitizeValue(substr($value, 3));
      }
      elseif (substr($value, 0, 7) == 'BETWEEN')
      {
         list($begin, $end) = explode(' AND ', substr($value, 8));

         $sanitized = sprintf('BETWEEN %s AND %s',
            $this->getSharedModule()->sanitizeValue($begin),
            $this->getSharedModule()->sanitizeValue($end));
      }
      elseif (!is_numeric($sanitized))
      {
         if (strpos($sanitized, ',') !== FALSE)
         {
            $values = explode(',', $sanitized);
            $values = array_map('trim', $values);
            $values = array_map(
               array($this->getSharedModule(), 'sanitizeValue'), $values);

            $sanitized = sprintf('IN (%s)', join(', ', $values));
         }
         elseif (strpos($sanitized, '*') !== FALSE)
         {
            $sanitized = 'LIKE ' . $this->getSharedModule()->
               sanitizeValue(str_replace('*', '%', $sanitized));
         }
         else
         {
            $sanitized = "= '" . sqlite_escape_string($sanitized) . "'";
         }
      }
      else
      {
         $sanitized = "= '" . sqlite_escape_string($sanitized) . "'";
      }

      return $sanitized;
   }
}
