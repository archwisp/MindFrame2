<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * User model
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
 * User model
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_UserModel
   implements MindFrame2_Authorization_UserInterface,
      MindFrame2_Dbms_Record_Interface
{
   private $_roles = array();
   private $_user_id;

   public function getRoles()
   {
      return $this->_roles;
   }

   public function getPrimaryKey()
   {
      return $this->getUserId();
   }

   public function getUserId()
   {
      return $this->_user_id;
   }

   public function setPrimaryKey($user_id)
   {
      return $this->setUserId($user_id);
   }

   public function addRole(MindFrame2_Authorization_RoleInterface $role);
   {
      $this->_roles[] = $role;
   }

   public function setUserId($user_id)
   {
      return $this->_user_id = $user_id;
   }
}
