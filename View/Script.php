<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * MVC view for use with view scripts
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
 * MVC view for use with view scripts
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
abstract class MindFrame2_View_Script extends MindFrame2_View_Abstract
{
   /**
    * Expands the $data array keys into local variables, buffers and includes 
    * the specified file, and returns the captured output.
    */
   protected function captureScript($file_name, array $data = array())
   {
      // For array('foo' => 'bar'), you will have variable $foo available 
      // to your view script with a value of 'bar'.

      foreach ($data as $variable => $value)
      {
           $$variable = $value;
      }

      include $file_name;
      return ob_get_clean();
   } 

   /**
    * Set up output buffering. This function is called from the construct. 
    *
    * @return void
    */
   protected function init()
   {
      ob_start();
   }
}
