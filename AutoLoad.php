<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Source file auto-loader for intantiated objects
 */

/**
 * Source file auto-loader for intantiated objects
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
class MindFrame2_AutoLoad
{
   /**
    * Construct
    *
    * @param string $class_name The name of the class to be loaded
    *
    * @return void
    */
   public static function load($class_name)
   {
      if (class_exists($class_name))
      {
         return TRUE;
      }

      $file_name = self::parseNameToPath($class_name);

      $include_paths = explode(PATH_SEPARATOR, get_include_path());

      foreach ($include_paths as $include_path)
      {
         $full_path = $include_path . '/'. $file_name;

         if (file_exists($full_path))
         {
            include_once $file_name;
            return TRUE;
         }
         // end if // (file_exists($full_path)) //
      }
      // end foreach // ($include_paths as $include_path) //
      
      return FALSE;
   }

   public static function install()
   {
      spl_autoload_register('self::load');
   }

   /**
    * Maps the class name to the appropriate file
    *
    * @param string $class_name The name of the class to be loaded
    *
    * @return string
    */
   protected static function parseNameToPath($class_name)
   {
      $path = str_replace('_', '/', $class_name) . '.php';
      return($path);
   }
}
