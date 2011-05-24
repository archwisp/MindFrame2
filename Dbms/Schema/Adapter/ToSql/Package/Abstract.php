<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Abstract implementation of an SQL adapter module
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
 * Abstract implementation of an SQL adapter module
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
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
      MindFrame2_Dbms_Schema_Adapter_ToSql_Package_SharedInterface
      $shared_module)
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
