<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file MVC View helper for building fieldset forms
 *
 */

/**
 * MVC View helper for building fieldset forms
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
class MindFrame2_ViewHelper_FieldsetForm
{
   /**
    * @var array
    */
   protected $fields = array();

   /**
    * Adds an input field to the collection
    *
    * @param string $title Field label
    * @param MindFrame2_Xhtml_Interface $input Field object
    *
    * @return void
    */
   public function addInputField($title, MindFrame2_Xhtml_Interface $input)
   {
      $this->fields[] = new MindFrame2_Xhtml_Dt($title, array());
      $this->fields[] = new MindFrame2_Xhtml_Dd($input, array());
   }

   /**
    * Adds a text-only, read-only field to the collection
    *
    * @param string $title Field label
    * @param string $value Field value
    *
    * @return void
    */
   public function addTextOnlyField($title, $value)
   {
      $this->fields[] = new MindFrame2_Xhtml_Dt($title, array());

      $this->fields[] = new MindFrame2_Xhtml_Dd(
         new MindFrame2_Xhtml_Em($value, array()), array());
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
            'class' => 'FieldsetForm'));

      $form->addContent($fieldset = new MindFrame2_Xhtml_Fieldset(NULL, array()));

      $fieldset->addContent(new MindFrame2_Xhtml_Legend(
         $caption, array('class' => 'Caption')));

      $fieldset->addContent($dl = new MindFrame2_Xhtml_Dl(
         NULL, array('class' => 'Fields')));

      foreach ($this->fields as $field)
      {
         $dl->addContent($field);
      }

      return $form;
   }
}
