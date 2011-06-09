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
   public static function assertArgumentIsBool($value, $position, $name)
   {
      if (!is_bool($value))
      {
         $skel = 'Expected boolean value for argument #%d (%s), %s given';
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
   public static function assertArgumentIsInt($value, $position, $name)
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
   public static function assertArgumentIsIntOrNull($value, $position, $name)
   {
      if (!is_int($value) && !is_null($value))
      {
         $skel = 'Expected integer value for argument #%d (%s), %s given';
         $message = sprintf($skel, $position, $name, gettype($value));

         throw new InvalidArgumentException($message);
      }
   }
   
   /**
    * Validates that an argument is not blank and throws a structured exception
    * on failure. If the value is a string, it will be trimmed before 
    * checking. 
    *
    * @param mixed $value Value to be validated
    * @param int $position Argument number
    * @param strin $name Argument name
    *
    * @return void
    *
    * @throws InvalidArgumentException on failure
    */
   public static function assertArgumentIsNotBlank($value, $position, $name)
   {
      if (trim($value) === '')
      {
         $skel = 'Argument #%d (%s) cannot be blank';
         $message = sprintf($skel, $position, $name);

         throw new InvalidArgumentException($message);
      }
   }
   
   /**
    * Validates that an argument is not empty and throws a structured exception
    * on failure. If the value is a string, it will be trimmed before 
    * checking. 
    *
    * @param mixed $value Value to be validated
    * @param int $position Argument number
    * @param strin $name Argument name
    *
    * @return void
    *
    * @throws InvalidArgumentException on failure
    */
   public static function assertArgumentIsNotEmpty($value, $position, $name)
   {
      if (empty($value))
      {
         $skel = 'Argument #%d (%s) cannot be empty';
         $message = sprintf($skel, $position, $name);

         throw new InvalidArgumentException($message);
      }
   }
   
   /**
    * Validates a string argument and throws a structured exception on
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
   public static function assertArgumentIsString($value, $position, $name)
   {
      if (!is_string($value))
      {
         $skel = 'Expected string value for argument #%d (%s), %s given';
         $message = sprintf($skel, $position, $name, gettype($value));

         throw new InvalidArgumentException($message);
      }
   }

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
