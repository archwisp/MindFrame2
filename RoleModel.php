<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Role model
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
 * Role model
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_RoleModel
   implements MindFrame2_Dbms_Record_Interface,
      MindFrame2_Authorization_RoleInterface
{
   private $_role_id;
   private $_label;
   private $_permissions = array();
   private $_organization;

   public function __construct($role_id, $label,
      MindFrame2_Authorization_OrganizationInterface $organization)
   {
      $this->setLabel($label);
      $this->setOrganization($organization);
      $this->setRoleId($role_id);
   }

   public function addPermission(MindFrame2_Authorization_PermissionInterface $permission)
   {
      $this->_permissions[$permission->getPermissionId()] = $permission;
   }

   public function getRoleId()
   {
      return $this->_role_id;
   }

   public function getLabel()
   {
      return $this->_label;
   }

   public function getOrganization()
   {
      return $this->_organization;
   }

   public function getPermissions()
   {
      return $this->_permissions;
   }

   public function getPermissionIds()
   {
      return array_keys($this->getPermissions());
   }

   public function getPermissionById($permission_id)
   {
      if (array_key_exists($permission_id, $this->_permissions))
      {
         return $this->_permissions[$permission_id];
      }

      return FALSE;
   }

   public function getPermissionByLabel($label)
   {
      $permissions = $this->getPermissions();

      foreach ($permissions as $permission)
      {
         if ($permission->getLabel() == $label)
         {
            return $permission;
         }
      }

      return FALSE;
   }

   public function getPrimaryKey()
   {
      return $this->getRoleId();
   }

   public function removePermission(MindFrame2_Authorization_PermissionInterface $permission)
   {
      unset($this->_permissions[$permission->getPermissionId()]);
   }

   public function setLabel($label)
   {
      MindFrame2_Core::assertArgumentIsNotBlank($label, 1, 'label');

      $this->_label = $label;
   }

   public function setOrganization(
      MindFrame2_Authorization_OrganizationInterface $organization)
   {
      $this->_organization = $organization;
   }

   public function setPrimaryKey($role_id)
   {
      return $this->setRoleId($role_id);
   }

   public function setRoleId($role_id)
   {
      $this->_role_id =  $role_id;
   }
}
