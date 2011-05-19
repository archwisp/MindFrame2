<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * User model
 */

/**
 * User model
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2009-12-01
 */
class MindFrame2_Model_User implements MindFrame2_Dbms_Record_Interface
{
   /**
    * @var string
    */
   private $_email_address;

   /**
    * @var MindFrame2_Model_Role
    */
   private $_roles;

   /**
    * @var int
    */
   private $_user_id;

   /**
    * @var string
    */
   private $_username;

   /**
    * Initializes the username property
    *
    * @param int $user_id User ID
    * @param string $username Username
    * @param string $email_address Email Address
    * @param MindFrame2_Model_Role $role User Role
    *
    * @throws InvalidArgumentException If username argument is empty
    */
   public function __construct($user_id, $username, $email_address,
      MindFrame2_Model_Role $role)
   {
      MindFrame2_Validate::argumentIsNotEmpty($username, 2, 'username');

      $this->_user_id = $user_id;
      $this->_username = $username;
      $this->_role = $role;

      $this->setEmailAddress($email_address);
   }

   /**
    * Returns the role to which the user belongs
    *
    * @return MindFrame2_Model_Role
    */
   public function getRole()
   {
      return $this->_role;
   }

   /**
    * Returns the $email_address property of the object
    *
    * @return string
    */
   public function getEmailAddress()
   {
      return $this->_email_address;
   }

   /**
    * Wraps getUserId() to conform with MindFrame2_Dbms_Record_Interface
    *
    * @return void
    */
   public function getPrimaryKey()
   {
      return $this->getUserId();
   }

   /**
    * Returns the user's id
    *
    * @return int
    */
   public function getUserId()
   {
      return $this->_user_id;
   }

   /**
    * Returns the username
    *
    * @return string
    */
   public function getUserName()
   {
      return $this->_username;
   }

   /**
    * Sets the users' email address
    *
    * @param string $email_address Email Address
    *
    * @return void
    *
    * @throws InvalidArgumentException If email address argument is empty
    */
   public function setEmailAddress($email_address)
   {
      MindFrame2_Validate::argumentIsNotEmpty($email_address, 1, 'email_address');

      $this->_email_address = $email_address;
   }

   /**
    * Wraps setUserId() to conform with MindFrame2_Dbms_Record_Interface
    *
    * @param double $user_id Database identifier
    *
    * @return void
    */
   public function setPrimaryKey($user_id)
   {
      return $this->setUserId($user_id);
   }

   /**
    * Sets the role to which the user belongs
    *
    * @param MindFrame2_Model_Role $role The role to which the user belongs
    *
    * @return void
    */
   public function setRole(MindFrame2_Model_Role $role)
   {
      $this->_role = $role;
   }

   /**
    * Sets the user's database identifier
    *
    * @param double $user_id Database identifier
    *
    * @return void
    */
   public function setUserId($user_id)
   {
      return $this->_user_id = $user_id;
   }
}
