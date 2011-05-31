<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Permission model
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
 * Permission model
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_PermissionModel 
   implements MindFrame2_Dbms_Record_Interface,
      MindFrame2_Authorization_PermissionInterface
{
   /**
    * @var int
    */
   private $_permission_id;

   /**
    * @var string
    */
   private $_label;

   /**
    * Initializes the permissionname property
    *
    * @param string $permission_id Permission ID
    * @param string $label Permission name
    *
    * @throws InvalidArgumentException If permission id argument is empty
    * @throws InvalidArgumentException If label argument is empty
    */
   public function __construct($permission_id, $label)
   {
      MindFrame2_Validate::argumentIsNotEmpty($permission_id, 1, 'permission_id');
      MindFrame2_Validate::argumentIsNotEmpty($label, 1, 'label');

      $this->_permission_id = $permission_id;
      $this->_label = $label;
   }

   /**
    * Returns the permission's id
    *
    * @return string
    */
   public function getPermissionId()
   {
      return $this->_permission_id;
   }

   /**
    * Returns the permission's label
    *
    * @return string
    */
   public function getLabel()
   {
      return $this->_label;
   }

   /**
    * Wraps getPermissionId() to conform with MindFrame2_Dbms_Record_Interface
    *
    * @return string
    */
   public function getPrimaryKey()
   {
      return $this->getPermissionId();
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
      throw RuntimeException('Atempting to change a read-only property');
   }
}
