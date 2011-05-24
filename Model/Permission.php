<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Permission model
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
 * Permission model
 *
 * A permission represents one specific action for authorization purposes
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
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
