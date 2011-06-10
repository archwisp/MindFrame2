<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Adapter for automatically creating PHP models out of a database schema model
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
 * Adapter for automatically creating PHP models out of a database schema model
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Dbms_Schema_Adapter_ToModel
   extends MindFrame2_Dbms_Schema_Adapter_Abstract
{
   /**
    * Builds a model for the specified database table
    *
    * @param string $table_name Name of the table for which to build the model
    *
    * @return string
    */
   public function buildTableModel($table_name, $class_prefix, $tab_spaces)
   {
      MindFrame2_Core::assertArgumentIsInt($tab_spaces, 3, 'tab_spaces');

      $properties = $this->
         _buildTableModelProperties($table_name, $tab_spaces);

      $get_methods = $this->
         _buildTableModelGetMethods($table_name, $tab_spaces);

      $set_methods = $this->
         _buildTableModelSetMethods($table_name, $tab_spaces);
      
      $interface_methods = $this->
         _buildTableModelInterfaceMethods($table_name, $tab_spaces);

      $class = sprintf(
         "class %s%s implements MindFrame2_Dbms_Record_Interface " .
            "\n{\n%s\n\n%s\n\n%s\n\n%s\n}\n",
         $class_prefix,
         $this->adjustClassName($table_name),
         join("\n", $properties),
         join("\n\n", $get_methods),
         join("\n\n", $set_methods),
         join("\n\n", $interface_methods));

      $file_name = MindFrame2_AutoLoad::convertClassToPath(
         $class_prefix . $this->adjustClassName($table_name));

      $file_header = sprintf("<?php // vim:ts=%s:sts=%s:sw=%s:et:\n\n",
         $tab_spaces, $tab_spaces, $tab_spaces);

      file_put_contents($file_name, $file_header . $class);

      return $class;
   }

   private function _buildTableModelProperties($table_name, $tab_spaces)
   {
      $fields = $this->getDatabase()->getTableFields($table_name);

      $properties = array();

      foreach ($fields as $field)
      {
         $properties[] = sprintf('%sprivate $%s;',
            str_repeat(' ', $tab_spaces),
            $this->adjustPropertyName($field->getName()));
      }
      // end foreach // ($fields as $field) //

      sort($properties);
      return $properties;
   }

   private function _buildTableModelGetMethods($table_name, $tab_spaces)
   {
      $fields = $this->getDatabase()->getTableFields($table_name);

      $methods = array();

      foreach ($fields as $field)
      {
         $methods[] = sprintf("%spublic function get%s()\n%s{\n%s%s\n%s}",
            str_repeat(' ', $tab_spaces),
            $this->adjustMethodName($field->getName()),
            str_repeat(' ', $tab_spaces),
            str_repeat(' ', $tab_spaces * 2),
            sprintf('return $this->%s;',
               $this->adjustPropertyName($field->getName())),
            str_repeat(' ', $tab_spaces),
            str_repeat(' ', $tab_spaces));
      }
      // end foreach // ($fields as $field) //

      sort($methods);
      return $methods;
   }

   private function _buildTableModelSetMethods($table_name, $tab_spaces)
   {
      $fields = $this->getDatabase()->getTableFields($table_name);

      $methods = array();

      foreach ($fields as $field)
      {
         $methods[] = sprintf("%spublic function set%s($%s)\n%s{\n%s%s\n%s}",
            str_repeat(' ', $tab_spaces),
            $this->adjustMethodName($field->getName()),
            $this->adjustParameterName($field->getName()),
            str_repeat(' ', $tab_spaces),
            str_repeat(' ', $tab_spaces * 2),
            sprintf('$this->%s = $%s;',
               $this->adjustPropertyName($field->getName()),
               $this->adjustParameterName($field->getName())),
            str_repeat(' ', $tab_spaces),
            str_repeat(' ', $tab_spaces));
      }
      // end foreach // ($fields as $field) //

      return $methods;
   }
   
   private function _buildTableModelInterfaceMethods($table_name, $tab_spaces)
   {
      $methods = array();
      
      $pk = $this->getDatabase()->getTablePrimaryKey($table_name);

      if (!$pk instanceof MindFrame2_Dbms_Schema_Index)
      {
         return $methods;
      }

      $fields = $pk->getFields();

      if (count($fields) !== 1)
      {
         throw new UnexpectedValueException('Complex primary keys have not be implemented in this builder.');
         // return array();
      }

      $field = reset($fields);
      
      $methods[] = sprintf("%spublic function getPrimaryKey()\n%s{\n%s%s\n%s}",
         str_repeat(' ', $tab_spaces),
         str_repeat(' ', $tab_spaces),
         str_repeat(' ', $tab_spaces * 2),
         sprintf('return $this->get%s();',
            $this->adjustMethodName($field->getName())),
         str_repeat(' ', $tab_spaces),
         str_repeat(' ', $tab_spaces));

      $methods[] = sprintf("%spublic function setPrimaryKey($%s)\n%s{\n%s%s\n%s}",
         str_repeat(' ', $tab_spaces),
         $this->adjustParameterName($field->getName()),
         str_repeat(' ', $tab_spaces),
         str_repeat(' ', $tab_spaces * 2),
         sprintf('return $this->set%s($%s);',
         $this->adjustMethodName($field->getName()),
         $this->adjustParameterName($field->getName())),
         str_repeat(' ', $tab_spaces),
         str_repeat(' ', $tab_spaces));

      return $methods;
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
      $filtered = str_replace('fld', NULL, $filtered);

      return strtolower($filtered);
   }

   protected function adjustParameterName($field_name)
   {
      return strtolower($field_name);
   }

   protected function adjustPropertyName($field_name)
   {
      $filtered = str_replace('fld', NULL, $field_name);

      return '_' . strtolower($filtered);
   }
}
