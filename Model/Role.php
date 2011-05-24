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
class MindFrame2_Model_Role implements MindFrame2_Dbms_Record_Interface
{
   /**
    * @var int
    */
   private $_role_id;

   /**
    * @var string
    */
   private $_label;

   /**
    * @var array
    */
   private $_functions = array();

   /**
    * Initializes the rolename property
    *
    * @param int $role_id Role ID
    * @param string $label Rolename
    *
    * @throws InvalidArgumentException If role id is not an integer value
    * @throws InvalidArgumentException If label is empty
    */
   public function __construct($role_id, $label)
   {
      MindFrame2_Validate::argumentIsNotEmpty($label, 1, 'label');

      $this->_role_id = $role_id;
      $this->_label = $label;
   }

   /**
    * Adds a function to the collection
    *
    * @param MindFrame2_Model_Function $function The function to be added
    *
    * @return void
    */
   public function addFunction(MindFrame2_Model_Function $function)
   {
      $this->_functions[$function->getFunctionId()] = $function;
   }

   /**
    * Returns the role's id
    *
    * @return int
    */
   public function getRoleId()
   {
      return $this->_role_id;
   }

   /**
    * Returns the role's label
    *
    * @return string
    */
   public function getLabel()
   {
      return $this->_label;
   }

   /**
    * Returns the collection of functions
    *
    * @return array
    */
   public function getFunctions()
   {
      return $this->_functions;
   }

   /**
    * Returns the ids of the functions in the collection
    *
    * @return array
    */
   public function getFunctionIds()
   {
      return array_keys($this->getFunctions());
   }

   /**
    * Returns the function with the specified id, if any.
    *
    * @param string $function_id The function's ID
    *
    * @return array
    */
   public function getFunctionById($function_id)
   {
      if (array_key_exists($function_id, $this->_functions))
      {
         return $this->_functions[$function_id];
      }

      return FALSE;
   }

   /**
    * Returns the function with a matching label, if any.
    *
    * @param string $label The object's label
    *
    * @return array
    */
   public function getFunctionByLabel($label)
   {
      $functions = $this->getFunctions();

      foreach ($functions as $function)
      {
         if ($function->getLabel() == $label)
         {
            return $function;
         }
      }

      return FALSE;
   }

   /**
    * Wraps getRoleId() to conform with MindFrame2_Dbms_Record_Interface
    *
    * @return string
    */
   public function getPrimaryKey()
   {
      return $this->getRoleId();
   }

   /**
    * Removes a function from the role
    *
    * @param MindFrame2_Model_Function $function Function to be removed
    *
    * @return void
    */
   public function removeFunction(MindFrame2_Model_Function $function)
   {
      unset($this->_functions[$function->getFunctionId()]);
   }

   /**
    * Sets the model's label
    *
    * @param string $label The object's label
    *
    * @return void
    *
    * @throws InvalidArgumentException If label is empty
    */
   public function setLabel($label)
   {
      MindFrame2_Validate::argumentIsNotEmpty($label, 1, 'label');

      $this->_label = $label;
   }

   /**
    * Wraps setRoleId() to conform with MindFrame2_Dbms_Record_Interface
    *
    * @param mixed $role_id Database identifier
    *
    * @return void
    */
   public function setPrimaryKey($role_id)
   {
      return $this->setRoleId($role_id);
   }

   /**
    * Sets the role's id
    *
    * @param int $role_id The Role ID
    *
    * @return void
    */
   public function setRoleId($role_id)
   {
      return $this->_role_id =  $role_id;
   }
}
