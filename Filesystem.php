<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Collection of filesystem functions
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
 * Collection of filesystem functions
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Filesystem
{
   public static function assertFileExists($file_name)
   {
      if (!file_exists($file_name))
      {
         throw new RuntimeException(
            sprintf('The specified file does not exist (%s)', $file_name));
      }
   }

   public static function assertFileIsReadable($file_name)
   {
      if (!is_readable($file_name))
      {
         throw new RuntimeException(
            sprintf('Could not read the specified file (%s)', $file_name));
      }
   }

   /**
    * Returns a directory listing filtered by the specified regular
    * expression
    *
    * @param string $directory The directory path to get the listing for
    * @param string $filter The regular expression used to filter the results
    *
    * @return array
    */
   public static function ls($directory, $filter)
   {
      $files = array();

      $dir = dir($directory);
      while (FALSE !== ($item = $dir->read()))
      {
         if (preg_match($filter, $item) === 1)
         {
            $files[] = $item;
         }
         // end if // is_dir //
      }
      // end while // read directory //

      sort($files);

      return($files);
   }

   /**
    * Returns a recursive directory listing filtered by the specified regular
    * expression
    *
    * @param string $directory The directory path to get the listing for
    * @param string $filter The regular expression used to filter the results
    *
    * @return array
    */
   public static function lsRecursive($directory, $filter)
   {
      $dir = self::ls($directory, '/^[^\.]+/');

      $files = array();

      foreach ($dir as $item)
      {
         if (preg_match($filter, $item) === 1)
         {
            $files[] = $directory . '/' . $item;
         }
         // end if // (preg_match($filter, $item) === 1) //

         $subdir = $directory . '/' . $item;

         if (is_dir($subdir))
         {
            $files = array_merge($files, self::lsRecursive($subdir, $filter));
         }
         // end if // (is_dir($subdir)) //
      }
      // end foreach // ($dir as $item) //

      sort($files);

      return($files);
   }
}
