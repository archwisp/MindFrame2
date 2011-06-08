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
   protected function captureScript($file_name)
   {
      ob_flush();
      ob_start();
      include $file_name;
      return ob_get_clean();
   }
}
