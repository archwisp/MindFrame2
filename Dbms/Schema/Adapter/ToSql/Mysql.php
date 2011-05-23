<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Database model adapter for MYSQL.
 */

/**
 * Database model adapter for MYSQL.
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
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
