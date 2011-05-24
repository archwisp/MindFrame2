<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Factory for creating MindFrame2_Dbms_Schema_Index objects from XML definitions
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
 * Factory for creating MindFrame2_Dbms_Schema_Index objects from XML definitions
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
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
