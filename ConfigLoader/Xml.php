<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * XML configuration file loader
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
 * XML configuration file loader
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_ConfigLoader_Xml extends MindFrame2_ConfigLoader_Abstract
{
   public function load($component, $setting)
   {
      MindFrame2_Core::assertArgumentIsNotBlank($setting, 2, 'setting');

      $parsed = $this->parseFile($component);
      $children = (array)$parsed->children();

      if (!array_key_exists($setting, $children))
      {
         throw new UnexpectedValueException(
            sprintf('Could not find the specified ' .
            'configuation setting (%s)', $setting));
      }

      // SimpleXML's attributes() function returns a multi-dimensional
      // array, so we'll only return the first element.

      $child = (array)$children[$setting]->attributes();
      return reset($child);
   }

   protected function parse($content)
   {
      return simplexml_load_string($content);
   }
}
