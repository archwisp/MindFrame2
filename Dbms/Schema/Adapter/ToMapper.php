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
      
      $load_method = $this->_buildTableMapperLoadMethod(
         $table_name, $model_prefix, $tab_spaces);
      
      // $write_method = $this->
         // _buildTableMapperWriteMethod($table_name, $tab_spaces);

      $class = sprintf(
         "class %s%s extends MindFrame2_Dbms_Record_Mapper_Abstract " .
            "\n{\n%s\n\n%s\n}\n",
         $class_prefix,
         $this->adjustClassName($table_name), $init_method, $load_method);

      $file_name = MindFrame2_AutoLoad::convertClassToPath(
         $class_prefix . $this->adjustClassName($table_name));

      $file_header = sprintf("<?php // vim:ts=%s:sts=%s:sw=%s:et:\n\n",
         $tab_spaces, $tab_spaces, $tab_spaces);

      file_put_contents($file_name, $file_header . $class);

      return $class;
   }

   private function _buildTableMapperInitMethod($table_name,
      $model_prefix, $tab_spaces)
   {
      $method = sprintf(
         "%sprotected function init()\n%s{\n%s\n%s\n%s}",
          str_repeat(' ', $tab_spaces),
          str_repeat(' ', $tab_spaces),
          sprintf('%s$this->setTableName(\'%s\');',
            str_repeat(' ', $tab_spaces * 2), $table_name),
          sprintf('%s$this->setModelClass(\'%s%s\');',
            str_repeat(' ', $tab_spaces * 2), 
            $model_prefix,
            $this->adjustClassName($table_name)),
          str_repeat(' ', $tab_spaces));

      return $method;
   }
   
   private function _buildTableMapperLoadMethod($table_name, $model_prefix, $tab_spaces)
   {
      $tab = str_repeat(' ', $tab_spaces);
      $content = array();

      $pk = $this->getDatabase()->getTablePrimaryKey($table_name);

      if (!$pk instanceof MindFrame2_Dbms_Schema_Index)
      {
         throw new RunTimeException(
            sprintf("No primary key defined in table (%s)", $table_name));
      }

      $pk_fields = $pk->getFieldNames();

      if (count($pk_fields === 1))
      {
         $pk = reset($pk_fields);
      }

      $content[] = sprintf("%s\$record_id = \$record['%s'];",
         str_repeat($tab, 2), $pk);
      
      $content[] = sprintf(
         "\n%sif ((\$offspring = \$this->getOffspring(\$record_id)) !== FALSE)", 
         str_repeat($tab, 2));
      
      $content[] = sprintf("%s{", str_repeat($tab, 2));
      $content[] = sprintf("%sreturn \$offspring;", str_repeat($tab, 3));
      $content[] = sprintf("%s}\n", str_repeat($tab, 2));
      
      $content[] = sprintf('%s$model = new %s%s($record_id);',
         str_repeat($tab, 2), $model_prefix,
         $this->adjustClassName($table_name));
      
      $fields = $this->getDatabase()->getTableFields($table_name);

      foreach ($fields as $field)
      {
         $content[] = sprintf("%s\$model->set%s(\$record['%s']);", 
            str_repeat($tab, 2), $this->adjustMethodName($field->getName()), 
            $field->getName());
      }
      
      $content[] = sprintf("\n%s\$this->addOffspring(\$record_id, \$model);", 
         str_repeat($tab, 2));
      
      $content[] = sprintf("\n%sreturn \$model;", str_repeat($tab, 2));

      $method = sprintf(
         "%sprotected function load(array \$record)\n%s{\n%s\n%s}",
          $tab, $tab, join("\n", $content), $tab);

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
