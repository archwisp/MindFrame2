<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Abstract log handler
 */

/**
 * Abstract log handler
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2009-12-01
 */
abstract class MindFrame2_Log_Abstract
{
   /**
    * @var resource
    */
   protected $stream;

   /**
    * Returns the number of lines from the beginning of the stream to the
    * pointer location
    *
    * @return int
    */
   public abstract function getPosition();

   /**
    * Sets the position of the pointer in the stream
    *
    * @param int $position Number of lines from the beginning of the stream
    *
    * @return bool
    */
   public abstract function setPosition($position);

   /**
    * Reads a single line from the source
    *
    * @return mixed
    */
   public abstract function fetchLine();

   /**
    * Checks to be sure that the pointer is not already open before attempting
    * to open
    * the stream
    *
    * @return bool
    */
   protected abstract function openStreamOnce();

   /**
    * Checks to be sure that the pointer exists before attempting to close it
    *
    * @return bool
    */
   protected abstract function closeStreamIfOpen();

   /**
    * Reads the specified number of lines from the source
    *
    * @param int $line_count How many lines to read
    *
    * @return array
    */
   public function fetchLines($line_count)
   {
      $lines = array();

      for ($x = 0; $x < $line_count; $x++)
      {
         $lines[] = $this->readLine();
      }

      return $lines;
   }

   /**
    * Opens the stream handler to the source
    *
    * @return resource
    */
   protected function getStream()
   {
      $this->openStreamOnce();

      return $this->stream;
   }
}
