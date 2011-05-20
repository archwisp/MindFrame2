<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Factory for creating MindFrame2_Dbms_Schema_ForeignKey objects from XML definitions
 */

/**
 * Factory for creating MindFrame2_Dbms_Schema_ForeignKey objects from XML definitions
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2009-09-14
 */
class MindFrame2_Dbms_Schema_Builder_FromXml_ForeignKey extends MindFrame2_Dbms_Schema_Builder_FromXml_Abstract
{
   /**
    * Create a MindFrame2_Dbms_Schema_ForeignKey object from an XML definition
    *
    * @param SimpleXMLElement $xml XML foreign key definition
    * @param MindFrame2_Dbms_Schema_Table $table Table model in which the referenced
    * field objects exist
    * @param MindFrame2_Dbms_Schema_Database $database Database model in which the
    * referenced table objects exist
    *
    * @return MindFrame2_Dbms_Schema_ForeignKey
    *
    * @throws RuntimeException If the Label field fails to load
    */
   public function load(SimpleXMLElement $xml,
      MindFrame2_Dbms_Schema_Table $table, MindFrame2_Dbms_Schema_Database $database)
   {
      $name = (string)$xml->attributes()->name;

      $fk_field_names = explode(
         ',', (string)$xml->attributes()->foreign_key_fields);

      $fk_field_names = array_map('trim', $fk_field_names);
      $foreign_key_fields = $table->getFieldsByNames($fk_field_names);

      $pk_table_name = (string)$xml->attributes()->primary_key_table;
      $primary_key_table = $database->getTableByName($pk_table_name);

      $pk_field_names = explode(
         ',', (string)$xml->attributes()->primary_key_fields);

      $pk_field_names = array_map('trim', $pk_field_names);

      $primary_key_fields =
         $primary_key_table->getFieldsByNames($pk_field_names);

      $label_field_name = (string)$xml->attributes()->label_field;

      try
      {
         $label_field = $primary_key_table->getFieldByName($label_field_name);
      }
      catch (Exception $exception)
      {
         throw new RuntimeException(sprintf(
            'Failure when trying to load label field: %s',
            $exception->getMessage()));
      }

      return new MindFrame2_Dbms_Schema_ForeignKey($name, $foreign_key_fields,
         $primary_key_table, $primary_key_fields, $label_field);
   }
}
