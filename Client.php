<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Client data utility
 */

/**
 * Client data utility
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2010-07-26
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
