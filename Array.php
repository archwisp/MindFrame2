<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Collection of array functions
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
 * Collection of array functions
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Array
{
   /**
    * Extracts the specified column from a multi-dimensional array.
    *
    * @param array $input Input array
    * @param mixed $key Array key of the column to be extracted
    *
    * @return array
    */
   public static function extractColumn(array $input, $key)
   {
      $output = array();

      foreach ($input as $record)
      {
         $output = $record[$key];
      }

      return $output;
   }

   public static function implodeUrlParameters($first_delimiter, array $parameters)
   {
      $query_string = NULL;
      $delimiter = $first_delimiter;

      foreach ($parameters as $key => $value)
      {
         $query_string .= sprintf('%s%s=%s', $delimiter, $key, $value);
         $delimiter = '&';
      }

      return $query_string;
   }

   /**
    * Converts an indexed array into an associative array based on a
    * delimiter
    *
    * rekey(array('foo=bar'), '=');
    *
    *  returns: array('foo' => 'bar')
    *
    * @param array $array The array be re-indexed
    * @param string $delimiter The delimiter to split the element on
    *
    * @return array
    */
   public static function rekey(array $array, $delimiter)
   {
      $new_array = array();

      foreach ($array as $element)
      {
         $parts = explode($delimiter, $element);
         $index = trim($parts[0]);
         $value = trim(self::ifsetValue($parts, 1));
         $new_array[$index] = $value;
      }
      // end foreach // array //

      return($new_array);
   }

   /**
    * This function should be used to obtain vailues from an array index
    * which may not be defined
    *
    * @param array &$array The array to check
    * @param string $index The index of the value to check for
    *
    * @return mixed
    */
   public static function ifsetValue(array &$array, $index)
   {
      if (isset($array[$index]))
      {
         return($array[$index]);
      }
      else
      {
         return(NULL);
      }
   }

   /**
    * Attempts to explode the specified element by the specified delimiter into
    * multiple elements. If it is successful, the input array is expanded, and
    * the new elements are inserted where the target elment was. If the target
    * element does not exist or the delimiter is not found within the target
    * element, the input array is returned.
    *
    * @param string $delimiter Delimiter by which the target element will be
    * split
    * @param array $input The input array
    * @param int $index The target element
    *
    * @return array
    */
   public static function explodeElement($delimiter, array $input, $index)
   {
      MindFrame2_Validate::argumentIsInt($index, 3, 'index');

      if (!array_key_exists($index, $input))
      {
         return $input;
      }

      if (strpos($input[$index], $delimiter) !== FALSE)
      {
         $begin = array_slice($input, 0, $index);
         $exploded = explode($delimiter, $input[$index]);

         $end = ($index + 1 < count($input))
            ? array_slice($input, $index + 1) : array();

         $output = array_merge($begin, $exploded, $end);

         return $output;
      }

      return $input;
   }

   /**
    * Attempts to isolate the first pattern group from the remainder of the
    * string. If it is successful, the input array is expanded, and the new
    * elements are inserted where the target elment was. If the target element
    * does not exist or the delimiter is not found within the target element,
    * the input array is returned.
    *
    * @param string $pattern Regular expression by which the target element
    * will be split
    * @param array $input The input array
    * @param int $index The target element
    *
    * @return array
    */
   public static function pregSanitizeElement($pattern, array $input, $index)
   {
      MindFrame2_Validate::argumentIsInt($index, 3, 'index');

      if (!array_key_exists($index, $input))
      {
         return $input;
      }

      $matches = array();

      if (!preg_match($pattern, $input[$index], $matches)
         || ($matches[0] === $matches[1]))
      {
         return $input;
      }

      $split_pos = strlen($matches[1]);

      $exploded = array(
         substr($input[$index], 0, $split_pos),
         trim(substr($input[$index], $split_pos)));

      $begin = array_slice($input, 0, $index);

      $end = ($index + 1 < count($input))
         ? array_slice($input, $index + 1) : array();

      $output = array_merge($begin, $exploded, $end);

      return $output;
   }

   /**
    * Removes empty elements from the specified array.
    *
    * @param array $&array Array to be filtered
    *
    * @return void
    */
   public static function unsetEmptyElements(array &$array)
   {
      foreach ($array as $key => $element)
      {
         if (empty($element))
         {
            unset($array[$key]);
         }
         // end if // (empty($element)) //
      }
      // end foreach // ($array as $key => $element) //
   }
}
