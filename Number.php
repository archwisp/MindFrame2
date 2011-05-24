<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Number handling utility
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
 * Number handling utility
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Number
{
   /**
    * Determines whether the specified number is a valid bit value
    *
    * @param bit $number The number to be tested
    *
    * @return bool
    */
   public static function isBit($number)
   {
      if ($number === 0 || $number === 1)
      {
         return TRUE;
      }

      return FALSE;
   }
}
