<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * String handling utility
 */

/**
 * String handling utility
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2010-06-18
 */
class MindFrame2_String
{
   /**
    * @var string
    */
   const IPV4_PATTERN = '(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)';

   /**
    * @var string
    */
   const MAC_PATTERN = '([0-9a-f]{2}[:-]){5}[0-9a-f]{2}';

   /**
    * Tokenize the the string as space-separated values taking quoted strings
    * into account
    *
    * @param string $string String to be tokenized
    *
    * @return array
    */
   public static function tokenize($string)
   {
      $delimiters = array(' ', ',', ';');
      $quotes = array('"', "'");

      $tokens = array();
      $length = strlen($string);

      $token = NULL;
      $quote_started = FALSE;

      for ($x = 0; $x < $length; $x++)
      {
         $char = substr($string, $x, 1);

         if ((!$quote_started) && in_array($char, $quotes))
         {
            $quote_started = TRUE;
         }
         elseif ($quote_started && in_array($char, $quotes))
         {
            $quote_started = FALSE;
         }

         if ((!$quote_started) && in_array($char, $delimiters))
         {
            $tokens[] = $token;
            $token = NULL;
         }
         else
         {
            $token .= $char;
         }
      }
      // end for // ($x = 0; $x < $length; $x++) //

      $tokens[] = $token;

      return $tokens;
   }

   /**
    * Extracts an IPv4 address from the specified string
    *
    * @param string $string The target string
    *
    * @return string or FALSE
    */
   public static function extractIPv4Address($string)
   {
      $matches = array();

      if (preg_match('/' . self::IPV4_PATTERN . '/', $string, $matches))
      {
         return reset($matches);
      }

      return FALSE;
   }
}
