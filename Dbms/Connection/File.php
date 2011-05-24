<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * DBMS connection model for file-based databases
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
 * DBMS connection model for file-based databases
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Dbms_Connection_File
   implements MindFrame2_Dbms_Connection_Interface
{
   /**
    * @param string
    */
   private $_dbms;

   /**
    * @var string
    */
   private $_file_name;

   /**
    * Construct
    *
    * @param string $dbms DBMS identifier
    * @param string $file_name File in which the database resides
    */
   public function __construct($dbms, $file_name)
   {
      MindFrame2_Validate::argumentIsNotEmpty($dbms, 1, 'dbms');
      MindFrame2_Validate::argumentIsNotEmpty($file_name, 2, 'file_name');

      $this->_dbms = $dbms;
      $this->_file_name = $file_name;
   }

   /**
    * Builds the dsn string for the connection
    *
    * @return string
    */
   public function buildDsn()
   {
      return sprintf('%s:%s', $this->_dbms, $this->_file_name);
   }

   /**
    * Returns the DBMS identifier
    *
    * @return string
    */
   public function getDbms()
   {
      return $this->_dbms;
   }

   /**
    * Returns the name of the file in which the database resides
    *
    * @return string
    */
   public function getFileName()
   {
      return $this->_file_name;
   }
}
