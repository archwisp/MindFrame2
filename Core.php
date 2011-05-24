<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Core functions which could be added to PHP's standard library
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
 * Core functions which could be added to PHP's standard library
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Core
{
   /**
    * Returns the first argument value which is not empty
    *
    * @return mixed or FALSE
    */
   public static function coalesce()
   {
      $arguments = func_get_args();

      foreach ($arguments as $argument)
      {
         if (!empty($argument))
         {
            return $argument;
         }
      }
      // end foreach // ($arguments as $argument) //

      return FALSE;
   }
}
