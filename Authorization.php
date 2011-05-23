<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Handles all authorization routines for the framework
 */

/**
 * Handles all authorization routines for the framework. This class operates in
 * a similar manner as a file system. An ACL is created for each object as
 * necessary and permission entries are added to the ACL. This class also
 * supports cascading permission for parent/child objects. Permissions granted
 * to a parent object will apply to all of its children.
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
class MindFrame2_Authorization
{
   /**
    * @var array
    */
   private $_object_map = array();

   /**
    * @var array
    */
   private $_parent_map = array();

   /**
    * @var MindFrame2_Model_User
    */
   private $_user;

   /**
    * Creates an object child => parent relationship
    *
    * @param MindFrame2_Object $child Child object
    * @param MindFrame2_Object $parent Parent object
    *
    * @return bool
    */
   public function addObjectParent(MindFrame2_Object $child, MindFrame2_Object $parent)
   {
      $child_id = $child->getObjectId();
      $parent_id = $parent->getObjectId();

      if ($child_id !== $parent_id)
      {
         $this->_object_map[$child_id] = $child;
         $this->_object_map[$parent_id] = $parent;

         $this->_parent_map[$child_id] = $parent_id;

         return TRUE;
      }

      return FALSE;
   }

   /**
    * Returns the object's child objects
    *
    * @param MindFrame2_Object $object Parent object
    *
    * @return array
    */
   protected function getObjectChildren(MindFrame2_Object $object)
   {
      $object_id = $object->getObjectId();
      $child_ids = array_keys($this->_parent_map, $object_id);

      $children = array();

      foreach ($child_ids as $child_id)
      {
         $children[] = $this->_object_map[$child_id];
      }

      return $children;
   }

   /**
    * Returns the object's parent object
    *
    * @param MindFrame2_Object $object Child object
    *
    * @return MindFrame2_Object or FALSE
    */
   protected function getObjectParent(MindFrame2_Object $object)
   {
      $object_id = $object->getObjectId();

      if (array_key_exists($object_id, $this->_parent_map))
      {
         $parent_id = $this->_parent_map[$object_id];
         return $this->_object_map[$parent_id];
      }

      return FALSE;
   }

   /**
    * Sets the user model which will be checked for determining authorization
    *
    * @param MindFrame2_Model_User $user User model
    *
    * @return void
    */
   public function setUser(MindFrame2_Model_User $user)
   {
      $this->_user = $user;
   }

   /**
    * Returns the user model set in the object
    *
    * @return MindFrame2_Model_User or NULL
    */
   public function getUser()
   {
      return $this->_user;
   }

   /**
    * Determines whether the user has the specified access to the
    * object or the object's parents.
    *
    * @param string $permission_name Name of permission to check for
    * @param MindFrame2_Object $object Object
    *
    * @return bool
    */
   public function hasPermissionByNameRecursive(
      $permission_name, MindFrame2_Object $object)
   {
      if ($this->hasPermissionByName($permission_name, $object))
      {
         return TRUE;
      }
      // end if // ($this->hasPermissionByName($permission_name, $object)) //

      $children = $this->getObjectChildren($object);

      foreach ($children as $child)
      {
         if ($this->hasPermissionByName($permission_name, $child))
         {
            return TRUE;
         }
         // end if // ($this->hasPermissionByName($permission_name, $object)) //
      }
      // end foreach // ($children as $child) //

      if ($this->parentHasPermissionByNameRecursive($permission_name, $object))
      {
         return TRUE;
      }
      // end if // ($this->parentHasPermissionByNameRecursive ... //

      return FALSE;
   }

   /**
    * Recursively checks parents for the specified permission
    *
    * @param string $permission_name Name of permission to check for
    * @param MindFrame2_Object $object Object
    *
    * @return bool
    */
   protected function parentHasPermissionByNameRecursive(
      $permission_name, MindFrame2_Object $object)
   {
      $parent = $this->getObjectParent($object);

      if ($parent instanceof MindFrame2_Object)
      {
         if ($this->hasPermissionByName($permission_name, $parent))
         {
            return TRUE;
         }
         // end if // ($this->hasPermissionByName($permission_name, $object)) //

         return $this->parentHasPermissionByNameRecursive(
            $permission_name, $parent);
      }
      // end if // ($parent instanceof MindFrame2_Object) //

      return FALSE;
   }

   /**
    * Checks to see if the object has been assigned ANY of the specified
    * permissions. The search is performed against the permissions' names.
    *
    * @param array $permissions_names Names to search for
    * @param MindFrame2_Object $object Object to be checked
    *
    * @return bool
    */
   public function hasAnyPermissionByNames(
      array $permissions_names, MindFrame2_Object $object)
   {
      foreach ($permissions_names as $name)
      {
         if ($this->hasPermissionByNameRecursive($name, $object))
         {
            return TRUE;
         }
      }

      return FALSE;
   }

   /**
    * Checks to see if the object has been assigned ALL of the specified
    * permissions. The search is performed against the permissions' names.
    *
    * @param array $permissions_names Names to search for
    * @param MindFrame2_Object $object Object to be checked
    *
    * @return bool
    */
   public function hasAllPermissionsByNames(
      array $permissions_names, MindFrame2_Object $object)
   {
      foreach ($permissions_names as $name)
      {
         if (!$this->hasPermissionByNameRecursive($name, $object))
         {
            return FALSE;
         }
      }

      return TRUE;
   }

   /**
    * Checks to see if a permission with the specified name has been assigned
    * to the object. This function was merely created for abstraction purposes.
    * Permissions checks should be made with hasPermissionByNameRecursive().
    *
    * @param string $name Name to search for
    * @param MindFrame2_Object $object Object to be checked
    *
    * @return bool
    */
   protected function hasPermissionByName($name, MindFrame2_Object $object)
   {
      $role_id = $this->getUser()->getRole()->getRoleId();
      $acl = $object->getAcl();

      if (!$acl instanceof MindFrame2_Model_ACL)
      {
         return FALSE;
      }

      $permissions = $acl->getPermissions();

      foreach ($permissions as $permission)
      {
         if (($permission->getName() === $name)
            && (strtolower($permission->getRole()) === strtolower($role_id)))
         {
            return TRUE;
         }
      }

      return FALSE;
   }

   /**
    * Fetches whether or not a user is assigned the specified function
    *
    * @param string $function_id Function id to check for
    *
    * @return bool
    */
   public function canUserPerformFunction($function_id)
   {
      $user = $this->getUser();

      if ($user instanceof MindFrame2_Model_User)
      {
         $role = $user->getRole();

         if ($role instanceof MindFrame2_Model_Role)
         {
            $function = $role->getFunctionById($function_id);
            return ($function instanceof MindFrame2_Model_Function) ? TRUE : FALSE;
         }
         // end if // ($role instanceof MindFrame2_Model_Role) //
      }
      // end if // ($user instanceof MindFrame2_Model_User) //

      return FALSE;
   }
}
