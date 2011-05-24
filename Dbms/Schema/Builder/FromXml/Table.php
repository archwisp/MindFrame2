<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Factory for creating MindFrame2_Dbms_Schema_Table objects from XML definitions
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
 * Factory for creating MindFrame2_Dbms_Schema_Table objects from XML definitions. Child
 * object loaders are created for loading child elements and the resulting
 * objects are added to the table object.
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Dbms_Schema_Builder_FromXml_Table extends MindFrame2_Dbms_Schema_Builder_FromXml_Abstract
{
   /**
    * Create a MindFrame2_Dbms_Schema_Table object from an XML definition
    *
    * @param SimpleXMLElement $xml XML table definition
    * @param MindFrame2_Dbms_Schema_Database $database Database model in which the
    * referenced objects exist
    *
    * @return MindFrame2_Dbms_Schema_Table
    */
   public function load(SimpleXMLElement $xml, MindFrame2_Dbms_Schema_Database $database)
   {
      $database->addTable(
         $table = new MindFrame2_Dbms_Schema_Table((string)$xml->attributes()->name));

      $this->loadFields($xml, $table);
      $this->loadIndexes($xml, $table);
      $this->loadForeignKeys($xml, $table, $database);

      return $table;
   }

   /**
    * Creates field objects from the child XML field definitions via the
    * MindFrame2_Dbms_Schema_Builder_FromXml_Field factory and adds them to the specified table
    * model.
    *
    * @param SimpleXMLElement $xml XML table definition
    * @param MindFrame2_Dbms_Schema_Table $table Table model
    *
    * @return bool
    */
   protected function loadFields(
      SimpleXMLElement $xml, MindFrame2_Dbms_Schema_Table $table)
   {
      $field_loader = new MindFrame2_Dbms_Schema_Builder_FromXml_Field();

      foreach ($xml->field as $field_xml)
      {
         $table->addField($field_loader->load($field_xml));
      }

      return TRUE;
   }

   /**
    * Creates index objects from the child XML index definitions via the
    * MindFrame2_Dbms_Schema_Builder_FromXml_Index factory and adds them to the specfied table
    * model.
    *
    * @param SimpleXMLElement $xml XML table key definition
    * @param MindFrame2_Dbms_Schema_Table $table Table model
    *
    * @return bool
    */
   protected function loadIndexes(
      SimpleXMLElement $xml, MindFrame2_Dbms_Schema_Table $table)
   {
      $index_loader = new MindFrame2_Dbms_Schema_Builder_FromXml_Index();

      if (isset($xml->primary_key))
      {
         $table->setPrimaryKey($index_loader->load($xml->primary_key, $table));
      }

      if (isset($xml->index))
      {
         foreach ($xml->index as $index_xml)
         {
            $table->addIndex($index_loader->load($index_xml, $table));
         }
         // end foreach // ($xml->index as $index_xml) //
      }
      // end if // (isset($xml->index)) //
   }

   /**
    * Creates foreign key objects from the child XML foreign key definitions
    * via the MindFrame2_Dbms_Schema_Builder_FromXml_ForeignKey factory and adds them to the
    * specfied table model.
    *
    * @param SimpleXMLElement $xml XML table definition
    * @param MindFrame2_Dbms_Schema_Table $table Table model
    * @param MindFrame2_Dbms_Schema_Database $database Database model in which the
    * referenced objects exist
    *
    * @return bool
    */
   protected function loadForeignKeys(SimpleXMLElement $xml,
      MindFrame2_Dbms_Schema_Table $table, MindFrame2_Dbms_Schema_Database $database)
   {
      $foreign_key_loader = new MindFrame2_Dbms_Schema_Builder_FromXml_ForeignKey();

      if (isset($xml->foreign_key))
      {
         foreach ($xml->foreign_key as $foreign_key_xml)
         {
            $table->addForeignKey(
               $foreign_key_loader->load($foreign_key_xml, $table, $database));
         }
         // end foreach // ($xml->foreign_key as $foreign_key_xml) //
      }
      // end if // (isset($xml->foreign_key)) //
   }
}
