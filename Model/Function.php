<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Function model
 */

/**
 * Function model
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2009-12-01
 */
class MindFrame2_Model_Function implements MindFrame2_Dbms_Record_Interface
{
   /**
    * @var int
    */
   private $_function_id;

   /**
    * @var string
    */
   private $_label;

   /**
    * Initializes the functionname property
    *
    * @param string $function_id Function ID
    * @param string $label Function name
    *
    * @throws InvalidArgumentException If function id argument is empty
    * @throws InvalidArgumentException If label argument is empty
    */
   public function __construct($function_id, $label)
   {
      MindFrame2_Validate::argumentIsNotEmpty($function_id, 1, 'function_id');
      MindFrame2_Validate::argumentIsNotEmpty($label, 1, 'label');

      $this->_function_id = $function_id;
      $this->_label = $label;
   }

   /**
    * Returns the function's id
    *
    * @return string
    */
   public function getFunctionId()
   {
      return $this->_function_id;
   }

   /**
    * Returns the function's label
    *
    * @return string
    */
   public function getLabel()
   {
      return $this->_label;
   }

   /**
    * Wraps getFunctionId() to conform with MindFrame2_Dbms_Record_Interface
    *
    * @return string
    */
   public function getPrimaryKey()
   {
      return $this->getFunctionId();
   }

   /**
    * This function is merely a sanity check. If it is called, it throws a 
    * runtime exception because this model uses constant values.
    *
    * @param double $value Database identifier
    *
    * @return void
    * 
    * @throws RuntimeException Because this field is read-only
    */
   public function setPrimaryKey($value)
   {
      throw RuntimeException('Atempting to change a read-only property');
   }
}
