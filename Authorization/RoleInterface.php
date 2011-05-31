<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Authorization role model interface
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
 * Authorization role model interface
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
interface MindFrame2_Authorization_RoleInterface
{
   /**
    * Adds the specified permission to the role
    *
    * @return void
    */
   public function addPermission(
      MindFrame2_Authorization_PermissionInterface $permission);

   /**
    * Returns the organization to which the role is assigned
    *
    * @reutn MindFrame2_Authorization_OrganizationInterface
    */
   public function getOrganization();

   /**
    * Returns the array of permission which are assigned to the role
    *
    * @return array
    */
   public function getPermissions();

   /**
    * Retreives the permission object with the specified id if it exists
    *
    * @return MindFrame2_Authorization_PermissionInterface or FALSE
    */
   public function getPermissionById($permission_id);

   /**
    * Assignes the role to the specified organization
    *
    * @return void
    */
   public function setOrganization(
      MindFrame2_Authorization_OrganizationInterface $organization);
}
