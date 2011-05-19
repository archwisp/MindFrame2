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
abstract class MindFrame2_Log_File_Abstract extends MindFrame2_Log_Abstract
{
   const MODE_EXCLUSIVE = 'exclusive';
   const MODE_APPEND = 'append';

   /**
    * @var string
    */
   protected $line_ending;

   /**
    * @var string
    */
   protected $path;

   /**
    * Construct
    *
    * @param string $path Path to the log source
    * @param string $mode Mode in which the file will be opened
    *
    * @throws InvalidArgumentException If the specified mode is not supported
    */
   public function __construct($path, $mode)
   {
      if ($this->isValidMode($mode))
      {
         throw new InvalidArgumentException(
            sprintf('Invalid mode (%s)', $mode));
      }

      $this->path = $path;
      $this->mode = $mode;
   }

   /**
    * Returns the number of lines from the beginning of the stream to the
    * pointer location
    *
    * @return int
    */
   public function getPosition()
   {
      return ftell($this->getStream());
   }

   /**
    * Sets the position of the pointer in the stream
    *
    * @param int $position Number of lines from the beginning of the stream
    *
    * @return bool
    */
   public function setPosition($position)
   {
      return fseek($this->stream, $position);
   }

   /**
    * Sets the string that will be used by the readLine() function to determine
    * where a line ends
    *
    * @param string $line_ending Line ending delimiter
    *
    * @return bool
    */
   public function setLineEnding($line_ending)
   {
      $this->line_ending = $line_ending;
   }

   /**
    * Returns the path to the log file
    *
    * @return string
    */
   protected function getPath()
   {
      return $this->path;
   }

   /**
    * Checks to be sure that the pointer is not already open before attempting 
    * to open the stream
    *
    * @return bool
    *
    * @throws RuntimeException If the file cannot be opened
    */
   protected function openStreamOnce()
   {
      if ($this->stream === NULL)
      {
         $file_name = $this->getPath();

         if (!is_file($file_name))
         {
            throw new RuntimeException(
               sprintf('File not found (%s)', $file_name));
         }
         // end if // (is_readable($file_name)) //

         if (!is_readable($file_name))
         {
            throw new RuntimeException(sprintf(
               'Permission deined when trying to open file (%s)', $file_name));
         }
         // end if // (is_readable($file_name)) //

         $this->stream = fopen($file_name, 'r');

         if ($this->stream === FALSE)
         {
            throw new RuntimeException(
               sprintf('Error opening file (%s)', $file_name));
         }
         // end if // ($this->getStream() === FALSE) //

         return TRUE;
      }
      // end if // ($this->stream !== NULL) //

      return FALSE;
   }

   /**
    * Checks to be sure that the pointer exists before attempting to close it
    *
    * @return bool
    */
   protected function closeStreamIfOpen()
   {
      if ($this->getStream() !== NULL)
      {
         return fclose($this->stream);
      }

      return FALSE;
   }

   /**
    * Validates the file mode
    *
    * @param string $mode Mode in which the file should be opened
    *
    * @return bool
    */
   protected function isValidMode($mode)
   {
      if (defined('self::MODE_' . strtoupper($mode)))
      {
         return TRUE;
      }

      return FALSE;
   }
}
