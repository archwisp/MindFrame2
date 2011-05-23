<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Factory for creating MindFrame2_Dbms_Schema_Field objects from XML definitions
 */

/**
 * Factory for creating MindFrame2_Dbms_Schema_Field objects from XML definitions
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
class MindFrame2_Dbms_Schema_Builder_FromXml_Field extends MindFrame2_Dbms_Schema_Builder_FromXml_Abstract
{
   /**
    * Create a MindFrame2_Dbms_Schema_Field object from an XML definition
    *
    * @param SimpleXMLElement $xml XML field definition
    *
    * @return MindFrame2_Dbms_Schema_Field
    */
   public function load(SimpleXMLElement $xml)
   {
      $allow_null = $this->autoCast($xml->attributes()->allow_null);
      $allow_null = is_null($allow_null) ? FALSE : $allow_null;

      $auto_increment = $this->autoCast($xml->attributes()->is_auto_increment);
      $auto_increment = is_null($auto_increment) ? FALSE : $auto_increment;

      $field = new MindFrame2_Dbms_Schema_Field(
         $this->autoCast($xml->attributes()->name),
         $this->autoCast($xml->attributes()->type),
         $this->autoCast($xml->attributes()->length),
         $allow_null,
         $this->autoCast($xml->attributes()->default_value),
         $auto_increment);

      return $field;
   }
}
