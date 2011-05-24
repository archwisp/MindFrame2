<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Factory for creating MindFrame2_Dbms_Schema_Permission objects from XML definitions
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
 * Factory for creating MindFrame2_Dbms_Schema_Permission objects from XML definitions
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
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
