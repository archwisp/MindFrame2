<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Field model
 */

/**
 * Field model
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
class MindFrame2_Dbms_Schema_Field
{
   /**
    * @var string
    */
   private $_type;

   /**
    * @var int
    */
   private $_length;

   /**
    * @var bool
    */
   private $_allow_null;

   /**
    * @var bool
    */
   private $_is_auto_increment;

   /**
    * @var string
    */
   private $_default_value;

   /**
    * @var string
    */
   private $_name;

   /**
    * Creates the object
    *
    * @param string $name Field name
    * @param string $type Field type
    * @param int $length Field length
    * @param bool $allow_null Allow NULL or not
    * @param string $default_value Default value for newly inserted records
    * @param bool $is_auto_increment Identity field or not
    *
    * @throws InvalidArgumentException If the name agument is empty
    * @throws InvalidArgumentException If the type agument is empty
    * @throws InvalidArgumentException If the length is not an intege
    * @throws InvalidArgumentException If the allow null is not a boolean
    * @throws InvalidArgumentException If the auto increment is not a boolean
    */
   public function __construct($name, $type, $length,
      $allow_null, $default_value, $is_auto_increment)
   {
      MindFrame2_Validate::argumentIsNotEmpty($name, 1, $name);
      MindFrame2_Validate::argumentIsNotEmpty($type, 2, $type);
      MindFrame2_Validate::argumentIsIntOrNull($length, 3, $length);
      MindFrame2_Validate::argumentIsBool($allow_null, 4, $allow_null);
      MindFrame2_Validate::argumentIsBool(
         $is_auto_increment, 6, $is_auto_increment);

      $this->_name = $name;
      $this->_type = $type;
      $this->_length = $length;
      $this->_allow_null = $allow_null;
      $this->_default_value = $default_value;
      $this->_is_auto_increment = $is_auto_increment;
   }

   /**
    * Returns the type property of the object
    *
    * @return string
    */
   public function getType()
   {
      return $this->_type;
   }

   /**
    * Returns the length property of the object
    *
    * @return int
    */
   public function getLength()
   {
      return $this->_length;
   }

   /**
    * Returns the allow_null property of the object
    *
    * @return bool
    */
   public function getAllowNull()
   {
      return $this->_allow_null;
   }

   /**
    * Returns the is_auto_increment property of the object
    *
    * @return bool
    */
   public function getIsAutoIncrement()
   {
      return $this->_is_auto_increment;
   }

   /**
    * Returns the default_value property of the object
    *
    * @return string
    */
   public function getDefaultValue()
   {
      return $this->_default_value;
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
}
