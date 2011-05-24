<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * MVC View helper for building fieldset forms
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
 * MVC View helper for building fieldset forms
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
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
