<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * System information utility
 */

/**
 * System information utility
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2010-06-04
 */
class MindFrame2_System
{
   /**
    * Returns the MAC address of the specified network interface
    *
    * @param string $interface Network interface
    *
    * @return string
    */
   public static function getHardwareAddress($interface)
   {
      $cmd = sprintf("/sbin/ifconfig %s", $interface);
      $cmd .= " | head -n 1";
      $cmd .= " | awk -F  ' ' '{print $5}'";

      return exec($cmd);
   }

   /**
    * Returns the IP address of the specified network interface
    *
    * @param string $interface Network interface
    *
    * @return string
    */
   public static function getIpAddress($interface)
   {
      $cmd = sprintf("/sbin/ifconfig %s", $interface);
      $cmd .= " | fgrep 'inet '";
      $cmd .= " | sed -r 's/.*?addr:(\S+).*/\\1/'";

      return exec($cmd);
   }

   /**
    * Returns the system load average
    *
    * @return array
    */
   public static function getLoadAverage()
   {
      return sys_getloadavg();
   }

   /**
    * Returns the disk usage percentage of the specified path
    *
    * @param string $path Filesystem path
    *
    * @return float
    */
   public static function getDiskUsage($path)
   {
      return ((disk_free_space($path) / disk_total_space($path)) * 100);
   }
}
