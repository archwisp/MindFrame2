<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * DBMS connection model for file-based databases
 */

/**
 * DBMS connection model for file-based databases
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2010-12-09
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
