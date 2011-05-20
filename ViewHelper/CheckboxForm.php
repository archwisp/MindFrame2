<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * MVC View helper for building checkbox forms
 *
 */

/**
 * MVC View helper for building checkbox forms
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2010-08-16
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
