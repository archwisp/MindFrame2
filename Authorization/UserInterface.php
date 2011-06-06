<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Authorization user model interface
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
 * Authorization user model interface
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
interface MindFrame2_Authorization_UserInterface
{
   /**
    * Assigns the specified role to the user
    *
    * @return void
    */
   public function addRole(MindFrame2_Authorization_RoleInterface $role);

   /**
    * Returns the array of roles whih have been assigned to the user
    *
    * @return array
    */
   public function getRoles();
}
