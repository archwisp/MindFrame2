<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * System information utility
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
 * System information utility
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
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
