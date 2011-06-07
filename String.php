<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * String handling utility
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
 * String handling utility
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
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
   
   /**
    * Validates an email address
    *
    * @param string $string Email address to validate
    *
    * @return bool
    */
   public static function isValidEmailAddress($string)
   {
      $expr = '/^[\w|\d|\.\-|]{1,}@[\w|\d|\.\-|]{1,}\.[a-zA-Z]{1,}/';
      $match = preg_match($expr, $string);

      if (empty($match))
      {
         return FALSE;
      }
      // end if // (empty($match)) //

      return TRUE;
   }

   /**
    * Validates a STRING representation of a float value. This is handy
    * because floating point precision varies accross systems and storing 
    * floats values as strings is sometimes necessary.
    *
    * @param string $string Value to be validated
    * @param bool $allow_zero Whether or not to allow a zero value
    *
    * @return bool
    *
    * @throws InvalidArgumentException if string argument is not a string type
    * @throws InvalidArgumentException if allow_zero argument is not a boolean
    */
   public static function isValidFloatString($string, $allow_zero)
   {
      MindFrame2_Core::assertArgumentIsString($string, 1, 'string');
      MindFrame2_Core::assertArgumentIsBool($allow_zero, 2, 'allow_zero');

      if (($dec_pos = strpos($string, '.')) === FALSE || (!is_numeric($string)))
      {
         return FALSE;
      }

      if ((!$allow_zero) && $string === '0.0')
      {
         return FALSE;
      }

      $integral = substr($string, 0, $dec_pos);
      $fractional = substr($string, $dec_pos + 1);

      if ((int)$integral != $integral || (int)$fractional != $fractional)
      {
         return FALSE;
      }

      return TRUE;
   }
   
   /**
    * Determines whether the specified string is an IPv4 address
    *
    * @param string $string The target string
    *
    * @return bool
    *
    * @todo Check for broadcast addresses
    */
   public static function isValidIPv4Address($string)
   {
      if (preg_match('/' . MindFrame2_String::IPV4_PATTERN . '$/', $string))
      {
         return TRUE;
      }

      return FALSE;
   }

   /**
    * Determines whether the specified string is a valid MAC (Media Access
    * Control) address
    *
    * @param string $string The target string
    *
    * @return bool
    */
   public static function isValidMacAddress($string)
   {
      if (preg_match('/' . MindFrame2_String::MAC_PATTERN . '/', $string))
      {
         return TRUE;
      }

      return FALSE;
   }
}
