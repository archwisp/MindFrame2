<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Abstract log handler
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
 * Abstract log handler
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
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
