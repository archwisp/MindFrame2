<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Index model
 */

/**
 * Index model
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2008-10-15
 */
class MindFrame2_Dbms_Schema_Index
{
   /**
    * @param array
    */
   private $_fields = array();

   /**
    * @var string
    */
   private $_name;

   /**
    * @param string
    */
   private $_type = NULL;

   /**
    * Initiates the name, type, and fields for the model
    *
    * @param string $name Name of the index
    * @param string $type Type of index
    * @param array $fields Fields to be indexed
    *
    * @throws InvalidArgumentException If any of the specified fields are not
    * and instance of MindFrame2_Dbms_Schema_Field
    */
   public function __construct($name, $type, array $fields)
   {
      foreach ($fields as $field)
      {
         if (!$field instanceof MindFrame2_Dbms_Schema_Field)
         {
            throw new InvalidArgumentException(
               'Expected array of MindFrame2_Dbms_Schema_Field ' .
               'objects for argument #2 (fields)');
         }
      }

      $this->_name = $name;
      $this->_type = $type;
      $this->_fields = $fields;
   }

   /**
    * Returns the field models which represent the fields that are part of the
    * index
    *
    * @return array
    */
   public function getFields()
   {
      return $this->_fields;
   }

   /**
    * Returns an array containing the names of the fields that are part of the
    * index
    *
    * @return array
    */
   public function getFieldNames()
   {
      $field_names = array();

      foreach ($this->_fields as $field)
      {
         $field_names[] = $field->getName();
      }

      return $field_names;
   }

   /**
    * Returns the name of the table
    *
    * @return string
    */
   public function getName()
   {
      return $this->_name;
   }

   /**
    * Returns the type of index
    *
    * @return string
    */
   public function getType()
   {
      return $this->_type;
   }
}
