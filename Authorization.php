<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Hierarchical authorization module
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
 * Hierarchical authorization module
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
    * Determines whether or not a user is assigned the specified permission
    * for the specified organization.
    *
    * @param string $organization Organization to check against
    * @param string $permission_id Permission id to check for
    *
    * @return bool
    */
   public function checkForPermission(
      MindFrame2_Authorization_OrganizationInterface $organization,
      $permission_id)
   {
      $user = $this->getUser();

      $ancestry = array_merge(
         array($organization), $this->_getAncestry($organization)
      );

      if ($user instanceof MindFrame2_Authorization_UserInterface)
      {
         $roles = $user->getRoles();

         foreach ($roles as $role)
         {
            $permission = $role->getPermissionById($permission_id);

            if ($permission !== FALSE)
            {
               foreach ($ancestry as $organization)
               {
                  if ($role->getOrganization() === $organization)
                  {
                     return TRUE;
                  }
                  // end if // ($role->getOrganization() === $organization) //
               }
               // end foreach // ($ancestry as $organization) //
            }
            // end if // ($permission !== FALSE) //
         }
         // end foreach // ($roles as $role) //
      }
      // end if // ($user instanceof MindFrame2_Authorization_UserInterface) //

      return FALSE;
   }

   private function _getAncestry(
      MindFrame2_Authorization_OrganizationInterface $organization)
   {
      $ancestry = array();
      $parent = $organization->getParentOrganization();

      if (!$parent instanceof MindFrame2_Authorization_OrganizationInterface)
      {
         return $ancestry;
      }

      $ancestry[] = $parent;

      $ancestry = array_merge($ancestry, $this->_getAncestry($parent));

      return $ancestry;
   }
}
