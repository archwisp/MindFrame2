<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Collection of validation functions
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
 * Collection of validation functions
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Validate
{
   /**
    * Validates a boolean argument and throws a structured exception on
    * failure.
    *
    * @param int $value Value to be validated
    * @param int $position Argument number
    * @param strin $name Argument name
    *
    * @return void
    *
    * @throws InvalidArgumentException on failure
    */
   public static function argumentIsBool($value, $position, $name)
   {
      if (!is_bool($value))
      {
         $skel = 'Expected boolean value for argument #%d (%s), %s given';
         $message = sprintf($skel, $position, $name, gettype($value));

         throw new InvalidArgumentException($message);
      }
   }

   /**
    * Validates a boolean argument and throws a structured exception on
    * failure.
    *
    * @param int $value Value to be validated
    * @param int $position Argument number
    * @param strin $name Argument name
    *
    * @return void
    *
    * @throws InvalidArgumentException on failure
    */
   public static function argumentIsBoolOrNull($value, $position, $name)
   {
      if (!(is_null($value) || is_bool($value)))
      {
         $message = sprintf(
            'Expected NULL or boolean value for argument #%d (%s), %s given',
            $position, $name, gettype($value));

         throw new InvalidArgumentException($message);
      }
   }

   /**
    * Validates an integer argument and throws a structured exception on
    * failure.
    *
    * @param int $value Value to be validated
    * @param int $position Argument number
    * @param strin $name Argument name
    *
    * @return void
    *
    * @throws InvalidArgumentException on failure
    */
   public static function argumentIsInt($value, $position, $name)
   {
      if (!is_int($value))
      {
         $skel = 'Expected integer value for argument #%d (%s), %s given';
         $message = sprintf($skel, $position, $name, gettype($value));

         throw new InvalidArgumentException($message);
      }
   }

   /**
    * Validates an integer argument and throws a structured exception on
    * failure.
    *
    * @param int $value Value to be validated
    * @param int $position Argument number
    * @param strin $name Argument name
    *
    * @return void
    *
    * @throws InvalidArgumentException on failure
    */
   public static function argumentIsIntOrNull($value, $position, $name)
   {
      if (!(is_null($value) || is_int($value)))
      {
         $message = sprintf(
            'Expected NULL or integer value for argument #%d (%s), %s given (%s)',
            $position, $name, gettype($value), $value);

         throw new InvalidArgumentException($message);
      }
   }

   /**
    * Validates an IPV4 address argument.
    *
    * @param string $value Value to be validated
    * @param int $position Argument number
    * @param strin $name Argument name
    *
    * @return void
    *
    * @throws InvalidArgumentException If valus is not a valid address
    */
   public static function argumentIsIPv4Address($value, $position, $name)
   {
      if (MindFrame2_Validate::isValidIPv4Address($value))
      {
         return TRUE;
      }

      $skel = 'Expected an IPV4 address for argument #%d (%s), %s given';
      $message = sprintf($skel, $position, $name, gettype($value));

      throw new InvalidArgumentException($message);
   }

   /**
    * Validates that an argument is not empty and throws a structured exception
    * on failure
    *
    * @param mixed $value Value to be validated
    * @param int $position Argument number
    * @param strin $name Argument name
    *
    * @return void
    *
    * @throws InvalidArgumentException on failure
    */
   public static function argumentIsNotEmpty($value, $position, $name)
   {
      $value = is_string($value) ? trim($value) : $value;

      if (empty($value))
      {
         $skel = 'Argument #%d (%s) cannot be empty';
         $message = sprintf($skel, $position, $name);

         throw new InvalidArgumentException($message);
      }
   }

   /**
    * Validates a time-based ID. The structure of such is a string
    * representation of a float value where the integral portion is composed of
    * a standard unix timestamp and the composition of the fractional portion
    * is: the first two digits representing micro-seconds folowed by four
    * random digits. The fractional portion must always contain six digits
    * total.
    *
    * @param mixed $value Value to be validated
    * @param int $position Argument number
    * @param strin $name Argument name
    *
    * @return void
    *
    * @throws InvalidArgumentException if string argument is not a string type
    */
   public static function argumentIsTimeBasedIdOrNull($value, $position, $name)
   {
      if (is_null($value))
      {
         return TRUE;
      }

      if (is_string($value) && (($dec_pos = strpos($value, '.')) !== FALSE))
      {
         $integral = substr($value, 0, $dec_pos);
         $fractional = substr($value, $dec_pos + 1);

         if ((int)$integral == $integral && (int)$fractional == $fractional
            && self::isValidTimestamp($integral) && strlen($fractional) === 6)
         {
            return TRUE;
         }
      }

      $skel = 'Expected time-based id value for argument #%d (%s), %s given';
      $message = sprintf($skel, $position, $name, gettype($value));

      throw new InvalidArgumentException($message);
   }

   /**
    * Validates an email address
    *
    * @param string $email_address Email address to validate
    *
    * @return bool
    */
   public static function isValidEmail($email_address)
   {
      $expr = '/^[\w|\d|\.\-|]{1,}@[\w|\d|\.\-|]{1,}\.[a-zA-Z]{1,}/';
      $match = preg_match($expr, $email_address);

      if (empty($match))
      {
         return FALSE;
      }
      // end if // (empty($match)) //

      return TRUE;
   }

   /**
    * Validates a STRING representation of a float value. This is handy
    * because PHP's inconsistent handling of floating point variables between
    * 32 and 64 bit systems.
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
      if (!is_string($string))
      {
         throw new InvalidArgumentException(
            'Expected string representation ' .
            'of a float for argument #1 (string)');
      }

      if (!is_bool($allow_zero))
      {
         throw new InvalidArgumentException(
            'Expected boolean value for argument #2 (allow_zero)');
      }

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

   /**
    * Validates a UNIX timestamp
    *
    * @param int $timestamp Timestamp to validate
    *
    * @return bool
    */
   public static function isValidTimestamp($timestamp)
   {
      $year = date('Y', $timestamp);
      $month = date('m', $timestamp);
      $day = date('d', $timestamp);

      $valid = checkdate($month, $day, $year);

      if ($valid === FALSE)
      {
         return FALSE;
      }
      // end if // (!$valid) //

      return TRUE;
   }
}
