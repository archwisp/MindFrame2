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
class MindFrame2_Dbms_Schema_Adapter_ToSql_Package_Mysql_Select
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
}
