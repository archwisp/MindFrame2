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
   private $_display_name;
   private $_email_address;
   private $_roles = array();
   private $_user_id;
   private $_username;

   public function __construct($user_id, $username, $display_name, $email_address)
   {
      MindFrame2_Validate::argumentIsNotEmpty($username, 2, 'displayname');
      
      $this->_username = $username;
      
      $this->setDisplayName($display_name);
      $this->setEmailAddress($email_address);
      $this->setUserId($user_id);
   }

   public function getDisplayName()
   {
      return $this->display_name;
   }

   public function getEmailAddress()
   {
      return $this->_email_address;
   }

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

   public function getUsername()
   {
      return $this->_username;
   }

   public function setDisplayName($display_name)
   {
      MindFrame2_Validate::argumentIsNotEmpty($display_name, 1, 'display_name');

      $this->_display_name = $display_name;
   }

   public function setEmailAddress($email_address)
   {
      $this->_email_address = $email_address;
   }

   public function setPrimaryKey($user_id)
   {
      return $this->setUserId($user_id);
   }

   public function addRole(MindFrame2_Authorization_RoleInterface $role)
   {
      $this->_roles[] = $role;
   }

   public function setUserId($user_id)
   {
      MindFrame2_Validate::argumentIsNotEmpty($user_id, 1, 'user_id');
      
      $this->_user_id = $user_id;
   }
}
