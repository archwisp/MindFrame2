<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Client data utility
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
 * Client data utility
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Client
{
   /**
    * Fetches the remote client's address
    *
    * @return string
    */
   public static function fetchIpAddress()
   {
      return isset($_SERVER['REMOTE_ADDR'])
         ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
   }
}
