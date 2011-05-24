<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Database model adapter for MYSQL.
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
 * Database model adapter for MYSQL.
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Dbms_Schema_Adapter_ToSql_Mysql
   extends MindFrame2_Dbms_Schema_Adapter_ToSql_Abstract
{
   const CODE_DUPLICATE_ENTRY = 1062;
   const CODE_TABLE_DOESNT_EXIST = 1146;

   /**
    * Loads the modules associated with the initialized abstraction.
    *
    * @return void
    */
   protected function loadPackageModules()
   {
      $this->setSharedModule($shared = new
         MindFrame2_Dbms_Schema_Adapter_ToSql_Package_Mysql_Shared(
         $this->getDatabase(), $this->getFieldDelimiter()));

      $this->setDataModule(
         new MindFrame2_Dbms_Schema_Adapter_ToSql_Package_Mysql_Data($shared));

      $this->setQueryModule(
         new MindFrame2_Dbms_Schema_Adapter_ToSql_Package_Mysql_Query($shared));

      $this->setSchemaModule(
         new MindFrame2_Dbms_Schema_Adapter_ToSql_Package_Mysql_Schema($shared));

      $this->setSelectModule(
         new MindFrame2_Dbms_Schema_Adapter_ToSql_Package_Mysql_Select($shared));
   }
}
