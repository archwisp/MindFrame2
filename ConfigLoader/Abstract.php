<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Config loader interface
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
 * Config loader interface
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
abstract class MindFrame2_ConfigLoader_Abstract
   implements MindFrame2_ConfigLoader_Interface
{
   private $_extension;
   private $_path;
   private $_parsed = array();
   
   public function __construct($path, $extension)
   {
      $this->_path = $path;
      $this->_extension = $extension;
   }

   protected function parseFile($component)
   {
      if (!array_key_exists($component, $this->_parsed))
      {
         $file_name = $this->buildFileName($component);

         MindFrame2_Filesystem::assertFileExists($file_name);
         MindFrame2_Filesystem::assertFileIsReadable($file_name);

         $content = file_get_contents($file_name);
         $this->_parsed[$component] = $this->parse($content);
      }

      return $this->_parsed[$component];
   }

   protected function buildFileName($component)
   {
      MindFrame2_Core::assertArgumentIsNotBlank($component, 1, 'component');

      $path = is_null($this->_path) ? NULL : $this->_path . '/';
      $extension = is_null($this->_extension) ? NULL : '.' . $this->_extension;

      return $path . $component . $extension;
   }
}
