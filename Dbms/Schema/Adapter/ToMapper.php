<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Adapter for automatically creating mappers out of a database schema model
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
 * Adapter for automatically creating mappers out of a database schema model
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Dbms_Schema_Adapter_ToMapper
   extends MindFrame2_Dbms_Schema_Adapter_Abstract
{
   /**
    * Builds a model for the specified database table
    *
    * @param string $table_name Name of the table for which to build the model
    *
    * @return string
    */
   public function buildTableMapper($table_name,
      $class_prefix, $model_prefix, $tab_spaces)
   {
      MindFrame2_Core::assertArgumentIsInt($tab_spaces, 3, 'tab_spaces');

      $init_method = $this->_buildTableMapperInitMethod(
         $table_name, $model_prefix, $tab_spaces);
      
      // $load_method = $this->
         // _buildTableMapperLoadMethod($table_name, $tab_spaces);
      
      // $write_method = $this->
         // _buildTableMapperWriteMethod($table_name, $tab_spaces);

      return sprintf(
         "class %s%s extends MindFrame2_Dbms_Record_Mapper_Abstract " .
            "\n{\n%s\n}\n",
         $class_prefix,
         $this->adjustClassName($table_name), $init_method);
   }

   private function _buildTableMapperInitMethod($table_name,
      $model_prefix, $tab_spaces)
   {
      $method = sprintf(
         "%sprotected function init()\n%s{\n%s\n%s\n%s}",
          str_repeat(' ', $tab_spaces),
          str_repeat(' ', $tab_spaces),
          sprintf('%s$this->setTableName(\'%s\')',
            str_repeat(' ', $tab_spaces * 2), $table_name),
          sprintf('%s$this->setModelClass(\'%s%s\')',
            str_repeat(' ', $tab_spaces * 2), 
            $model_prefix,
            $this->adjustClassName($table_name)),
          str_repeat(' ', $tab_spaces));

      return $method;
   }
   
   private function _buildTableMapperLoadMethod($table_name,
      $model_prefix, $tab_spaces)
   {
      $fields = $this->getDatabase()->getTableFields($table_name);

      $method = sprintf(
         "%sprotected function init()\n%s{\n%s\n%s\n%s}",
          str_repeat(' ', $tab_spaces),
          str_repeat(' ', $tab_spaces),
          sprintf('%s$this->setTableName(\'%s\')',
            str_repeat(' ', $tab_spaces * 2), $table_name),
          sprintf('%s$this->setModelClass(\'%s%s\')',
            str_repeat(' ', $tab_spaces * 2), 
            $model_prefix,
            $this->adjustClassName($table_name)),
          str_repeat(' ', $tab_spaces));

      return $method;
   }

   protected function adjustClassName($table_name)
   {
      $filtered = str_replace('tbl', NULL, $table_name);
      $filtered = preg_replace("/_(\w)/e", "strtoupper('\\1')", $filtered);

      return ucfirst($filtered);
   }

   protected function adjustMethodName($field_name)
   {
      $filtered = str_replace('_', NULL, $field_name);

      return strtolower($filtered);
   }

   protected function adjustParameterName($field_name)
   {
      $filtered = str_replace('fld', NULL, $field_name);

      return strtolower($filtered);
   }

   protected function adjustPropertyName($field_name)
   {
      $filtered = str_replace('fld', NULL, $field_name);

      return '_' . strtolower($filtered);
   }
}
