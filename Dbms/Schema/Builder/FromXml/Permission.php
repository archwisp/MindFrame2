<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Factory for creating MindFrame2_Dbms_Schema_Permission objects from XML definitions
 */

/**
 * Factory for creating MindFrame2_Dbms_Schema_Permission objects from XML definitions
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
class MindFrame2_Dbms_Schema_Builder_FromXml_Permission extends MindFrame2_Dbms_Schema_Builder_FromXml_Abstract
{
   /**
    * Create a MindFrame2_Dbms_Schema_Permission object from an XML definition
    *
    * @param SimpleXMLElement $xml XML index definition
    *
    * @return MindFrame2_Dbms_Schema_Permission
    */
   public function load(SimpleXMLElement $xml)
   {
      return new MindFrame2_Dbms_Schema_Permission(
         (string)$xml->attributes()->name,
         (string)$xml->attributes()->role);
   }
}
