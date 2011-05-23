<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Core functions which could be added to PHP's standard library
 */

/**
 * Core functions which could be added to PHP's standard library
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
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
