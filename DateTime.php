<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Date/Time Utility
 */

/**
 * Date/Time Utility
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
class MindFrame2_DateTime
{
   const FIVE_MINUTES = 300;
   const ONE_HOUR = 3600;

   /**
    * Builds an array containing the values from 0-23 but ordered so that the
    * current hour is the last element and any overflowing values are
    * pre-pended.
    *
    * Examples:
    *
    *    If the current hour is 23, the resulting array will be:
    *    (0, 1, 2, ..., 21, 22, 23)
    *
    *    If the current hour is 8, the resulting array will be:
    *    (9, 10, 11, ..., 22, 23, 0, ..., 6, 7, 8)
    *
    * @return array
    */
   public static function buildFloating24HourArray()
   {
      $current_hour = (int)date('H', time());

      $hours = range(0, $current_hour);

      if ($current_hour < 23)
      {
         $hours = array_merge(range($current_hour + 1, 23), $hours);
      }

      return $hours;
   }

   /**
    * Generates a string composed of a unix timestamp followed by a decimal
    * point and 6 digits representing microseconds.
    *
    * @return string
    */
   public static function buildMicroSecondTimeString()
   {
      list($microseconds, $time) = explode(' ', microtime());
      $string = sprintf("%s.%s", $time, substr($microseconds, 2, 6));

      return $string;
   }

   /**
    * Builds a multi-dimensional array of time-zones. The first dimension
    * represents the "continent" and the second dimension contains all of the
    * time-zones that are defined within it.
    *
    * @return array() or FALSE
    */
   public static function buildTimeZones()
   {
      if (($zones = timezone_identifiers_list()) === FALSE)
      {
         return FALSE;
      }
      // end if // (($zones = timezone_identifiers_list()) === FALSE) //

      $time_zones = array();

      foreach ($zones as $zone)
      {
         if (strpos($zone, '/') !== FALSE)
         {
            list($continent, $city) = explode('/', $zone);
         }
         else
         {
            $continent = 'Other';
            $city = $zone;
         }
         // end else // if (strpos($zone, '/') !== FALSE) //

         if (!array_key_exists($continent, $time_zones))
         {
            $time_zones[$continent] = array();
         }
         // end if // (!array_key_exists($continent, $time_zones)) //

         $time_zones[$continent][$zone] = $city;
      }
      // end foreach // ($zones as $zone) //

      return $time_zones;
   }

   /**
    * Builds a syslog compatible date/time string out of the specified unix
    * timestamp.
    *
    * @param int $timestamp Unix timestamp
    *
    * @return string
    */
   public static function buildSyslogDateTime($timestamp)
   {
      MindFrame2_Validate::argumentIsInt($timestamp, 1, 'timestamp');

      return sprintf('%s %s %s',
         date('M', $timestamp),
         str_pad(date('n', $timestamp), 2, ' ', STR_PAD_LEFT),
         date('H:i:s', $timestamp));
   }

   /**
    * Returns the significant portion of the date. If the timestamp occurred
    * today, the date portion will be omitted.
    *
    * @param int $timestamp Unix timestamp
    *
    * @return string
    *
    * @throws InvalidArgumentException If the date parameter is not a valid
    * unix timestamp
    */
   public static function getSignificantDateTime($timestamp)
   {
      MindFrame2_Validate::argumentIsInt($timestamp, 1, 'timestamp');

      if (date('Y-m-d', $timestamp) === date('Y-m-d'))
      {
         return date('H:i', $timestamp);
      }
      elseif (date('Y', $timestamp) === date('Y'))
      {
         return date('m/d H:i', $timestamp);
      }
      else
      {
         return date('Y-m-d H:i', $timestamp);
      }
   }
}
