<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Factory for creating MindFrame2_Dbms_Schema_Index objects from XML definitions
 */

/**
 * Factory for creating MindFrame2_Dbms_Schema_Index objects from XML definitions
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2009-09-14
 */
class MindFrame2_Dbms_Schema_Builder_FromXml_Index extends MindFrame2_Dbms_Schema_Builder_FromXml_Abstract
{
   /**
    * Create a MindFrame2_Dbms_Schema_Index object from an XML definition
    *
    * @param SimpleXMLElement $xml XML index definition
    * @param MindFrame2_Dbms_Schema_Table $table Table model in which the referenced 
    * field objects exist
    *
    * @return MindFrame2_Dbms_Schema_Index
    */
   public function load(SimpleXMLElement $xml, MindFrame2_Dbms_Schema_Table $table)
   {
      $field_names = explode(',', (string)$xml->attributes()->fields);
      $field_names = array_map('trim', $field_names);
      $fields = $table->getFieldsByNames($field_names);

      return new MindFrame2_Dbms_Schema_Index(
         $this->autoCast($xml->attributes()->name),
         $this->autoCast($xml->attributes()->type),
         $fields);
   }
}
