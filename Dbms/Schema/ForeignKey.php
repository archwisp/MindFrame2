<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Foreign key model
 */

/**
 * Foreign key model
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
class MindFrame2_Dbms_Schema_ForeignKey
{
   /**
    * @var MindFrame2_Dbms_Schema_Field
    */
   private $_label_field;

   /**
    * @var array
    */
   private $_foreign_key_fields;

   /**
    * @var string
    */
   private $_name;

   /**
    * @var array
    */
   private $_primary_key_fields;

   /**
    * @var MindFrame2_Dbms_Schema_Table
    */
   private $_primary_key_table;

   /**
    * Builds the object
    *
    * @param string $name Name of the key
    * @param array $foreign_key_fields Field models for the fields that make up
    * the foreign key
    * @param MindFrame2_Dbms_Schema_Field $primary_key_table Table where the
    * primary key fields exist
    * @param array $primary_key_fields Fiel models for the fields that make up
    * the primary key
    * @param MindFrame2_Dbms_Schema_Field $label_field Field containing primary
    * key's text description
    *
    * @throws InvalidArgumentException If field array elements are not
    * MindFrame2_Dbms_Schema_Field objects
    */
   public function __construct($name,
      array $foreign_key_fields, MindFrame2_Dbms_Schema_Table $primary_key_table,
      array $primary_key_fields, MindFrame2_Dbms_Schema_Field $label_field)
   {
      foreach ($foreign_key_fields as $foreign_key_field)
      {
         if (!$foreign_key_field instanceof MindFrame2_Dbms_Schema_Field)
         {
            throw new InvalidArgumentException(
               'Expected array of MindFrame2_Dbms_Schema_Field ' .
               'objects for argument #2 (foreign_key_fields)');
         }
         // end if // (!$foreign_key_field instanceof...  //
      }
      // end foreach // ($foreign_key_fields as $foreign_key_field) //

      foreach ($primary_key_fields as $primary_key_field)
      {
         if (!$primary_key_field instanceof MindFrame2_Dbms_Schema_Field)
         {
            throw new InvalidArgumentException(
               'Expected array of MindFrame2_Dbms_Schema_Field ' .
               'objects for argument #4 (primary_key_fields)');
         }
         // end if // (!$primary_key_field instanceof... //
      }
      // end foreach // ($primary_key_fields as $primary_key_field) //

      $this->_name = $name;
      $this->_foreign_key_fields = $foreign_key_fields;
      $this->_primary_key_table = $primary_key_table;
      $this->_primary_key_fields = $primary_key_fields;
      $this->_label_field = $label_field;
   }

   /**
    * Returns the field contianing the primary key's text description
    *
    * @return MindFrame2_Dbms_Schema_Field
    */
   public function getLabelField()
   {
      return $this->_label_field;
   }

   /**
    * Returns an array of the field models of the fields that make up the
    * foreign key
    *
    * @return array
    */
   public function getForeignKeyFields()
   {
      return $this->_foreign_key_fields;
   }

   /**
    * Returns an array containing the names of the fields that make up the
    * foreign key
    *
    * @return array
    */
   public function getForeignKeyFieldNames()
   {
      $field_names = array();

      foreach ($this->_foreign_key_fields as $field)
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
    * Returns an array of field models of the fields make up the primary key
    *
    * @return array
    */
   public function getPrimaryKeyFields()
   {
      return $this->_primary_key_fields;
   }

   /**
    * Returns an array containing the names of the fields that make up the
    * primary key
    *
    * @return array
    */
   public function getPrimaryKeyFieldNames()
   {
      $field_names = array();

      foreach ($this->_primary_key_fields as $field)
      {
         $field_names[] = $field->getName();
      }

      return $field_names;
   }

   /**
    * Returns the table table model for the table in which the primary key
    * fields exist
    *
    * @return MindFrame2_Dbms_Schema_Table
    */
   public function getPrimaryKeyTable()
   {
      return $this->_primary_key_table;
   }
}
