<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Factory for creating MindFrame2_Dbms_Schema_Database objects from XML definitions
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
 * Factory for creating MindFrame2_Dbms_Schema_Database objects from XML definitions
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Dbms_Schema_Builder_FromXml_Database extends MindFrame2_Dbms_Schema_Builder_FromXml_Abstract
{
   /**
    * Create a MindFrame2_Dbms_Schema_Database object from an XML definition
    *
    * @param SimpleXMLElement $xml XML database definition
    *
    * @return MindFrame2_Dbms_Schema_Database
    */
   public function load(SimpleXMLElement $xml)
   {
      $database = new MindFrame2_Dbms_Schema_Database((string)$xml->attributes()->name);

      $this->loadTables($xml, $database);

      return $database;
   }

   /**
    * Loads the tables from the specified XML and adds them to the specified
    * object
    *
    * @param SimpleXmlElement $xml XML to be parsed
    * @param MindFrame2_Dbms_Schema_Database $database Database model object to which the
    * tables will be added
    *
    * @return void
    */
   protected function loadTables($xml, MindFrame2_Dbms_Schema_Database $database)
   {
      $table_loader = new MindFrame2_Dbms_Schema_Builder_FromXml_Table();

      foreach ($xml->table as $table_xml)
      {
         $table_loader->load($table_xml, $database);
      }
   }
}
