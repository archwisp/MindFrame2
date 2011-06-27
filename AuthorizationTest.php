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
      $this->_instance = new MindFrame2_Authorization();
      
      $this->_instance->setUser(
         new MindFrame2_UserModel(1, 'test', 'Foo Bar', 'bryan@localhost')
      );
   }

   public function testDirectPermission()
   {
      $acme = new MindFrame2_OrganizationModel(1, 'Acme');
      $read_only = new MindFrame2_RoleModel(rand(), 'Acme - Read-only', $acme);
      $read_only->addPermission(new MindFrame2_PermissionModel(1, 'List Users'));

      $this->_instance->getUser()->addRole($read_only);

      $this->assertEquals(TRUE,
         $this->_instance->checkForPermission($acme, 1));
      
      $this->assertEquals(FALSE,
         $this->_instance->checkForPermission($acme, 2));
   }
   
   public function testInheritedPermission()
   {
      $acme = new MindFrame2_OrganizationModel(1, 'Acme');
      $read_only = new MindFrame2_RoleModel(rand(), 'Acme - Read-only', $acme);
      $read_only->addPermission(new MindFrame2_PermissionModel(1, 'List Users'));

      $this->_instance->getUser()->addRole($read_only);

      $acme_tools = new MindFrame2_OrganizationModel(2, 'Acme Tools');
      $acme_tools->setParentOrganization($acme);
      
      $admin = new MindFrame2_RoleModel(rand(), 'Acme Tools - Admin', $acme_tools);
      $admin->addPermission(new MindFrame2_PermissionModel(2, 'Edit Users'));
      
      $this->_instance->getUser()->addRole($admin);

      $acme_tools_sales = new MindFrame2_OrganizationModel(3, 'Acme Tools - Sales');
      $acme_tools_sales->setParentOrganization($acme_tools);

      // Check Acme for List Users permission. This check should pass.

      $this->assertEquals(TRUE,
         $this->_instance->checkForPermission($acme, 1));

      // Check Acme for Edit Users function. It has been assigned to 
      // Acme Tools, so this check should fail. 

      $this->assertEquals(FALSE,
         $this->_instance->checkForPermission($acme, 2));
      
      // Check Acme for an undefined function. This check should fail.
            
      $this->assertEquals(FALSE,
         $this->_instance->checkForPermission($acme, 3));

      // Check Acme Tools for List Users functionality which should be 
      // inherited from Acme.

      $this->assertEquals(TRUE,
         $this->_instance->checkForPermission($acme_tools, 1));
      
      $this->assertEquals(TRUE,
         $this->_instance->checkForPermission($acme_tools, 2));
      
      $this->assertEquals(FALSE,
         $this->_instance->checkForPermission($acme_tools, 3));
   }
}
