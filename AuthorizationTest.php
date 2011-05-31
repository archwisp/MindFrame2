<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Test class for MindFrame2_Authorization
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
 * Test class for MindFrame2_Authorization
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_AuthorizationTest extends PHPUnit_Framework_TestCase
{
   private $_instance;

   public function setUp()
   {
      $acme = new MindFrame2_OrganizationModel(77, 'Acme');

      $acme_tools = new MindFrame2_OrganizationModel(754, 'Acme Tools');
      $acme_tools->setParentOrganization($acme);

      $admin = new MindFrame2_RoleModel(rand(), 'Acme Tools - Admin', $acme_tools);

      $admin->addPermission(new MindFrame2_PermissionModel(3, 'Add User'));
      $admin->addPermission(new MindFrame2_PermissionModel(6, 'Edit Users'));
      $admin->addPermission(new MindFrame2_PermissionModel(22, 'Reset Passwords'));

      $read_only = new MindFrame2_RoleModel(rand(), 'Acme - Read-only', $acme);

      $user = new MindFrame2_UserModel(2112, 'test', 'Foo Bar', NULL);
      $user->addRole($admin);

      $this->_instance = new MindFrame2_Authorization();
   }

   public function testSomething()
   {
      $this->assertEquals(TRUE, TRUE);
   }
}
