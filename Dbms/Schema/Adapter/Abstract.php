<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Abstract database schema model adapter
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
 * Abstract database schema model adapter
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
abstract class MindFrame2_Dbms_Schema_Adapter_Abstract
{
   /**
    * @var MindFrame2_Dbms_Schema_Database
    */
   private $_database;

   /**
    * @var string
    */
   private $_field_delimiter;

   /**
    * Builds the object
    *
    * @param MindFrame2_Dbms_Schema_Database $database The database model to be adapted
    * @param string $field_delimiter Delimiter used to separate tables and
    * fields
    */
   public function __construct(
      MindFrame2_Dbms_Schema_Database $database, $field_delimiter)
   {
      $this->_database = $database;
      $this->_field_delimiter = $field_delimiter;
   }

   /**
    * Returns the database property of the object
    *
    * @return MindFrame2_Dbms_Schema_Database
    */
   public function getDatabase()
   {
      return $this->_database;
   }

   /**
    * Returns the string used for fully qulaified table/field names
    *
    * @return string
    */
   public function getFieldDelimiter()
   {
      return $this->_field_delimiter;
   }
}
