<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Abstract file authentication module
 */

/**
 * Abstract file authentication module
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
abstract class MindFrame2_Authentication_File_Abstract
   extends MindFrame2_Authentication_Abstract
{
   /**
    * @var string
    */
   private $_file_name;

   /**
    * Initializes the file name to be used for authentication
    *
    * @param string $file_name File name containing login information
    */
   public function __construct($file_name)
   {
      $this->_file_name = $file_name;
   }

   /**
    * Reads the contents of the file into an array
    *
    * @return array
    */
   protected function readFile()
   {
      return file($this->_file_name);
   }

   /**
    * Writes the contents into the file
    *
    * @param string $contents The contents to be written to the file
    *
    * @return bool
    *
    * @throws RuntimeException If the password file is not writable
    */
   protected function writeFile($contents)
   {
      if (!is_writable($this->_file_name))
      {
         throw new RuntimeException(
            'Permission denied while trying to write password file.');
      }

      if (file_put_contents($this->_file_name, $contents) === FALSE)
      {
         return FALSE;
      }

      return TRUE;
   }
}
