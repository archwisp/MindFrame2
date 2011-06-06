<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Handles all authorization routines for the framework
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
 * Handles all authorization routines for the framework. This class operates in
 * a similar manner as a file system. An ACL is created for each object as
 * necessary and permission entries are added to the ACL. This class also
 * supports cascading permission for parent/child objects. Permissions granted
 * to a parent object will apply to all of its children.
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Authorization
{
   /**
    * @var MindFrame2_Authorization_UserInterface
    */
   private $_user;

   /**
    * Sets the user model which will be checked for determining authorization
    *
    * @param MindFrame2_Authorization_UserInterface $user User model
    *
    * @return void
    */
   public function setUser(MindFrame2_Authorization_UserInterface $user)
   {
      $this->_user = $user;
   }

   /**
    * Returns the user model set in the object
    *
    * @return MindFrame2_Authorization_UserInterface or NULL
    */
   public function getUser()
   {
      return $this->_user;
   }

   /**
    * Fetches whether or not a user is assigned the specified permission
    * in the specified organization.
    *
    * @param string $organization_id Organization id to check for
    * @param string $permission_id Permission id to check for
    *
    * @return bool
    */
   public function isUserAssignedPermission($organization_id, $permission_id)
   {
      $user = $this->getUser();

      if ($user instanceof MindFrame2_Authorization_UserInterface)
      {
         $roles = $user->getRoles();

         foreach ($roles as $role)
         {
            if ($role->getOrganization()->
               getOrganizationId() == $organization_id)
            {
               if ($this->_doesRoleContainPermission(
                  $role, $permission_id) === TRUE)
               {
                  return TRUE;
               }
               // end if // ($this->_doesRoleContainPermission($role, ... //
            }
            // end if // ($role->getOrganization()->getOrganizationId() ... //
         }
         // end foreach // ($roles as $role) //
      }
      // end if // ($user instanceof MindFrame2_Authorization_UserInterface) //

      return FALSE;
   }

   private function _doesRoleContainPermission(
      MindFrame2_Authorization_RoleInterface $role, $permission_id)
   {
      $permission = $role->getPermissionById($permission_id);

      if ($permission instanceof MindFrame2_Authorization_PermissionInterface)
      {
         return TRUE;
      }

      return FALSE;
   }
}
