<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Number handling utility
 */

/**
 * Number handling utility
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2010-07-23
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
