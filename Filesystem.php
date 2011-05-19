<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Collection of filesystem functions
 */

/**
 * Collection of filesystem functions
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2008-03-01
 */
class MindFrame2_Filesystem
{
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
            $files = array_merge($files, self::listRecursive($subdir, $filter));
         }
         // end if // (is_dir($subdir)) //
      }
      // end foreach // ($dir as $item) //

      sort($files);

      return($files);
   }
}
