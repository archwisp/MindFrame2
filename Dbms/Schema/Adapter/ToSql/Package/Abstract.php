<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 *
 * Abstract implementation of an SQL adapter module
 */

/**
 * Abstract implementation of an SQL adapter module
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
abstract class MindFrame2_Dbms_Schema_Adapter_ToSql_Package_Abstract
{
   /**
    * @var MindFrame2_Dbms_Schema_Adapter_ToSql_Package_SharedInterface
    */
   private $_shared_module;

   /**
    * Construct
    *
    * @param MindFrame2_Dbms_Schema_Adapter_ToSql_Package_SharedInterface
    * $shared_module Shared functionality module
    */
   public function __construct(
      MindFrame2_Dbms_Schema_Adapter_ToSql_Package_SharedInterface $shared_module)
   {
      $this->_shared_module = $shared_module;
   }

   /**
    * Retreives the shared functionality module
    *
    * @return MindFrame2_Dbms_Schema_Adapter_ToSql_Package_SharedInterface
    */
   protected function getSharedModule()
   {
      return $this->_shared_module;
   }
}
