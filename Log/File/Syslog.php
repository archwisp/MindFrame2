<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Syslog log handler
 */

/**
 * Syslog log handler
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
class MindFrame2_Log_File_Syslog extends MindFrame2_Log_File_Abstract
{
   /**
    * Reads a single line from the source
    *
    * @return string or FALSE
    */
   public function fetchLine()
   {
      return fgets($this->getStream());
   }
}
