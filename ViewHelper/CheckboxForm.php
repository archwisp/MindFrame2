<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * MVC View helper for building checkbox forms
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
 * MVC View helper for building checkbox forms
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_ViewHelper_CheckboxForm
{
   /**
    * @var array
    */
   protected $fields = array();

   /**
    * Adds a checkbox to the collection
    *
    * @param string $title Checkbox label
    * @param MindFrame2_Xhtml_Input $input Checkbox object
    *
    * @return void
    */
   public function addInputField($title, MindFrame2_Xhtml_Input $input)
   {
      $this->fields[] = $div = new MindFrame2_Xhtml_Div($input, array());
      $div->addContent($title);
   }

   /**
    * Builds a form with the css class of FieldsetForm in which fields are
    * encapsulated within dl elements.
    *
    * @param string $caption Form caption
    * @param string $action Form action
    *
    * @return MindFrame2_Xhtml_Form
    */
   public function buildForm($caption, $action)
   {
      $form = new MindFrame2_Xhtml_Form(NULL,
         array(
            'method' => 'post',
            'action' => $action,
            'class' => 'CheckboxForm'));

      $form->addContent($fieldset = new MindFrame2_Xhtml_Fieldset(NULL, array()));

      $fieldset->addContent(new MindFrame2_Xhtml_Legend(
         $caption, array('class' => 'Caption')));

      $fieldset->addContent($div = new MindFrame2_Xhtml_Div(
         NULL, array('class' => 'Fields')));

      foreach ($this->fields as $field)
      {
         $div->addContent($field);
      }

      return $form;
   }
}
