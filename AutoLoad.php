<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Source file auto-loader for intantiated objects
 */

/**
 * Source file auto-loader for intantiated objects
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2008-10-08
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

      if (!file_exists($file_name))
      {
         return FALSE;
      }
      
      include_once $file_name;
   
      return TRUE;
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
