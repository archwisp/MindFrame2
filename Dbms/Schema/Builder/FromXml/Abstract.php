<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Abstract factory for creating model objects from XML definitions
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
 * Abstract factory for creating model objects from XML definitions
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
abstract class MindFrame2_Dbms_Schema_Builder_FromXml_Abstract
{
   /**
    * Converts XML string values to native types
    *
    * @param string $xml Data to be cast
    *
    * @return mixed
    */
   protected function autoCast($xml)
   {
      $string = (string)$xml;

      if (is_numeric($string))
      {
         $value = (int)$string;
      }
      elseif (strtolower($string) === 'true')
      {
         $value = TRUE;
      }
      elseif (strtolower($string) === 'false')
      {
         $value = FALSE;
      }
      elseif (empty($string))
      {
         $value = NULL;
      }
      else
      {
         $value = $string;
      }

      return $value;
   }

   /**
    * Reads the XML definition from the specified file
    *
    * @param string $file_name Path to file
    *
    * @return SimpleXMLElement or FALSE
    *
    * @throws RuntimeException If the XML file could not be loaded
    */
   public function loadFromFile($file_name)
   {
      $xml = simplexml_load_file($file_name);

      if (!$xml instanceof SimpleXMLElement)
      {
         throw new RuntimeException('Failed to load XML file');
      }

      return $this->load($xml);
   }
}
