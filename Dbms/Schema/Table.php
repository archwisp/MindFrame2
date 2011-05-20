<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Table model
 */

/**
 * Table model
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2008-03-01
 */
class MindFrame2_Dbms_Schema_Table
{
   /**
    * @var array
    */
   private $_fields = array();

   /**
    * @var array
    */
   private $_foreign_keys = array();

   /**
    * @var array
    */
   private $_indexes = array();

   /**
    * @var string
    */
   private $_name;

   /**
    * @var MindFrame2_Dbms_Schema_Index
    */
   private $_primary_key;

   /**
    * Construct
    *
    * @param string $name Name of the table
    */
   public function __construct($name)
   {
      $this->_name = $name;
   }

   /**
    * Addes a field object to the collection
    *
    * @param MindFrame2_Dbms_Schema_Field $field Field model
    *
    * @return void
    */
   public function addField(MindFrame2_Dbms_Schema_Field $field)
   {
      $this->_fields[$field->getName()] = $field;
   }

   /**
    * Adds an index object to the collection
    *
    * @param MindFrame2_Dbms_Schema_Index $index Index model
    *
    * @return void
    */
   public function addIndex(MindFrame2_Dbms_Schema_Index $index)
   {
      $this->_indexes[] = $index;
   }

   /**
    * Adds a foreign key object to the collection
    *
    * @param MindFrame2_Dbms_Schema_ForeignKey $foreign_key Foreign key model
    *
    * @return void
    */
   public function addForeignKey(MindFrame2_Dbms_Schema_ForeignKey $foreign_key)
   {
      $this->_foreign_keys[] = $foreign_key;
   }

   /**
    * Sets the primary key object
    *
    * @param MindFrame2_Dbms_Schema_Index $index Model index
    *
    * @return void
    */
   public function setPrimaryKey(MindFrame2_Dbms_Schema_Index $index)
   {
      $this->_primary_key = $index;
   }

   /**
    * Returns the field objects in the collection
    *
    * @return array
    */
   public function getFields()
   {
      return $this->_fields;
   }

   /**
    * Returns the names of the field objects in the collection
    *
    * @return array
    */
   public function getFieldNames()
   {
      return array_keys($this->_fields);
   }

   /**
    * Returns the field with the specified name property
    *
    * @param string $field_name Name of field to retreive
    *
    * @return MindFrame2_Dbms_Schema_Field
    *
    * @throws InvalidArgumentException If the field is not defined in the table
    * model
    */
   public function getFieldByName($field_name)
   {
      if (!array_key_exists($field_name, $this->_fields))
      {
         throw new InvalidArgumentException(
            'Field is not defined: ' . $field_name);
      }

      return $this->_fields[$field_name];
   }

   /**
    * Builds an array of field objects with the specified names
    *
    * @param array $field_names Names of the fields to retreive
    *
    * @return array
    */
   public function getFieldsByNames(array $field_names)
   {
      $fields = array();

      foreach ($field_names as $field_name)
      {
         $fields[] = $this->getFieldByName($field_name);
      }

      return $fields;
   }

   /**
    * Returns the foreign keys in the collection
    *
    * @return array
    */
   public function getForeignKeys()
   {
      return $this->_foreign_keys;
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
    * Returns the primary key index
    *
    * @return MindFrame2_Dbms_Schema_Index or FALSE
    */
   public function getPrimaryKey()
   {
      if (!$this->_primary_key instanceof MindFrame2_Dbms_Schema_Index)
      {
         return FALSE;
      }

      return $this->_primary_key;
   }

   /**
    * Returns the indexes from the collection
    *
    * @return array
    */
   public function getIndexes()
   {
      return $this->_indexes;
   }
}
