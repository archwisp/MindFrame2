<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Adapter for automatically creating an XHTML form out of a database model
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
 * Adapter for automatically creating an XHTML form out of a database model
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Dbms_Schema_Adapter_ToForm extends MindFrame2_Dbms_Schema_Adapter_Abstract
{
   /**
    * Builds an XHTML form for the specified table. The fields are populated
    * with the specified input values.
    *
    * @param string $table_name Name of the table for which to build the form
    * @param array $input_values Values for populating the fields
    * @param array $submit_name Name assigned to the submit button
    * @param array $submit_value Value assigned to the submit button
    *
    * @return MindFrame2_Xhtml_Form
    */
   public function buildTableForm(
      $table_name, array $input_values, $submit_name, $submit_value)
   {
      $form = new MindFrame2_Xhtml_Form(NULL, array('action' => '',
         'method' => 'post', 'class' => get_class($this)));

      $form->addContent($fieldset = new MindFrame2_Xhtml_Fieldset(NULL, array()));

      $fieldset->addContent(new MindFrame2_Xhtml_Legend(
         str_replace('_', ' ', $table_name), array()));

      $fieldset->addContent($dl = new MindFrame2_Xhtml_Dl(NULL, array()));

      $fields = $this->getDatabase()->getTableFields($table_name);

      $fk_field_names = $this->getDatabase()->
         getTableForeignKeyFieldNames($table_name);

      foreach ($fields as $field)
      {
         // Defer creation of foreign key fields until later when they can be
         // grouped with their label fields

         if (!in_array($field->getName(), $fk_field_names))
         {
            $this->buildFieldElements(
               $table_name, $field, $input_values, FALSE, $dl);
         }
         // end if // (!in_array($field->getName(), $fk_field_names)) //
      }
      // end foreach // ($fields as $field) //

      // Now render the foreign key fields and their associated label fields

      $foreign_keys = $this->getDatabase()->getTableForeignKeys($table_name);

      foreach ($foreign_keys as $foreign_key)
      {
         foreach ($foreign_key->getForeignKeyFields() as $field)
         {
            $this->buildFieldElements(
               $table_name, $field, $input_values, FALSE, $dl);
         }
         // end foreach // ($foreign_key->getForeignKeyFields() as $field) //

         $fk_name = $foreign_key->getName();
         $label_field = $foreign_key->getLabelField();

         $this->buildFieldElements(
            $fk_name, $label_field, $input_values, TRUE, $dl);
      }
      // end foreach // ($foreign_keys as $foreign_key) //

      $submit = new MindFrame2_Xhtml_Input(array('type' => 'submit',
         'name' => $submit_name, 'value' => $submit_value,
         'class' => 'Button'));

      $fieldset->addContent($submit);

      return $form;
   }

   /**
    * Builds an XHTML table from the table object model for the table name
    * specified and the supplied search results.
    *
    * @param string $table_name Name of the table being searched
    * @param array $search_results The search results to be displayed
    *
    * @return MindFrame2_Xhtml_Table or FALSE
    */
   public function buildTableSearchResultsXhtmlTable(
      $table_name, array $search_results)
   {
      if (empty($search_results))
      {
         return FALSE;
      }

      $fields = $this->getDatabase()->getTableFields($table_name);
      $foreign_keys = $this->getDatabase()->getTableForeignKeys($table_name);

      $table = new MindFrame2_Xhtml_Table(NULL, array('class' => 'SearchResults'));
      $row = new MindFrame2_Xhtml_Tr(NULL, array('class' => 'Header'));

      foreach ($fields as $field)
      {
         $row->addContent(new MindFrame2_Xhtml_Th($field->getName(), array()));
      }
      // end foreach // ($fields as $field) //

      foreach ($foreign_keys as $foreign_key)
      {
         $row->addContent(new MindFrame2_Xhtml_Th(
            $foreign_key->getName(), array()));
      }
      // end foreach // ($foreign_keys as $foreign_key) //

      $table->addContent($row);
      $row_index = 0;

      foreach ($search_results as $columns)
      {
         $class = (($row_index % 2) === 0) ? 'Odd' : 'Even';

         $row = new MindFrame2_Xhtml_Tr(NULL, array('class' => $class));

         foreach ($fields as $field)
         {
            $row->addContent(
               new MindFrame2_Xhtml_Td($columns[$field->getName()], array()));
         }
         // end foreach // ($columns as $column) //

         $table->addContent($row);
         $row_index++;
      }
      // end foreach // ($element->getRows() as $columns) //

      return $table;
   }

   /**
    * Builds label and input for the specified field and adds them to the
    * specified XHTML containter.
    *
    * @param string $table_name Name of the table for which the form is built
    * @param MindFrame2_Dbms_Schema_Field $field Field object used to build the elements
    * @param array $input_values Values for populating the fields
    * @param bool $full_label Whether or not to prepend the table name to the
    * field name
    * @param MindFrame2_Xhtml_AbstractContainerElement $container Output container
    *
    * @return bool
    */
   protected function buildFieldElements($table_name,
      MindFrame2_Dbms_Schema_Field $field, array $input_values, $full_label,
      MindFrame2_Xhtml_AbstractContainerElement $container)
   {
      $field_name = $table_name
         . $this->getFieldDelimiter() . $field->getName();

      $field_value = array_key_exists($field_name, $input_values)
         ? $input_values[$field_name] : NULL;

      if ($full_label)
      {
         $field_label = $table_name . ' ' . $field->getName();
      }
      else
      {
         $field_label = $field->getName();
      }

      $label = new MindFrame2_Xhtml_Span(
         str_replace('_', ' ', $field_label), array());

      $title = new MindFrame2_Xhtml_Dt($label, array());

      $input = new MindFrame2_Xhtml_Input(array(
         'type' => 'text',
         'id' => $field_name,
         'name' => $field_name,
         'value' => $field_value
      ));

      $definition = new MindFrame2_Xhtml_Dd($input, array());

      $container->addContent($title);
      $container->addContent($definition);

      return TRUE;
   }
}
