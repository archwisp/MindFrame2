<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Syslog log handler
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
 * Syslog log handler
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Log_File_Syslog extends MindFrame2_Log_File_Abstract
{
   /**
    * Reads a single line from the source
    *
    * @return string or FALSE
    */
   public function fetchLine()
   {
      return fgets($this->getStream());
   }
}
