<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Organization Type model
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
 * Organization Type model
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_OrganizationTypeModel
   implements MindFrame2_Dbms_Record_Interface
{
   /**
    * @var int
    */
   private $_organization_type_id;

   /**
    * @var string
    */
   private $_label;

   /**
    * Initializes the organization_typename property
    *
    * @param string $organization_type_id OrganizationType ID
    * @param string $label OrganizationType name
    *
    * @throws InvalidArgumentException If organization_type id argument is empty
    * @throws InvalidArgumentException If label argument is empty
    */
   public function __construct($organization_type_id, $label)
   {
      MindFrame2_Core::assertArgumentIsNotBlank(
         $organization_type_id, 1, 'organization_type_id');

      MindFrame2_Core::assertArgumentIsNotBlank($label, 1, 'label');

      $this->_organization_type_id = $organization_type_id;
      $this->_label = $label;
   }

   /**
    * Returns the organization type's id
    *
    * @return string
    */
   public function getOrganizationTypeId()
   {
      return $this->_organization_type_id;
   }

   /**
    * Returns the organization type's label
    *
    * @return string
    */
   public function getLabel()
   {
      return $this->_label;
   }

   /**
    * Wraps getOrganizationTypeId() to conform with
    * MindFrame2_Dbms_Record_Interface
    *
    * @return string
    */
   public function getPrimaryKey()
   {
      return $this->getOrganizationTypeId();
   }

   /**
    * This function is merely a sanity check. If it is called, it throws a
    * runtime exception because this model uses constant values.
    *
    * @param double $value Database identifier
    *
    * @return void
    *
    * @throws RuntimeException Because this field is read-only
    */
   public function setPrimaryKey($value)
   {
      throw new RuntimeException('Atempting to change a read-only property');
   }
}
