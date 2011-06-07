<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Organization model
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
 * Organization model
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_OrganizationModel
   implements MindFrame2_Dbms_Record_Interface,
      MindFrame2_Authorization_OrganizationInterface
{
   private $_label;
   private $_organization_id;
   private $_parent_organization;

   public function __construct($organization_id, $label)
   {
      MindFrame2_Core::assertArgumentIsNotBlank(
         $organization_id, 1, 'organization_id');

      MindFrame2_Core::assertArgumentIsNotBlank($label, 1, 'label');

      $this->_organization_id = $organization_id;
      $this->_label = $label;
   }

   public function getOrganizationId()
   {
      return $this->_organization_id;
   }

   public function getLabel()
   {
      return $this->_label;
   }

   public function getParentOrganization()
   {
      return $this->_parent_organization;
   }

   public function getPrimaryKey()
   {
      return $this->getOrganizationId();
   }

   public function setParentOrganization(
      MindFrame2_Authorization_OrganizationInterface $parent_organization)
   {
      $this->_parent_organization = $parent_organization;
   }

   public function setPrimaryKey($value)
   {
      throw RuntimeException('Atempting to change a read-only property');
   }
}
