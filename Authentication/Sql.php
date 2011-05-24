<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * SQL authentication module
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
 * SQL authentication module
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Authentication_Sql implements MindFrame2_Authentication_Interface
{
   private $_adapter;
   private $_crypto_module;
   private $_dbi;
   private $_encryption_key;
   private $_password_field;
   private $_iv_field;
   private $_table;
   private $_username_field;

   public function __construct(MindFrame2_Dbms_Dbi_Interface $dbi,
      MindFrame2_Dbms_Schema_Adapter_ToSql_Interface $adapter,
      MindFrame2_Crypto $crypto_module, $encryption_key,
      $table, $username_field, $password_field, $iv_field)
   {
      $this->_adapter = $adapter;
      $this->_crypto_module = $crypto_module;
      $this->_dbi = $dbi;
      $this->_encryption_key = $encryption_key;
      $this->_password_field = $password_field;
      $this->_iv_field = $iv_field;
      $this->_table = $table;
      $this->_username_field = $username_field;
   }

   /**
    * Authenticates the username/password
    *
    * @param string $username Username for the user being authenticated
    * @param string $password Password for the user being authenticated
    *
    * @return bool
    */
   public function authenticate($username, $password)
   {
      $record = $this->_fetchPasswordRecord($username);

      if ($record === FALSE)
      {
         return FALSE;
      }

      $iv = base64_decode($record[$this->_iv_field]);

      $ciphertext = $this->_crypto_module->encrypt(
         $password, $this->_encryption_key, $iv);

      if ($ciphertext !== $record[$this->_password_field])
      {
         return FALSE;
      }

      return TRUE;
   }

   /**
    * Sets the specified user's password to the specified string
    *
    * @param string $username The user for which to set the password
    * @param string $password The password to be set
    *
    * @return bool
    */
   public function setPassword($username, $password)
   {
      $iv = $this->_crypto_module->generateIv();

      $ciphertext = $this->_crypto_module->encrypt(
         $password, $this->_encryption_key, $iv);

      $encoded_iv = base64_encode($iv);

      $record = $this->_fetchPasswordRecord($username);

      if ($record === FALSE)
      {
         if ($this->_insertPasswordRecord($username, $ciphertext, $encoded_iv))
         {
            return TRUE;
         }
      }
      else
      {
         if ($this->_updatePasswordRecord($username, $ciphertext, $encoded_iv))
         {
            return TRUE;
         }
      }

      return FALSE;
   }

   private function _buildFieldPrefix()
   {
      return $this->_table . $this->_adapter->getFieldDelimiter();
   }

   private function _fetchPasswordRecord($username)
   {
      if (empty($username))
      {
         return FALSE;
      }

      $prefix = $this->_buildFieldPrefix();

      $sql = $this->_adapter->buildSelectTableSql($this->_table,
         array($prefix . $this->_username_field => $username), array(), 1);

      $result = $this->_dbi->query($sql, NULL);

      return $result->fetch(MindFrame2_Dbms_Result::FETCH_ASSOC);
   }

   private function _insertPasswordRecord($username, $password, $iv)
   {
      $prefix = $this->_buildFieldPrefix();

      $update_data = array(
         $prefix . $this->_username_field => $username,
         $prefix . $this->_password_field => $password,
         $prefix . $this->_iv_field => $iv);

      $sql = $this->_adapter->buildInsertTableSql($this->_table, $update_data);

      return $this->_dbi->exec($sql, NULL);
   }

   private function _updatePasswordRecord($username, $password, $iv)
   {
      $prefix = $this->_buildFieldPrefix();

      $update_data = array(
         $prefix . $this->_username_field => $username,
         $prefix . $this->_password_field => $password,
         $prefix . $this->_iv_field => $iv);

      $sql = $this->_adapter->buildUpdateTableSql($this->_table, $update_data);

      return $this->_dbi->exec($sql, NULL);
   }
}
