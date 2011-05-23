<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Permission model
 */

/**
 * Permission model
 *
 * A permission represents one specific action for authorization purposes
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
class MindFrame2_Model_Permission extends MindFrame2_Object
{
   /**
    * Construct
    *
    * @param string $name Database name
    * @param string $role Role to which the permission is assigned
    *
    * @throws InvalidArgumentException If name is empty
    * @throws InvalidArgumentException If role is empty
    */
   public function __construct($name, $role)
   {
      MindFrame2_Validate::argumentIsNotEmpty($name, 1, 'name');
      MindFrame2_Validate::argumentIsNotEmpty($role, 1, 'role');

      $this->name = $name;
      $this->role = $role;
   }

   /**
    * Returns the $name property of the object
    *
    * @return string
    */
   public function getName()
   {
      return $this->name;
   }

   /**
    * Returns the $role property of the object
    *
    * @return string
    */
   public function getRole()
   {
      return $this->role;
   }
}
